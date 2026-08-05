# DK Singh Fitness — Enterprise Platform Audit

Audit date: 2026-08-05  
Branch: `feature/full-platform-audit` (created locally from remote `main` at `66985c4`)  
Stack verified from lockfiles/runtime: Laravel 12.61.0, PHP 8.2.12, Filament 3.3.52, Livewire 3.8.0, Composer 2.9.5, Node 24.15.0, npm 11.12.1.

## Executive Summary

The repository is a broad functional prototype with 37 models, 57 controllers, 31 Filament resources, 108 migrations, 23 services, and 178 registered routes. Most requested business areas have database tables and admin CRUD surfaces, but the platform is not production-ready. The dominant risks are authorization coverage, payment integrity, migration quality, dependency vulnerabilities, weak automated tests, inconsistent domain relationships, direct environment access, and a monolithic Website Builder.

The strongest foundations are the conventional Laravel skeleton, extensive Filament CRUD coverage, a reusable Media service layer, working authentication scaffolding, and broad feature coverage. The weakest foundations are only one policy for the entire system (and it is misnamed/deny-all), only two Form Requests, no meaningful module tests, incomplete foreign-key/index strategy, queries in views and providers, duplicated/legacy controllers, and business-critical workflows implemented without transactions or trustworthy payment verification.

Phase 1 intentionally avoids module refactors. Four runtime/test-infrastructure blockers were corrected: database reads during provider bootstrap, database reads while constructing the Filament panel, a MySQL-only migration that failed SQLite, and a missing deterministic test encryption key. The guest layout now also tolerates an unseeded settings table. No Blog CMS implementation files were changed.

## Scorecard

| Area | Score | Assessment |
|---|---:|---|
| Architecture | 48/100 | Broad layering exists, but controllers/Livewire components are oversized and boundaries are inconsistent. |
| Laravel best practices | 45/100 | Eloquent and services are used, but validation, policies, route organization, config access, typing, and migrations fall short. |
| Filament best practices | 50/100 | CRUD coverage is high; authorization, relation managers, tests, singleton settings, and secure uploads are weak. |
| Security | 32/100 | Critical product-payment bypass, weak policy coverage, public media tooling, raw HTML, plaintext provider credentials, and vulnerable dependencies. |
| Performance | 47/100 | Some eager loading exists, but view queries, repeated dashboard counts, unbounded collections, and no application caching are common. |
| UI/UX | 57/100 | Major surfaces exist and Vite/Tailwind/Bootstrap are wired; accessibility, consistency, empty/error states, and responsive QA are uneven. |
| Database | 44/100 | Schema breadth is good; relationship methods, constraints, composite indexes, portability, and migration reversibility are incomplete. |
| Testing | 23/100 | Ten mostly scaffold tests cover authentication/profile only; no Filament, Livewire, payment, authorization, service, or module tests. |
| Production readiness | 35/100 | Suitable for controlled development only; security and data-integrity gates block production release. |

## Audit Method

- Inspected all application, route, migration, model, resource, service, middleware, request, policy, test, and frontend-view files.
- Registered and inventoried all 178 routes.
- Compared model relationship declarations with foreign-key migrations.
- Inventoried CRUD forms, tables, filters, bulk actions, and resource-level authorization hooks.
- Searched for view-layer queries, raw HTML, direct `env()` calls, upload controls, duplicated routes/controllers, hardcoded integrations, and transaction boundaries.
- Installed lockfile dependencies and verified exact framework versions.
- Ran Composer and npm security audits.
- Ran PHPUnit repeatedly to isolate bootstrap, migration, configuration, template, and stale-test failures.

## Architecture Findings

### High severity

1. `app/Livewire/Builder/WebsiteBuilder.php` is a multi-domain god component of roughly two thousand lines, directly coordinating settings, media, cards, programs, products, testimonials, blogs, transformations, and sections. It creates high regression and authorization risk.
2. Route organization is a single large `routes/web.php` with repeated middleware, duplicated source declarations for action-plan and coach-note routes, anonymous/unprotected diagnostic routes, and inconsistent naming.
3. Legacy parallel entry points exist (`BlogController`, `BlogsPageController`, `ProductsPageController`, `ProgramsPageController`, `ServicesPageController`, root `HomeController`) alongside `Front` controllers. Some are empty or apparently unreachable, increasing ambiguity.
4. Domain logic resides in controllers: payment fulfillment, membership activation, courier dispatch, communication, completion checks, and report assembly. Transactions and idempotency are missing.
5. Only two Form Requests exist; most controllers validate inline and several workflows accept loosely typed payloads.

### Medium severity

- Services exist for Dashboard, Communication, Delhivery, Builder, and Media, but conventions and dependency injection are inconsistent.
- Many model relationships have no declared return type; numerous obvious foreign keys have no corresponding relationship method.
- No observers or domain events coordinate order, membership, shipment, notification, or media lifecycle behavior.
- No consistent query scopes for active/published/status/access filters.
- Multiple Blade builder previews and the footer query Eloquent directly.
- PSR-12 formatting and imports are inconsistent across core classes.

## Database Findings

### High severity

- The order payment-status migration used raw MySQL `ALTER TABLE ... MODIFY`, breaking SQLite tests and database portability. Fixed with `Schema::table()->change()`.
- Numerous `foreignId` columns do not call `constrained()`, leaving referential integrity dependent on application behavior. Examples include early orders, memberships, shipments, and several member records.
- Core models including Order, Membership, Notification, ActionPlan, CoachNote, WorkoutPlan, DietPlan, and User omit or under-specify relationships visible in the schema.
- Business lookup patterns lack deliberate compound indexes, including status/date lists, user/status/date member queries, payment status/date dashboards, notification user/read/date, and access/status content lists.
- Several migrations are additive corrections to earlier migrations and have empty or non-equivalent `down()` methods; rollback confidence is low.

### Medium severity

- Money is handled inconsistently and requires confirmation of fixed decimal precision across all tables and calculations.
- Status fields are free-form strings without enums/check constraints or centralized value objects.
- Singleton concepts (Settings, Theme Settings, Hero Settings) are ordinary multi-row tables/resources without uniqueness enforcement.
- Polymorphic order items are implemented as `item_type`/`item_id` without a formal morph relationship or referential enforcement.
- API secrets in communication/courier providers need encrypted casts and masked Filament fields.

## Security Findings

### Critical

1. `ProductPaymentController::success()` contains “payment verify disabled” behavior and marks an order paid based on client-submitted identifiers. This permits fraudulent fulfillment and courier dispatch. Disable this endpoint until server-side signature/order/amount verification and idempotency are implemented.
2. Lockfiles contain known advisories. Composer reports vulnerabilities affecting Laravel 12.61.0, Filament Forms 3.3.52 (high-severity RichEditor XSS), Guzzle, PSR-7, and related packages. npm reports seven vulnerable packages, including two critical and four high findings.

### High

- Thirty-one Filament resources have no resource authorization overrides, and the platform has only one policy. The policy is named `App\Policies\Media` rather than `MediaPolicy`, is not explicitly registered, and denies every action; effective authorization behavior is therefore unclear and untested.
- Filament access checks `account_type === 'admin'`, while `AdminMiddleware` checks `is_admin`. Divergent sources of truth can produce privilege inconsistencies.
- `/media-library`, `/media-test`, and `/grid-test` are publicly registered diagnostic/tooling routes. Media tooling must require authenticated authorized administrators; test routes must be removed outside local environments.
- Provider API keys/secrets are mass assignable and appear unencrypted at rest.
- Multiple admin upload fields omit explicit MIME allowlists and maximum sizes. MediaUploadService trusts the client extension and `getimagesize()` is called without validation/error handling.
- Raw HTML is rendered for diets and workouts. Rich content needs a documented trusted-admin boundary and server-side sanitization, especially given the current Filament RichEditor advisory.
- Payment and order workflows lack transactions, idempotency keys, replay protection, and amount/currency comparison.

### Medium

- `env()` is called directly in services, controllers, and Blade, defeating config caching and making secrets/config difficult to validate.
- External API calls need explicit connect/read timeouts, retry policy, structured error handling, and secret-safe logging.
- Rate limiting is present for login through Breeze but not documented for contact, payment callbacks, or other abuse-sensitive endpoints.
- Manual ownership checks exist in several member controllers and are good defensive behavior, but policies/scoped binding would make enforcement consistent and testable.

## Performance Findings

- `AppServiceProvider` previously queried settings/theme at every application bootstrap and unread notifications for every composed view. Fixed by deferring settings/theme loading to actual view rendering and limiting unread counts to the navbar.
- Footer and Website Builder preview Blade files query Eloquent directly.
- DashboardController and DashboardService issue many separate aggregate queries that can be consolidated and cached briefly.
- Several frontend indexes use unbounded `get()` rather than pagination.
- AccessManager can query memberships/plans repeatedly; eager loading or request-scoped memoization is needed.
- No documented cache invalidation strategy exists for settings, theme, homepage data, navigation, or high-traffic public collections.
- Media metadata is captured, but responsive variants, WebP/AVIF delivery, and explicit frontend dimensions are inconsistent.
- Vite builds separate app/theme/blog assets, but duplicate CSS framework responsibilities and bundle budgets are not documented.

## Configuration and Operations

- Exact versions are locked, but `composer.json` uses `*` for Filament and Dompdf; fresh dependency resolution can introduce breaking changes. Replace wildcards with compatible explicit constraints in a dedicated dependency-hardening phase.
- `.env.example` defaults to SQLite while the stated production target is MySQL. Document separate local/test/production expectations.
- Razorpay and Delhivery values belong in `config/services.php` or dedicated config files and should be validated during deployment.
- Queue infrastructure exists, but outbound email/SMS/courier operations are predominantly synchronous. Long-running external calls should be queued with retries and failure monitoring.
- Health check `/up` exists, but it does not verify database, storage, cache, queue, or external dependency readiness.
- Deployment documentation does not define scheduler, queue worker, storage link, cache warming, backup, rollback, or observability procedures.

## Testing Assessment

Initial failure `SQLSTATE[HY000]: no such table: settings` had two root causes: providers queried database-backed settings while the application container was booting, before test migrations, and the Filament panel did the same while being constructed. Both reads are now deferred safely.

After that fix, SQLite exposed a MySQL-only order migration; it is now database-portable. PHPUnit also lacked `APP_KEY`, which is now a deterministic non-production test key. The suite then reached application behavior and reported stale/insufficient fixtures:

- Baseline after infrastructure fixes: 25 tests discovered; 1 passed, 10 failed, and 14 emitted warnings in the final compact run.
- Authentication views assumed a seeded Settings record; the guest layout now falls back to `config('app.name')`.
- The default ExampleTest hits a database-driven homepage without `RefreshDatabase` or seeded module data.
- Profile tests expect original Breeze routes/redirects, while the application now redirects through `/member/profile` and `profile.completed` middleware.
- There are no tests for Filament resources, policies, Livewire Media/Builder, payment verification, orders, memberships, shipments, integrations, member ownership, dashboards, or frontend module pages.

Tests should be updated to the intended product contract rather than changing working product routes to satisfy stale scaffold assertions.

## Issues Fixed in Phase 1

1. Deferred Settings and ThemeSetting queries out of application bootstrap.
2. Limited unread-notification composition to the navbar instead of every view.
3. Deferred Filament brand-name database access and added a safe fallback.
4. Replaced a MySQL-only migration statement with Laravel Schema Builder syntax.
5. Added a deterministic test-only encryption key to PHPUnit configuration.
6. Made the guest layout safe when Settings has not been seeded.
7. Removed a UTF-8 BOM from the Media migration that polluted PHPUnit output and response bodies.

## Remaining Release Blockers

1. Restore verifiable Razorpay payment processing and make payment fulfillment transactional/idempotent.
2. Upgrade vulnerable Composer/npm dependencies to patched compatible versions and retest.
3. Define a single role/permission model and authorize every Filament resource and privileged route.
4. Protect/remove public Media and diagnostic routes.
5. Establish a complete MySQL and SQLite-compatible migration baseline with constraints and indexes.
6. Rebuild the test suite around current routes and business behavior; add critical workflow coverage.
7. Encrypt/mask integration secrets and move runtime environment access into configuration.
8. Validate and harden all file uploads and rich HTML output.
9. Introduce transactions, jobs, retries, and failure monitoring for order/membership/shipment/communication workflows.
10. Decompose Website Builder before substantial new features are added.

## Recommended Next Audit

The next implementation audit should be **Security and Authorization Foundations**, followed immediately by **Payments, Orders, and Membership Data Integrity**. Blog CMS should remain untouched until its audited branch is merged and reconciled.
