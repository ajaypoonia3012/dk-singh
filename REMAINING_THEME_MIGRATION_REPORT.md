# Remaining Public Theme Migration Report

## Executive summary

Phase 6B correctly themes the active homepage, but the rest of the public/member frontend still contains a large independent styling surface. This audit inspected 79 public-facing Blade candidates across public marketing, commerce, authentication, account, member, premium-content, and shared-component paths. Seventy-five contain direct fixed presentation values; several additional Blog and authentication views inherit fixed styling from shared CSS/components.

This report is analysis only for those remaining pages. No listed page was migrated in this release.

## Hero image regression

### Root cause

The platform currently has a deliberate fallback chain, not a single exclusive owner:

1. Media Library selection is stored in `hero_settings.background_media_id` and exposed by `HeroSetting::backgroundMedia()`.
2. Direct Hero upload is stored in `settings.hero_image`. `WebsiteBuilder::saveHero()` explicitly writes this field.
3. Legacy `hero_settings.background` can remain when the media-relation migration cannot find a matching Media row.
4. The packaged static image is the final fallback.

`HomeController` already eager-loads `HeroSetting::backgroundMedia` and provides cached `Setting`; no controller or service change was required. The regression was in `resources/views/home/sections/hero.blade.php`: it checked only `HeroSetting.backgroundMedia`, then immediately used the static image, skipping `Setting.hero_image` and the legacy HeroSetting path.

### Restored precedence

```text
HeroSetting.backgroundMedia.url
    -> Setting.hero_image on the public storage disk
    -> HeroSetting.background on the public storage disk
    -> public/images/dk-hero.jpeg
```

Ownership, persistence, services, schema, and data were not changed.

## Recommended replacement vocabulary

| Existing pattern | Theme replacement |
|---|---|
| `bg-yellow-*`, `text-yellow-*`, amber brand classes | `theme-text-primary`, `theme-surface-*`, `theme-button-primary`, `theme-badge` |
| `bg-white`, fixed off-white hex surfaces | `theme-card`, `theme-surface-default`, `theme-surface-muted` |
| `bg-black`, dark fixed surfaces | `theme-surface-strong`, `theme-dark-surface` |
| `text-black`, gray/zinc text | `theme-text-secondary`, `theme-text-neutral`, `theme-section-subtitle` |
| Green/red/blue status colors | `theme-status-success`, `theme-status-danger`, `theme-status-info`, semantic text classes |
| Fixed buttons | `x-theme.button` with primary, secondary, or outline variant |
| Fixed cards | `x-theme.card`, `theme-card-padding*` |
| Fixed inputs and labels | `x-theme.input`, `textarea`, `select`, `label` |
| Fixed containers/section padding | `x-theme.page-container`, `x-theme.section` |
| Fixed headings/body copy | `x-theme.section-heading`, `x-theme.section-subtitle`, Theme typography classes |
| Fixed radius/border/shadow | `theme-radius`, `theme-border`, `theme-shadow` or the card/form primitives |
| Fixed gaps/stacks | `theme-grid-gap`, `theme-content-gap`, `theme-stack-*` |

## Public marketing and catalogue pages

| File | Hardcoded classes/values | Theme primitive replacement | Risk | Effort |
|---|---|---|---|---|
| `resources/views/about/index.blade.php` | Gold/black/white/gray colors, `#111111`, `#f6f3eb`, fixed containers, `py-24`, 2xl/3xl/40px radii, large shadows | Theme sections, containers, headings/subtitles, buttons, cards, media, surfaces | Medium | Medium |
| `resources/views/contact/index.blade.php` | Gold/off-white/black/gray colors, fixed form borders/focus rings, containers, spacing, radius, shadows | Same contact primitives used by homepage; Theme status/form/button/card stack | Medium | Medium |
| `resources/views/plans/index.blade.php` | Extensive gold/black/white/gray palette, fixed pricing cards, borders, CTA buttons, spacing, radii, shadows | Theme sections/cards/badges/buttons; semantic featured-plan surface | High | High |
| `resources/views/products/index.blade.php` | Gold/off-white/white/black/gray, fixed 35px cards, containers, spacing, shadows | Theme section/header/card/button/container primitives | Medium | Medium |
| `resources/views/products/show.blade.php` | Black CTA, yellow price, gray copy, fixed container/button radius/shadow | Theme page container, headings/subtitles, primary CTA, media/card primitives | Medium | Medium |
| `resources/views/programs/index.blade.php` | Gold/off-white/white/black/gray, fixed cards, container, spacing, radius, shadows | Theme section/header/card/badge/button primitives | Medium | Medium |
| `resources/views/programs/show.blade.php` | Extensive gold/black/white/off-white palette, chips, pricing/CTA surfaces, fixed 40px radius and shadows | Theme section/card/badge/button/media and semantic surface primitives | High | High |
| `resources/views/programs.blade.php` | Legacy off-white/gold/white/black/gray implementation, fixed cards and buttons | Same program index primitives; first confirm whether route is still active | Medium | Low |
| `resources/views/services/index.blade.php` | Gold/off-white/white/black/gray, fixed cards, spacing, radius, shadows | Theme section/header/card/button primitives | Medium | Medium |
| `resources/views/services/show.blade.php` | Extensive gold/black/white/off-white palette, fixed feature/CTA surfaces, radii and shadows | Theme detail-page composition with cards, badges, buttons and semantic surfaces | High | High |
| `resources/views/transformations/index.blade.php` | Gold/off-white/white/gray/black, fixed badges/cards/containers/radius/shadows | Theme section/card/badge/media/typography primitives | Medium | Medium |
| `resources/views/transformations/show.blade.php` | Black/gold/white/gray detail surface, fixed buttons, radius and shadow | Theme strong surface, page container, card/media and CTA primitives | Medium | Medium |
| `resources/views/diet-plans/index.blade.php` | Fixed off-white, white, gold, gray/black, cards/badges/containers/shadows | Theme sections/cards/badges/containers | Medium | Medium |
| `resources/views/diet-plans/show.blade.php` | Gold/green/white/off-white status and CTA colors, fixed cards/forms/radii/shadows | Theme card/form/button plus success and warning semantic tokens | High | Medium |
| `resources/views/workout-plans/index.blade.php` | Gold/off-white/white/black/gray, fixed cards, badges, container and shadows | Theme section/card/badge/button primitives | Medium | Medium |
| `resources/views/workout-plans/show.blade.php` | Same fixed detail system as Services: gold/black/white/off-white, feature cards, buttons, radii, shadows | Shared themed detail-page primitives | High | High |

## Fitness Hub and premium content

| File | Hardcoded classes/values | Theme primitive replacement | Risk | Effort |
|---|---|---|---|---|
| `resources/views/fitness-hub/index.blade.php` | Gold/black/white/off-white/gray, fixed cards, borders, CTAs, 40px radius and shadows | Theme sections/cards/buttons/badges/surfaces | Medium | Medium |
| `resources/views/fitness-hub/diets/index.blade.php` | White/off-white/black/gray/gold, fixed cards/containers/shadows | Theme section/card/container primitives | Medium | Medium |
| `resources/views/fitness-hub/diets/show.blade.php` | Black/white/gold/off-white, fixed tags/buttons/cards/radii | Theme strong/default surfaces, badges, cards, buttons | Medium | Medium |
| `resources/views/fitness-hub/workouts/index.blade.php` | White/off-white/black/gray/gold, fixed cards/containers/shadows | Theme section/card/container primitives | Medium | Medium |
| `resources/views/fitness-hub/workouts/show.blade.php` | Black/white/gold/off-white, fixed tags/buttons/cards/radii | Theme strong/default surfaces, badges, cards, buttons | Medium | Medium |
| `resources/views/premium/diets.blade.php` | Gold/off-white/black/gray, fixed container/button/radius | Theme section, page container, button and typography primitives | Low | Low |
| `resources/views/premium/diet-plans.blade.php` | Bootstrap spacing/shadow plus fixed white text and contextual colors | Theme cards/status/button wrappers around existing data | Medium | Low |
| `resources/views/premium/workouts.blade.php` | Bootstrap spacing/shadow plus fixed white text and contextual colors | Theme cards/status/button wrappers around existing data | Medium | Low |

## Commerce and account pages

| File | Hardcoded classes/values | Theme primitive replacement | Risk | Effort |
|---|---|---|---|---|
| `resources/views/checkout/index.blade.php` | Extensive gold/black/white/off-white/gray palette, fixed checkout form, cards, spacing, radius, shadows | Theme form/card/button/section primitives; preserve payment behavior | High | High |
| `resources/views/checkout/product.blade.php` | Black/white/gold/gray, fixed container/card/button/radius/shadow | Theme checkout card/form/button primitives | High | Medium |
| `resources/views/checkout/product-payment.blade.php` | Gold/white/off-white, fixed payment card/alerts/button/radius/shadow | Theme card/button/status primitives; preserve gateway hooks | High | Medium |
| `resources/views/account/orders.blade.php` | White cards, blue links, fixed container/radius/shadow | Theme page container/card/link/status primitives | Low | Low |
| `resources/views/account/order-show.blade.php` | Gold/white/blue/gray, fixed status borders/cards/buttons/radii/shadows | Theme cards, semantic statuses, links, outline/primary buttons | Medium | Medium |

## Authentication, profile and shared Breeze components

| File | Hardcoded classes/values | Theme primitive replacement | Risk | Effort |
|---|---|---|---|---|
| `resources/views/layouts/guest.blade.php` | Gray/white shell, fixed max width, radius, shadow, Figtree styling | Theme token emitter, page/card container and Theme typography | High | Medium |
| `resources/views/layouts/navigation.blade.php` | Gray/white/indigo navigation system, fixed container and dropdown spacing | Theme Navbar/link/button/dropdown primitives | High | High |
| `resources/views/auth/login.blade.php` | Gray/indigo text, borders, focus ring, radius/shadow | Theme form controls, labels, links, button | Low | Low |
| `resources/views/auth/register.blade.php` | Gray/indigo text/focus and fixed radius | Theme form controls, labels, links, button | Low | Low |
| `resources/views/auth/confirm-password.blade.php` | Fixed gray explanatory text; inherits fixed shared inputs/buttons | Theme subtitle plus shared Theme form components | Low | Low |
| `resources/views/auth/forgot-password.blade.php` | Fixed gray explanatory text; inherits fixed shared inputs/buttons | Theme subtitle plus shared Theme form components | Low | Low |
| `resources/views/auth/reset-password.blade.php` | No direct palette literal, but inherits fixed shared labels/inputs/buttons | Replace dependencies with Theme form primitives | Low | Low |
| `resources/views/auth/verify-email.blade.php` | Gray/green/indigo status and controls | Theme subtitle, success status, primary/secondary buttons | Low | Low |
| `resources/views/profile/edit.blade.php` | Gold/black/white/off-white/gray, fixed profile cards/forms/buttons/radius/shadow | Theme section/card/form/button/status primitives | Medium | High |
| `resources/views/profile/partials/update-profile-information-form.blade.php` | Gray/indigo/green form and success states | Theme inputs/labels/buttons/success status | Low | Low |
| `resources/views/profile/partials/update-password-form.blade.php` | Gray text and fixed form spacing through shared components | Theme inputs/labels/buttons and stacks | Low | Low |
| `resources/views/profile/partials/delete-user-form.blade.php` | Gray text and fixed destructive modal form | Theme form primitives and danger status/button | Medium | Low |
| `resources/views/components/primary-button.blade.php` | Gray/indigo fixed button | Delegate to `x-theme.button` primary | Medium | Low |
| `resources/views/components/secondary-button.blade.php` | White/gray/indigo fixed button | Delegate to `x-theme.button` secondary/outline | Medium | Low |
| `resources/views/components/danger-button.blade.php` | Red fixed button and ring | Theme danger semantic button variant or composed semantic class | Medium | Low |
| `resources/views/components/text-input.blade.php` | Gray/indigo border, ring, radius, shadow | Delegate to `x-theme.input` | Medium | Low |
| `resources/views/components/input-label.blade.php` | Fixed gray label | Delegate to `x-theme.label` | Low | Low |
| `resources/views/components/input-error.blade.php` | Fixed red error text | Theme danger semantic text | Low | Low |
| `resources/views/components/auth-session-status.blade.php` | Fixed green status | Theme success semantic text/status | Low | Low |
| `resources/views/components/nav-link.blade.php` | Gray/indigo active and hover states | Theme Navbar link and primary active token | Medium | Low |
| `resources/views/components/responsive-nav-link.blade.php` | Gray/indigo mobile states | Theme Navbar mobile/link/status primitives | Medium | Low |
| `resources/views/components/dropdown.blade.php` | White/black ring, fixed radius/shadow | Theme card/surface/border/shadow | Medium | Low |
| `resources/views/components/dropdown-link.blade.php` | Gray text/background | Theme neutral text and muted surface | Low | Low |
| `resources/views/components/modal.blade.php` | Gray overlay, white panel, fixed width/radius/shadow | Tokenized overlay plus Theme card; retain size API | Medium | Medium |

## Member dashboard and member tools

| File | Hardcoded classes/values | Theme primitive replacement | Risk | Effort |
|---|---|---|---|---|
| `resources/views/dashboard.blade.php` | Large zinc/amber/emerald/blue/rose system; dozens of cards, statuses, links, progress bars, radii and shadows | Theme dashboard card/status/link/button primitives; map all semantic states deliberately | High | High |
| `resources/views/dashboard/index.blade.php` | Black/white/gray/gold summary cards and CTA surfaces | Theme sections/cards/status/button primitives | Medium | Medium |
| `resources/views/dashboard/purchases.blade.php` | Off-white/black/white/gray/green purchase cards | Theme container/card/strong surface/status primitives | Medium | Medium |
| `resources/views/member/action-plan/index.blade.php` | Off-white/white/gold/green statuses, fixed card/badge/radius/shadow | Theme cards/badges/success status/container | Medium | Medium |
| `resources/views/member/billing.blade.php` | White cards, fixed container/radius/shadow | Theme page container/cards | Low | Low |
| `resources/views/member/check-ins/index.blade.php` | Off-white/white/gold form/buttons/cards/radius/shadow | Theme forms/cards/buttons/sections | Medium | Medium |
| `resources/views/member/coach-notes/index.blade.php` | Off-white/white/gray, fixed cards/radius/shadow | Theme section/cards/subtitles | Low | Low |
| `resources/views/member/my-plan.blade.php` | Black/white/gray/gold/green, fixed status chips/cards/buttons | Theme strong/card/button/badge/status primitives | High | Medium |
| `resources/views/member/my-plan-backup.blade.php` | Black/white/green backup implementation, fixed cards/chips | Confirm dead/backup status; theme only if reachable | Low | Low |
| `resources/views/member/notifications/index.blade.php` | Off-white/black/white/gray/gold notification states | Theme sections/cards/badges/status links | Medium | Medium |
| `resources/views/member/profile.blade.php` | Black/white/gray/green profile cards and buttons | Theme cards/forms/buttons/success status | Medium | Medium |
| `resources/views/member/progress/create.blade.php` | Black/white fixed form card/buttons | Theme form/card/button primitives | Medium | Low |
| `resources/views/member/progress/index.blade.php` | White/gray/black plus green/red statuses and controls | Theme cards/forms/buttons/success/danger statuses | High | Medium |
| `resources/views/member/transformations/index.blade.php` | Off-white/white/yellow/blue/green/gray, fixed upload/cards/alerts | Theme cards/forms/buttons/statuses/media | High | Medium |
| `resources/views/member/reports/progress-report.blade.php` | Inline `#222`, `#d4a017`, `#ddd`, `#f7f7f7` report CSS | Separate print-safe Theme token mapping with deterministic fallbacks | High | Medium |

## Blog frontend

Blog was audited but must be migrated as its own isolated phase because it intentionally has a separate Bootstrap-oriented component system and `resources/css/blog.css` token family.

| File | Hardcoded classes/values | Theme primitive replacement | Risk | Effort |
|---|---|---|---|---|
| `resources/views/blog/index.blade.php` | Bootstrap containers/grid/spacing and `text-muted`; delegates visual styling to `blog.css` | Retain grid if desired; map Blog components to canonical Theme tokens | High | Medium |
| `resources/views/blog/show.blade.php` | Bootstrap warning/light badges, dark/muted text, fixed radius and spacing | Theme badge/card/typography/link primitives | High | Medium |
| `resources/views/blog/category.blade.php` | Bootstrap container/grid/lead/muted styling | Theme page/section/header and card primitives | Medium | Low |
| `resources/views/blog/tag.blade.php` | Bootstrap container/grid/lead/muted styling | Theme page/section/header and card primitives | Medium | Low |
| `resources/views/blog/partials/hero.blade.php` | Warning badge/button, white text, inline max width | Theme section/badge/search form/button/container | High | Medium |
| `resources/views/blog/partials/card.blade.php` | Danger badge and custom `blog-*` card classes | Theme card/badge/button/typography primitives | High | Medium |
| `resources/views/blog/partials/sidebar.blade.php` | Warning badges, dark/muted text, custom widget/button/category classes, inline sticky offset | Theme cards/forms/badges/links and tokenized sticky layout | High | Medium |
| `resources/views/blog/partials/newsletter.blade.php` | Warning/white text, custom newsletter/button and Bootstrap form classes | Theme strong section/form/button/status primitives | High | Medium |
| `resources/views/blog/partials/related-posts.blade.php` | Bootstrap grid/spacing and inherited custom cards | Theme section/header and shared themed Blog cards | Medium | Low |
| `resources/css/blog.css` | Independent `--dk-*` colors/radius/shadow/transition plus fixed hex, rgba, padding and radius values | Alias/remove `--dk-*` in favor of existing Theme CSS variables after visual regression coverage | High | High |

## Deferred or non-page surfaces

The audit intentionally did not classify Filament views, Website Builder/preview views, Media Library administrative views, email templates, PDF invoice templates, sitemap XML, developer test pages, or inactive `home/sections/*` legacy partials as remaining public-page migration work. They require separate rendering contexts and were excluded by the user’s architecture constraints. `resources/views/admin/settings/edit.blade.php` is also excluded as an administrative legacy view.

## Prioritized future sequence

1. **Shared layouts and Breeze components** — unlock consistent authentication, profile, dropdown, modal, and navigation behavior.
2. **Catalogue index/detail families** — Programs, Products, Services, Transformations, Diet and Workout pages can share repeatable themed compositions.
3. **Checkout and Plans** — high-risk because CTA/payment states must retain behavior and conversion layout.
4. **Member dashboard and tools** — highest surface area and semantic-status complexity.
5. **Blog** — isolated migration that reconciles Bootstrap and `blog.css` without changing Blog architecture.

Estimated total implementation effort: **High**, best delivered in several independently reversible batches with route-level palette tests and browser visual baselines for each family.
