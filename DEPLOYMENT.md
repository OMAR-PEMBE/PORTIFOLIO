# Deployment configuration

## Contact mailbox

The contact handler reads its mailboxes from server environment variables; it does not contain a fallback recipient.

- `CONTACT_RECIPIENT` is required and receives submitted enquiries.
- `CONTACT_FROM` is required and must use a domain verified in Resend.
- `RESEND_API_KEY` is required. Use a restricted sending key and store it only in the server environment.

Configure these values in the hosting control panel, container environment, PHP-FPM pool, or Apache virtual host. For Apache, the virtual-host configuration can contain:

```apache
SetEnv CONTACT_RECIPIENT contact@the-deployed-domain.tld
SetEnv CONTACT_FROM website@the-deployed-domain.tld
SetEnv RESEND_API_KEY re_replace_with_a_secret_deployment_value
```

Do not commit production mailbox settings or API keys to the repository. Verify the sender domain in Resend, including the DNS records Resend provides, before enabling the form.

The handler sends through Resend's HTTPS API with a 3-second connection timeout and 10-second total timeout. It retries network errors, HTTP `429`, and `5xx` responses up to three times with the same idempotency key, preventing duplicate delivery. Permanent `4xx` failures are not retried.

Each attempt emits a structured JSON record through PHP's `error_log`, including the request ID, attempt, HTTP status, provider delivery ID, error, and duration. Forward PHP logs to the deployment's log collector and alert on `contact.configuration_error` and repeated delivery failures.

Abuse protection permits five submissions per client IP in a rolling 15-minute window and stores counters in PHP's temporary directory. Ensure that directory is writable and shared between application instances, or replace the file-backed limiter with a shared Redis-backed limiter when deploying multiple servers. The form also includes a honeypot field.

After deployment, submit the form once and confirm the provider delivery ID appears in logs and the message arrives at `CONTACT_RECIPIENT`.
