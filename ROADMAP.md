# DK Singh Fitness — Implementation Roadmap

This roadmap converts the Phase 1 audit into gated implementation phases. Estimates assume one senior Laravel/Filament engineer with access to product owners and integration credentials. Each phase requires tests and review before the next begins.

## Phase 0 — Emergency Production Safeguards (1–3 days)

- Disable product fulfillment until Razorpay signature, order identity, amount, currency, and replay verification are restored.
- Restrict Media Library/Media Test to authorized administrators and remove Grid Test outside local/testing.
- Upgrade Laravel, Filament Forms, Guzzle/PSR-7, Axios, PostCSS, concurrently/shell-quote, and other advised packages to patched compatible releases.
- Rotate integration credentials if they have ever been exposed in logs, exports, or shared databases.
- Add temporary monitoring for payment, order, shipment, authentication, and admin-access anomalies.

**Exit gate:** no known critical dependency advisory; no unverified paid order path; no public admin tooling.

## Phase 1 — Identity, Authorization, and Secrets (1–2 weeks)

- Choose one role source of truth; reconcile `account_type`, `is_admin`, `is_coach`, and premium/membership flags.
- Add policies for every domain and register/test them; apply Filament authorization consistently.
- Replace manual ownership checks with policies or scoped route model binding where appropriate.
- Encrypt communication/courier credentials, mask them in Filament, and prevent secret serialization.
- Move direct `env()` reads into typed configuration; add deployment-time configuration validation.
- Add endpoint rate limits and secure defaults for contact, payment, and integration callbacks.

**Exit gate:** authorization matrix approved and tested; no plaintext application-managed secrets; config cache works.

## Phase 2 — Database Integrity and Portable Migrations (1–2 weeks)

- Create an authoritative ERD and map every model relationship.
- Add missing foreign keys with explicit delete behavior after orphan-data reconciliation.
- Add compound indexes based on measured query patterns.
- Normalize status values with enums/value objects and confirm money precision.
- Enforce singleton settings/theme/hero invariants.
- Audit all migration rollback paths and verify clean MySQL and SQLite migration runs.

**Exit gate:** clean migrate/rollback/migrate on CI; no orphans; query plans reviewed for high-volume paths.

## Phase 3 — Test and CI Foundation (1–2 weeks)

- Replace stale Breeze expectations with current member-profile and onboarding behavior.
- Create factories/seed states for plans, memberships, orders, settings, media, and content.
- Add policy and Filament resource smoke tests.
- Add payment replay/signature/amount tests and transactional failure tests.
- Add member ownership tests for orders, invoices, notifications, notes, plans, and progress.
- Add Livewire upload tests and malicious file cases.
- Run Pint, PHPUnit, migration checks, Composer audit, npm audit, and Vite build in CI.

**Exit gate:** critical workflows covered; zero failing tests; CI required on protected branches.

## Phase 4 — Commerce and Membership Reliability (2–3 weeks)

- Extract checkout/payment/fulfillment into application services and database transactions.
- Add idempotency and immutable payment-event records.
- Queue communications and courier dispatch after transaction commit.
- Define order, payment, shipment, and membership state machines.
- Add reconciliation commands and operational dashboards.

**Exit gate:** fault-injection tests demonstrate no duplicate orders, memberships, charges, or shipments.

## Phase 5 — Media and Website Builder Architecture (2–4 weeks)

- Split WebsiteBuilder into bounded Livewire components/services with explicit authorization.
- Remove database queries from Blade previews.
- Consolidate all uploads through the Media service with MIME/content validation, size limits, metadata safety, and cleanup.
- Add responsive image variants and queue optimization.
- Add versioning/drafts/preview safety for site content changes.

**Exit gate:** builder domains can be tested independently; all uploads share one secure pipeline.

## Phase 6 — Module Completion Waves (4–8 weeks)

1. Members, Coach Notes, Action Plans, Notifications, Progress.
2. Plans, Workout Plans, Diet Plans, Programs, Services.
3. Products, Orders, Shipments, Courier Providers.
4. Inquiries, Communication Providers, Message Templates, Communication Logs.
5. Settings, Theme Settings, Homepage Cards, Testimonials, Transformations, Transformation Photos.
6. Blog CMS reconciliation after the audited Blog branch is merged.

For each module: confirm requirements, enforce authorization, complete CRUD/filters/bulk actions, add frontend states/SEO/accessibility, optimize queries, and add tests.

## Phase 7 — Performance, UI/UX, and Production Operations (2–4 weeks)

- Profile real workloads and set query/bundle/page-performance budgets.
- Cache settings, navigation, homepage, and safe public collections with explicit invalidation.
- Consolidate dashboard aggregates and paginate all unbounded lists.
- Perform the deferred design-system/responsive/accessibility pass.
- Add queues, scheduler, backups, restore drills, structured logging, error monitoring, and actionable health checks.
- Document deployment, rollback, incident response, data retention, and disaster recovery.

**Exit gate:** production readiness review passes security, data integrity, accessibility, observability, backup, and rollback checks.
