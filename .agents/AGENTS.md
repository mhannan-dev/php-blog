# Project Rules
- Always use open-source and MIT-licensed resources in this project (libraries, packages, assets, stylesheets, fonts, and other code resources).
- Always plan before writing any code.
- Always use Tailwind CSS for styling.
- Always use DTO (Data Transfer Object) pattern in the project.
- Always use SOLID principles in the project.
- Always use Clean Architecture in the project.

---

## OWASP Top 10 — Risk Awareness

Every code change in this project must be written with the following OWASP Top 10 risks in mind. These are not optional guidelines — treat them as hard requirements.

### A01 — Broken Access Control
- Always enforce authorization checks server-side; never rely on hidden UI elements or client-side state.
- Restrict admin routes to authenticated, role-verified users only.
- Apply the principle of least privilege: users may only access their own resources.

### A02 — Cryptographic Failures
- Never store sensitive data (passwords, tokens) in plain text.
- Use `password_hash()` / `password_verify()` (bcrypt) for all new password handling — **never** `md5()` or `sha1()`.
- Enforce HTTPS for all pages that transmit credentials or personal data.
- Keep secrets (DB credentials, API keys) in `.env` only — never hard-code them.

### A03 — Injection
- Always use PDO prepared statements with bound parameters for every database query — **never** interpolate user input directly into SQL.
- Sanitize and validate all user-supplied input before using it in any context (SQL, HTML, shell, filesystem).
- Use `htmlspecialchars()` (ENT_QUOTES, UTF-8) when rendering user-supplied content in HTML.

### A04 — Insecure Design
- Apply defense-in-depth: assume any single control can be bypassed and layer multiple controls.
- Model threat scenarios during planning; document mitigations in design notes.
- Rate-limit sensitive operations (login, contact form, password reset).

### A05 — Security Misconfiguration
- Set `display_errors = Off` and `log_errors = On` in production PHP config.
- Remove or deny access to debug files, `.env`, `.git`, `composer.json` via `.htaccess`.
- Disable directory listing on all public directories.
- Set secure, `HttpOnly`, and `SameSite=Strict` flags on all session cookies.

### A06 — Vulnerable and Outdated Components
- Only use libraries that are actively maintained and MIT-licensed.
- Regularly run `composer outdated` to detect stale dependencies.
- Pin versions in `composer.json` and review changelogs before updating.

### A07 — Identification and Authentication Failures
- Enforce strong password policies on all user-facing forms.
- Regenerate the session ID on login and privilege escalation (`session_regenerate_id(true)`).
- Implement account lockout or CAPTCHA after repeated failed login attempts.
- Never expose usernames, emails, or user IDs in predictable URL patterns.

### A08 — Software and Data Integrity Failures
- Verify the integrity of any downloaded assets (checksum/signature) before including them.
- Do not deserialize untrusted data without validation.
- Use Content Security Policy (CSP) headers to restrict script sources.

### A09 — Security Logging and Monitoring Failures
- Log all authentication events (login success, failure, logout) with timestamp and IP.
- Log all access-control violations and unexpected errors.
- Never log passwords, tokens, or full credit-card numbers.
- Ensure log files are written outside the webroot and are not publicly accessible.

### A10 — Server-Side Request Forgery (SSRF)
- Validate and allowlist any URLs or hostnames before making server-side HTTP requests.
- Never forward raw user-supplied URLs to `file_get_contents()`, `curl`, or similar.
- Block requests to internal/private IP ranges (127.x, 10.x, 192.168.x) if outbound HTTP is required.
