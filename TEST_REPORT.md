# Phase 6E Test Report

## Focused suites

| Suite | Tests | Assertions | Result |
|---|---:|---:|---|
| `LoginAppearanceTest` | 6 | 52 | PASS |
| `SharedFrontendThemeMigrationTest` | 7 | 57 | PASS |
| `PublicFrontendRegressionTest` | 17 | 182 | PASS |
| **Total** | **30** | **291** | **PASS** |

PHPUnit reports warnings from the checkout's missing Vite manifest/dependency setup; the focused tests call `withoutVite()` and all assertions pass.

## Coverage

- Public and admin login rendering
- POST action, CSRF, email, password, Remember Me, and forgot-password contract
- Native checkbox sizing and exclusion from text-control CSS
- Theme token, palette, card background, and focus-color propagation
- default, image, overlay, gradient, orb, and image-zoom configuration
- reduced-motion CSS
- Media ID rendering and validation
- real Livewire `EditThemeSetting` save, notification, JSON persistence, refresh, and cache invalidation
- unchanged successful customer authentication and redirect
- public routes, `/programs`, Theme tokens, Hero image source, and UTF-8/mojibake regression scan

## Verification

- Modified PHP syntax: PASS
- `php artisan view:cache`: PASS
- Laravel Pint on all modified PHP files: PASS
- `git diff --check`: PASS; only existing CRLF normalization notices
- Mojibake scan across `app`, `routes`, `resources`, and `database`: PASS
- Hero/public regression suite: PASS

## Known pre-existing failures

The legacy `tests/Feature/Auth/AuthenticationTest.php` is not Phase 6E-clean:

1. `test_login_screen_can_be_rendered` does not call `withoutVite()` and this worktree has no `public/build/manifest.json` because its Node dependencies do not provide a runnable `vite` binary.
2. `test_users_can_authenticate_using_the_login_screen` expects named route `dashboard`, which is absent. The existing controller correctly redirects non-admin users to `/member/dashboard`.

Phase 6E includes a passing authentication behavior test matching the unchanged controller contract. These pre-existing test/environment defects were not altered because the phase explicitly prohibits route and authentication behavior changes.
