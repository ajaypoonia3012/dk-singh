# DK Singh Fitness — Module Status

Legend: **Complete** = production-capable for stated scope; **Partial** = meaningful implementation exists but material gaps remain; **Missing** = no meaningful implementation; **Broken** = a defect/security issue prevents safe use. Effort is an implementation estimate: XS (<1 day), S (1–3 days), M (3–7 days), L (1–3 weeks), XL (3+ weeks).

No module is rated Complete because cross-cutting authorization and test coverage are not production-grade.

## Dashboard

- **Status:** Partial
- **Missing features:** Permission-aware widgets, configurable date ranges, caching, operational/error metrics.
- **Bugs:** Many independent aggregate queries; widget access is not tested by role.
- **Recommended improvements:** Consolidate DashboardService queries, add short-lived cache, authorize widgets, add query-count tests.
- **Estimated effort:** M

## Communication Logs

- **Status:** Partial
- **Missing features:** Retry/failure workflow, correlation IDs, payload redaction, retention policy, delivery webhooks.
- **Bugs:** Relationship coverage and sensitive-data handling are incomplete.
- **Recommended improvements:** Queue communications, normalize statuses, redact secrets/PII, add filters/export and delivery tests.
- **Estimated effort:** M

## Communication Providers

- **Status:** Broken for production security
- **Missing features:** Encrypted credentials, health checks, provider adapters, failover.
- **Bugs:** API keys/secrets are fillable and apparently stored plaintext; resource authorization is absent.
- **Recommended improvements:** Encrypted casts, masked non-dehydrated secret fields, rotation workflow, policy and connectivity tests.
- **Estimated effort:** M

## Message Templates

- **Status:** Partial
- **Missing features:** Versioning, preview/test send, locale/channel variants, variable schema validation.
- **Bugs:** Template variables can fail at runtime; no sanitization/escaping contract is documented.
- **Recommended improvements:** Typed variable definitions, renderer tests, version history, authorization.
- **Estimated effort:** M

## Programs

- **Status:** Partial
- **Missing features:** End-to-end tests, consistent scopes, richer SEO/accessibility states.
- **Bugs:** Legacy parallel controllers exist; direct builder preview queries; inconsistent media pathways.
- **Recommended improvements:** Choose canonical controller/service, add published scope, policy, pagination and frontend tests.
- **Estimated effort:** M

## Theme Settings

- **Status:** Partial
- **Missing features:** Singleton enforcement, revision history, preview/rollback, cache invalidation.
- **Bugs:** Ordinary CRUD permits multiple theme rows; upload hardening and authorization are absent.
- **Recommended improvements:** Singleton page/resource, validated color/token schema, media integration, cache strategy.
- **Estimated effort:** M

## Workout Plans

- **Status:** Partial
- **Missing features:** Policy-backed tier access, progress rules, secure streaming/storage, tests.
- **Bugs:** Raw rich HTML output; uploads lack complete size/content controls; relationships are under-modeled.
- **Recommended improvements:** Central access policy, sanitize trusted HTML, secure media pipeline, completion uniqueness constraint.
- **Estimated effort:** L

## Website Builder

- **Status:** Broken architecturally
- **Missing features:** Draft/version model, safe preview, rollback, granular permissions, concurrency control.
- **Bugs:** One oversized Livewire component spans many domains; direct model/storage operations and duplicated ordering logic create high regression risk.
- **Recommended improvements:** Decompose by bounded section after tests; move persistence into services; add revisions and authorization.
- **Estimated effort:** XL

## Media Categories

- **Status:** Partial
- **Missing features:** Hierarchy/rules, usage counts, safe deletion behavior, tests.
- **Bugs:** Limited filters/actions and no demonstrated authorization.
- **Recommended improvements:** Policy, relation/usage guards, slug constraints, test category deletion with attached media.
- **Estimated effort:** S

## Media Library

- **Status:** Broken for production security
- **Missing features:** Unified validation, quotas, malware/content checks, responsive variants, audit trail.
- **Bugs:** Public tooling routes; deny-all/misnamed policy ambiguity; upload service trusts client extension; image metadata call lacks defensive handling.
- **Recommended improvements:** Restrict routes, correct/register policy, enforce MIME/size/dimensions, queue optimization, test malicious files.
- **Estimated effort:** L

## Homepage Cards

- **Status:** Partial
- **Missing features:** Scheduling, revisions, cache invalidation, accessibility content controls.
- **Bugs:** Legacy file path and Media relationship approaches coexist; ordering logic is duplicated.
- **Recommended improvements:** Standardize Media usage, use shared ordering service, enforce active/order indexes, add preview tests.
- **Estimated effort:** M

## Testimonials

- **Status:** Partial
- **Missing features:** Consent/provenance, moderation, scheduling, accessibility review.
- **Bugs:** Builder preview queries database directly; no policy/tests.
- **Recommended improvements:** Published scope, consent metadata, pagination, policy and frontend state tests.
- **Estimated effort:** M

## Transformations

- **Status:** Partial
- **Missing features:** Consent/privacy controls, moderation, image lifecycle, structured outcomes.
- **Bugs:** Multiple photo/media representations and duplicate builder ordering logic; policy coverage absent.
- **Recommended improvements:** Define canonical media model, consent enforcement, secure image access, tests.
- **Estimated effort:** L

## Members

- **Status:** Partial
- **Missing features:** Central member domain/service, role lifecycle, privacy controls, export/deletion workflow.
- **Bugs:** Role state is split across `account_type`, `is_admin`, `is_coach`, premium and membership data; relationships are untyped and under-tested.
- **Recommended improvements:** Normalize roles, policies, profile state machine, privacy/audit events, comprehensive member tests.
- **Estimated effort:** L

## Coach Notes

- **Status:** Partial
- **Missing features:** Coach authorization, note visibility rules, audit/version history, attachments.
- **Bugs:** Only manual user scoping; model relationship typing/casts are incomplete; no policy/tests.
- **Recommended improvements:** Coach/member policies, immutable audit history, pagination, notification integration.
- **Estimated effort:** M

## Notifications

- **Status:** Partial
- **Missing features:** Channels/preferences, queued delivery, bulk read, retention, real-time behavior.
- **Bugs:** Duplicate unread-count queries existed; ownership is manual; indexes need review.
- **Recommended improvements:** Policy/scoped binding, user/read/date index, queue channels, pagination and tests.
- **Estimated effort:** M

## Memberships

- **Status:** Broken for reliable commerce
- **Missing features:** State machine, renewal/cancellation, payment linkage, history, reconciliation.
- **Bugs:** Activation is controller-driven without a transaction; role/access checks duplicate queries; foreign-key/relationship coverage is weak.
- **Recommended improvements:** Transactional membership service, immutable periods/events, constraints, policy and expiry jobs.
- **Estimated effort:** L

## Courier Providers

- **Status:** Broken for production security
- **Missing features:** Adapter contract, credential encryption, webhook verification, retry/failover.
- **Bugs:** Direct `env()` usage, synchronous network calls, plaintext credentials, unclear timeout/error policy.
- **Recommended improvements:** Dedicated config, encrypted provider settings, queued jobs, idempotency and integration tests.
- **Estimated effort:** L

## Diet Plans

- **Status:** Partial
- **Missing features:** Policy-backed access tiers, structured nutrition data, contraindication governance, tests.
- **Bugs:** Raw rich HTML output; access logic duplicated; uploads and relationships need hardening.
- **Recommended improvements:** Central access policy, sanitize content, secure Media usage, completion constraint and tests.
- **Estimated effort:** L

## Orders

- **Status:** Broken for production integrity
- **Missing features:** State machine, payment event ledger, refunds, idempotency, reconciliation, transactional fulfillment.
- **Bugs:** Product success path accepts unverified payment data; polymorphic items lack formal relation/integrity; model relationships/casts are incomplete.
- **Recommended improvements:** Immediately gate fulfillment, add verified payment service and transactions, formalize statuses/relations, add exhaustive tests.
- **Estimated effort:** XL

## Plans

- **Status:** Partial
- **Missing features:** Versioned pricing, currency/tax rules, archival semantics, entitlement definitions.
- **Bugs:** Membership activation depends on loosely constrained billing-cycle/duration values; no policy/tests.
- **Recommended improvements:** Typed enums/value objects, immutable sold-plan snapshot, validation, frontend/checkout tests.
- **Estimated effort:** M

## Products

- **Status:** Broken due to checkout vulnerability
- **Missing features:** Inventory, price snapshot, tax, refunds, verified fulfillment, stock concurrency.
- **Bugs:** Payment verification is disabled in product success flow; amount comes from current product without a trusted gateway order comparison.
- **Recommended improvements:** Disable unsafe fulfillment, implement server-created payment orders/signature verification/idempotency, inventory transaction and tests.
- **Estimated effort:** XL

## Shipments

- **Status:** Partial
- **Missing features:** State machine, webhook ingestion, retries, label workflow, cancellation/returns.
- **Bugs:** Courier dispatch is synchronous after order creation and not transactionally/outbox coordinated; relationship modeling is incomplete.
- **Recommended improvements:** Queue after commit, adapters, signed webhooks, event uniqueness, operational reconciliation.
- **Estimated effort:** L

## Services

- **Status:** Partial
- **Missing features:** Consistent publication scope, SEO/accessibility tests, scheduling.
- **Bugs:** Legacy parallel controller exists; file upload and Media approaches coexist; footer performs a query.
- **Recommended improvements:** Canonical controller/service, Media-only assets, cached navigation/footer data, policy/tests.
- **Estimated effort:** M

## Settings

- **Status:** Partial
- **Missing features:** Singleton guarantee, typed schema, cache invalidation, revisions, deployment-safe defaults.
- **Bugs:** Provider bootstrap previously required database state (fixed); many templates still assume a seeded row; resource authorization absent.
- **Recommended improvements:** Singleton settings page, defaults object, cached repository, revision/audit trail, seed/test contract.
- **Estimated effort:** M

## Transformation Photos

- **Status:** Partial
- **Missing features:** consent, retention, private storage/access, moderation, deletion workflow.
- **Bugs:** Sensitive body imagery uses ordinary public uploads; multiple fields lack explicit MIME/size controls; relationship/policy coverage is incomplete.
- **Recommended improvements:** Private disk and signed delivery, consent metadata, secure upload validation, policy and lifecycle tests.
- **Estimated effort:** L

## Action Plans

- **Status:** Partial
- **Missing features:** Assignment/audit history, due-date workflow, reminders, coach permissions.
- **Bugs:** Ownership is manually checked and no policy exists; relationship typing and status constraints are incomplete.
- **Recommended improvements:** Policy/scoped binding, state transitions, indexes, pagination and member/coach tests.
- **Estimated effort:** M

## Users

- **Status:** Partial
- **Missing features:** role/permission administration, account lifecycle, MFA, audit log, privacy workflows.
- **Bugs:** Filament and middleware use different admin flags; sensitive/privileged fields are mass assignable; no User policy.
- **Recommended improvements:** Normalize authorization model, protect privileged mutations, add policy/MFA/audit and role-escalation tests.
- **Estimated effort:** L

## Inquiries

- **Status:** Partial (implemented as Contact Leads)
- **Missing features:** spam protection, assignment SLA, consent/retention, duplicate detection, source attribution.
- **Bugs:** Two contact controller pathways create duplication; rate limiting and comprehensive request objects are absent.
- **Recommended improvements:** Canonical intake service/Form Request, throttling/CAPTCHA strategy, consent fields, CRM audit trail and tests.
- **Estimated effort:** M

## Blog CMS

- **Status:** Partial on `main`; separately audited work is not present on this branch
- **Missing features:** Reconcile and merge the dedicated Blog audit, then validate migrations, admin permissions, frontend tests, SEO and sanitization together.
- **Bugs:** Legacy `Blog` and newer `BlogPost` systems coexist; multiple controllers/resources represent overlapping concepts.
- **Recommended improvements:** Do not refactor in this phase. Merge the approved Blog branch, select one canonical domain model, migrate data safely, and remove legacy code only after regression tests.
- **Estimated effort:** L (reconciliation dependent)
