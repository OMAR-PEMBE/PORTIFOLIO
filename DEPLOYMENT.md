# Deployment configuration

## Contact mailbox

The contact handler reads its mailboxes from server environment variables; it does not contain a fallback recipient.

- `CONTACT_RECIPIENT` is required and receives submitted enquiries. Set this to your Gmail address if you want enquiries delivered to Gmail.
- `CONTACT_FROM` is required and must be a bare email address. Until you own a domain, use Resend's `onboarding@resend.dev` testing sender. This testing sender can deliver only to the email address associated with your Resend account.
- `RESEND_API_KEY` is required. Use a restricted sending key and store it only in the server environment.

Configure these values in the hosting control panel, container environment, PHP-FPM pool, or Apache virtual host. For Apache, the virtual-host configuration can contain:

```apache
SetEnv CONTACT_RECIPIENT contact@the-deployed-domain.tld
SetEnv CONTACT_FROM website@the-deployed-domain.tld
SetEnv RESEND_API_KEY re_replace_with_a_secret_deployment_value
```

The recipient and sender are separate settings. For example, `CONTACT_RECIPIENT` may be `yourname@gmail.com`, while `CONTACT_FROM` is `onboarding@resend.dev` during testing and later becomes an address on your verified domain. The form does not need your Gmail password or Gmail API credentials.

### Create the Resend API key

1. Sign in to the Resend dashboard.
2. Open **API Keys** from the left navigation.
3. Select **Create API Key**.
4. Name it `portfolio-render` and select the sending-only permission when offered.
5. Create the key and copy the value beginning with `re_` immediately. Resend shows the complete secret only at creation time.
6. Put it in your local `.env` as `RESEND_API_KEY=re_...`. Never paste it into source code, commit it to Git, or send it in chat.
7. For testing without a domain, set `CONTACT_RECIPIENT` to the exact email address used for your Resend account and set `CONTACT_FROM=onboarding@resend.dev`.

When you later buy a domain, add it in Resend under **Domains**, publish the SPF and DKIM DNS records, wait for verification, and replace `CONTACT_FROM` with a bare address such as `website@yourdomain.com`.

Do not commit production mailbox settings or API keys to the repository. Verify the sender domain in Resend, including the DNS records Resend provides, before enabling the form.

The handler sends through Resend's HTTPS API with a 3-second connection timeout and 10-second total timeout. It retries network errors, HTTP `429`, and `5xx` responses up to three times with the same idempotency key, preventing duplicate delivery. Permanent `4xx` failures are not retried.

Set `CONTACT_QUEUE_DIR` to enable the durable outbox. The web request then atomically writes the message and returns immediately; run `php scripts/process-contact-queue.php` from a supervised cron or worker process. Leave it unset to keep synchronous delivery.

Each attempt emits a structured JSON record through PHP's `error_log`, including the request ID, attempt, HTTP status, provider delivery ID, error, and duration. Forward PHP logs to the deployment's log collector and alert on `contact.configuration_error` and repeated delivery failures.

Abuse protection permits five submissions per client IP in a rolling 15-minute window. Set `REDIS_DSN` for a shared Redis-backed limiter in multi-server deployments; otherwise counters are stored in PHP's temporary directory for single-server deployments. The form also includes a honeypot field.

After deployment, submit the form once and confirm the provider delivery ID appears in logs and the message arrives at `CONTACT_RECIPIENT`.

## Portfolio admin

The project editor is available at `/admin/`. Set `ADMIN_PASSWORD_HASH` to a PHP `password_hash()` value before using it. Generate one with:

```powershell
php -r "echo password_hash('choose-a-long-password', PASSWORD_DEFAULT), PHP_EOL;"
```

Never deploy the hash from a shared example or reuse an account password. Admin sessions use strict, HTTP-only cookies, expire after two hours of inactivity, and failed login attempts are limited to five per IP/username pair every 15 minutes. If the site is behind a reverse proxy, restrict `/admin/` by IP or add a second authentication layer at the proxy where practical.

Logout is a CSRF-protected POST action. Verify HTTPS is enabled before signing in so the session cookie is marked Secure.

The admin stores changes atomically in `data/projects.json`, which is intentionally ignored by Git. Ensure the `data` directory is writable by the web server and back up this file in production.

Homepage text and project content are managed together at `/admin/dashboard.php` after login and stored in `data/site-content.json` and `data/projects.json`. Project gallery lines accept legacy asset paths or typed direct URLs in the format `image | URL`, `video | URL`, or `website | URL`.

Project media uploads accept JPG, PNG, WebP, GIF, MP4, WebM, and MOV files. Images are limited to 10 MiB each and videos to 100 MiB each. Configure PHP and Apache upload limits (`upload_max_filesize` and `post_max_size`) high enough for the video limit when needed.

The included Apache `.htaccess` blocks access to internal directories and dotfiles, disables directory indexes, and adds baseline security headers. Confirm that the virtual host permits `AllowOverride` or copy these directives into the virtual-host configuration. Add `Strict-Transport-Security` at the HTTPS virtual host or reverse proxy only after HTTPS works across the whole domain.

Keep `CONTACT_QUEUE_DIR`, backups, and logs outside the public document root. The `test_connection.php` diagnostic is intentionally not part of the application; do not recreate or deploy public connection-test endpoints. Configure PHP with `display_errors=Off` and forward `error_log` to private, monitored storage.

Back up `data/projects.json`, `data/site-content.json`, and `assets/uploads/projects` together. Test restoration periodically so records and their uploaded media remain consistent.

## Deploy from GitHub to Render

GitHub stores the repository; the PHP site itself must run as a Render Web Service. This repository includes a Docker runtime and `render.yaml` Blueprint.

1. Confirm `.env` is not staged by running `git status`. Never commit it.
2. Commit and push the application files, including `Dockerfile`, `docker/`, `health.php`, and `render.yaml`, to GitHub.
3. In Render, select **New > Blueprint**, connect the GitHub repository, and deploy its `render.yaml`.
4. When Render requests secret values, provide `ADMIN_PASSWORD_HASH`, `CONTACT_RECIPIENT`, and `RESEND_API_KEY`. Use the password hash, not the plain admin password.
5. Keep `CONTACT_FROM=onboarding@resend.dev` until a custom domain has been verified in Resend.
6. Wait for `/health.php` to report healthy, then test the public pages, admin login, and one contact submission at the assigned `onrender.com` HTTPS address.

The free Render instance has an ephemeral filesystem. Admin edits and uploaded project media can disappear on a restart or deployment. For a temporary free deployment, treat the Git-tracked files in `data/` and `assets/img/` as the source of truth. For durable admin editing, attach a persistent disk mounted at `/var/www/storage` on a compatible paid instance; `APP_DATA_DIR` and `UPLOAD_STORAGE_DIR` are already configured beneath that mount. Only files under a persistent disk's mount path survive restarts and deploys.
