# Phase 3 Performance Audit — Batch 1

## Executive Summary

The audit found avoidable query multiplication in dashboard charts, relationship N+1 queries in Filament tables, repeated settings/theme reads on every request, non-sargable date predicates, and missing indexes for frequently executed dashboard and homepage queries.

This batch applies small, behavior-preserving fixes to the highest-confidence paths. It does not change UI, business rules, routes, or package versions.

The installed application stack is Laravel 12.61.0, Filament 3.3.52, and Livewire 3.8.0. The requested Filament v5 target does not match the repository; no upgrade was attempted.

## Findings Fixed

### Database Indexes

Sixteen indexes were added for predicates and ordering observed in application queries:

- Orders by payment status/date and user/date.
- Memberships by active status/expiry and creation date.
- Contact leads by status/date and creation date.
- Unread notifications by user.
- Visible coach notes by user/date.
- Action-plan completion state by user/date.
- Progress logs and weekly check-ins by user/date.
- Workout and diet completion lookups by user/plan.
- Shipment lookups by order and courier provider.
- Active homepage cards by display order.

The migration only adds indexes. It does not alter data, columns, constraints, or business behavior.

### Dashboard Widgets

| Area | Before | After |
| --- | ---: | ---: |
| Leads chart | 7 queries | 1 query |
| Membership chart | 6 queries | 1 query |
| Revenue chart | 6 queries | 1 query |
| Leads overview | 4 queries | 2 queries |
| Main stats overview | 10 queries | 6 queries |
| Activity feed memberships | Up to 6 queries | 2 queries |

Chart queries now retrieve the bounded reporting period once and group results in memory. This remains database-portable and avoids database-specific date formatting expressions.

### Eloquent and Filament N+1 Queries

Explicit eager loading was added for:

- Memberships with users and plans.
- Shipments with orders and courier providers.
- Communication logs with users and providers.
- Users with active memberships and membership plans.
- Activity-feed memberships with users.

### Settings and Theme Caching

Global settings and theme records are now cached using stable keys. Model `saved` and `deleted` events invalidate the corresponding cache immediately. The homepage reuses the same settings cache key, removing its duplicate settings query.

### Sargable Date Queries

Date-function predicates were replaced with date ranges in dashboard revenue, lead counts, and expiring memberships. This permits normal B-tree index range scans.

## Homepage Assessment

The homepage performs bounded queries for programs, products, services, transformations, testimonials, hero configuration, and cards. Batch 1 optimizes its duplicate settings lookup and card predicate index.

The content collections were not cached because a complete invalidation policy across all homepage-managed models is not currently present. Adding TTL-only caching would introduce visible admin-to-frontend staleness and was therefore deferred.

## View Composer Assessment

The global composer previously loaded settings and theme records once per request but still queried the database on every request. Cross-request caching now removes those repeated reads. The notification composer performs one indexed user/unread count and remains request-specific.

`Schema::hasTable()` safeguards remain in place because this application boots providers during fresh database setup and tests. Removing them could reintroduce the historical missing-table boot failure.

## Remaining Opportunities

These were audited but intentionally not changed in Batch 1:

1. Add eager loading to remaining relationship-based Filament tables: action plans, coach notes, notifications, transformation photos, media, products, testimonials, and transformations.
2. Consolidate the member dashboard's independent count/existence queries behind a dedicated read service.
3. Paginate currently unbounded frontend collections for programs, services, products, workout plans, and diet plans where dataset growth warrants it.
4. Reduce repeated full-collection reloads in the Livewire Website Builder and avoid loading the full media library for option lists.
5. Consider event-invalidated homepage fragment caching after all homepage content models share a consistent invalidation contract.
6. Add production query telemetry before introducing further indexes; indexes increase write cost and should be driven by real workload evidence.
7. Review remaining `whereDate()` usage in progress submission checks. Functional timezone semantics should be defined before replacing those predicates.

## Operational Risk

The index migration can hold metadata locks while indexes are built on large MySQL tables. Apply it during a normal deployment window and review table sizes first. For very large production tables, use the deployment platform's online-schema-change procedure.

