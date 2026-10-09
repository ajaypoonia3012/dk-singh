# DK Singh Fitness — Phase 2 Security Audit

Date: 2026-08-05  
Branch: `feature/full-platform-audit`  
Scope: payment verification, Filament authorization, admin identity, privileged routes, uploads, provider secrets, headers, throttling, and audit logging. No package upgrades, UI redesigns, or new business features were performed.

## Executive Summary

Phase 2 closes the highest-impact application-level security gaps identified in Phase 1. Product and membership fulfillment now require a server-created Razorpay order, a session-bound checkout context, a valid Razorpay signature, matching order/payment amount and currency, and a captured payment. All 31 Filament resource models are explicitly mapped to an administrator-only policy. Administrative identity checks now use one model method while keeping legacy columns synchronized. Media tooling is no longer public, debug UI is environment-gated, uploads have global and Media-specific restrictions, provider credentials are encrypted, state-changing requests are audit logged without payloads, security headers are globally applied, and abuse-sensitive endpoints are rate limited.

Application-level security is materially stronger, but production deployment remains blocked until known vulnerable packages can be upgraded in a separately authorized phase. The no-upgrade constraint leaves published advisories in Laravel 12.61.0, Filament Forms 3.3.52, Guzzle/PSR-7, Axios, PostCSS, concurrently/shell-quote, and transitive dependencies.

## Controls Implemented

### 1. Razorpay verification

- Checkout creates the Razorpay order server-side using credentials from configuration.
- Expected order ID, amount, currency, item type, item ID, user ID, and creation time are stored in the authenticated session.
- Success callbacks validate all required identifiers and limit their length.
- Razorpay signatures are verified with the official SDK.
- Razorpay order and payment records are fetched server-side.
- Order ID, payment order ID, amount, currency, captured status, item, user, and one-hour session expiry are verified before fulfillment.
- Previously processed payment IDs are rejected.
- Plan order, membership deactivation, and new membership creation run in one database transaction.
- Checkout state is removed after successful or duplicate processing.
- Product fulfillment no longer contains the disabled-verification path.

### 2. Filament policies

- Every model backing the 31 Filament resources is explicitly registered with `AdminPolicy`.
- The shared policy implements all Filament-relevant abilities: view, create, update, delete, bulk delete, restore, force delete, replicate, and reorder.
- Administrators are admitted through `User::isAdmin()`; all other users are denied.
- This central policy is intentionally uniform for the current single-admin-role architecture and can later be replaced module-by-module without changing resource code.

### 3. Authorization and admin identity

- Filament panel access and AdminMiddleware use the same `User::isAdmin()` method.
- `isAdmin()` accepts either legacy admin representation during transition, preserving existing administrators.
- A data migration synchronizes `account_type = admin` and `is_admin = true` in both directions.
- User model save hooks keep both legacy fields synchronized until a later schema-removal phase.

### 4. Privileged routes

- `/media-library` and `/media-test` require authentication, administrator authorization, and the admin rate limiter.
- `/grid-test` is registered only in local or testing environments.
- Filament panel requests use the named admin rate limiter in addition to Filament authentication and `canAccessPanel()`.

### 5. Upload validation

- Every Filament FileUpload receives a 20 MB ceiling and an allowlist for JPG, PNG, WebP, GIF, ICO, MP4, WebM, and PDF.
- The custom Media uploader is intentionally narrower: images only, maximum ten files, 10 MB per file, and explicit JPG/PNG/WebP/GIF validation.
- Stored Media extensions are derived from the detected MIME type instead of the client filename.
- Invalid image dimensions cause the stored object to be deleted and validation to fail safely.
- SVG is intentionally excluded because public SVG delivery can execute active content.

### 6. API key protection

- CommunicationProvider and CourierProvider credentials use Laravel encrypted casts.
- Secret attributes are hidden from serialization.
- Existing plaintext values are encrypted by migration; rollback decrypts them.
- Filament secret fields are password/revealable inputs, never hydrate existing ciphertext/plaintext into the browser, and preserve stored secrets when left blank.
- Razorpay and Delhivery values are read from `config/services.php`, making config caching safe.
- Delhivery HTTP calls now use explicit connect/read timeouts, limited retries, token authentication, and invalid-response handling.

### 7. Headers and throttling

All web responses receive:

- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `Referrer-Policy: strict-origin-when-cross-origin`
- restrictive camera, microphone, and geolocation Permissions Policy
- `Cross-Origin-Opener-Policy: same-origin-allow-popups` to retain Razorpay popup compatibility
- HSTS on HTTPS requests only

Named limiters protect contact submissions, payment callbacks, admin media tools, and Filament panel traffic. Existing Breeze login throttling remains intact.

### 8. Security audit logging

- All non-safe HTTP requests are recorded after completion.
- Events capture authenticated user, route name, action, method, path, response status, IP address, bounded user agent, exception class, and timestamps.
- Request payloads, passwords, payment signatures, API keys, and secrets are never stored.
- Audit-log persistence failures are reported without breaking the user operation.
- Indexes support event/date and user/date investigation queries.

## Remaining Security Risks

### Release blockers

1. **Known dependency vulnerabilities remain.** Package upgrades were explicitly prohibited in this phase.
2. **RichEditor XSS advisory remains.** Filament Forms 3.3.52 is affected. Treat rich content as trusted-admin-only and prioritize the approved patched release.
3. **Database uniqueness for payment IDs is not enforced.** Application replay checks are present, but a unique database index requires a pre-deployment duplicate-data assessment.
4. **Content Security Policy is deferred.** Existing inline scripts and third-party payment scripts require nonce/hash migration before a strict CSP can be enabled without breaking functionality.
5. **Audit logs are not tamper-evident.** Database access controls, append-only permissions, retention/export, and external log shipping remain operational work.

### Important follow-up

- Replace the transitional dual admin fields with one canonical role schema after all integrations and seeders are updated.
- Add Razorpay webhook verification and reconciliation for asynchronous payment/capture events.
- Queue courier and communication side effects after transaction commit.
- Add a unique payment-provider/payment-ID constraint after production data validation.
- Move sensitive transformation imagery to private storage with signed delivery.
- Apply module-specific policies if roles beyond administrator require scoped Filament access.
- Configure proxy trust correctly so rate limiting and audit IPs cannot be spoofed behind the production load balancer.
- Define audit-log retention, legal access, backup, and incident-review procedures.

## Deployment Notes

- Set `RAZORPAY_KEY` and `RAZORPAY_SECRET` before exposing checkout.
- Set Delhivery variables documented in `.env.example` if courier dispatch is enabled.
- Back up the database before running the provider-credential encryption migration.
- Preserve `APP_KEY`; changing it makes encrypted provider credentials unreadable.
- Run migrations during maintenance/controlled deployment and verify existing administrators after synchronization.
- Validate Razorpay auto-capture configuration because fulfillment intentionally requires `captured` status.
