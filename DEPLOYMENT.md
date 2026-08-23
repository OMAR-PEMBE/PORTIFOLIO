# Deployment configuration

## Contact mailbox

The contact handler reads its mailboxes from server environment variables; it does not contain a fallback recipient.

- `CONTACT_RECIPIENT` is required and receives submitted enquiries. Set this to your Gmail address if you want enquiries delivered to Gmail.
- `CONTACT_FROM` is required and must use a domain verified in Resend.
- `RESEND_API_KEY` is required. Use a restricted sending key and store it only in the server environment.

Configure these values in the hosting control panel, container environment, PHP-FPM pool, or Apache virtual host. For Apache, the virtual-host configuration can contain:

```apache
SetEnv CONTACT_RECIPIENT contact@the-deployed-domain.tld
SetEnv CONTACT_FROM website@the-deployed-domain.tld
SetEnv RESEND_API_KEY re_replace_with_a_secret_deployment_value
```

The recipient and sender are separate settings. For example, `CONTACT_RECIPIENT` may be `yourname@gmail.com`, while `CONTACT_FROM` must remain an address on a domain verified in Resend. The form does not need your Gmail password or Gmail API credentials.

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
