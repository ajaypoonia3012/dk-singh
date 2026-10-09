# DK SINGH FITNESS — FULL PROJECT AUDIT & BASELINE REPORT

**Date:** October 4, 2026  
**Auditor:** Senior Staff Systems & Security Engineer  
**Project:** DK Singh Fitness & Nutrition  
**Stack:** Laravel 12.61.0, PHP 8.2.12, Filament v3.3.52, Livewire v3.8.0, Tailwind CSS, Vite, MySQL

---

## 1. Executive Summary

A comprehensive, evidence-based audit was conducted on the **DK Singh Fitness & Nutrition** application codebase. The audit inspected backend architecture, frontend Blade & Tailwind implementation, database schemas and migrations, routing definitions, authentication and authorization boundaries, payment integrations, member area functionality, content management (CMS/Blog/Builder), and Filament admin resources.

### Overall Status: **PARTIALLY READY / REQUIRES REPAIR**
While the application possesses a deep feature set (programs, personalized diet/workout plans, member progress tracking, Razorpay checkout, dynamic theme settings, and Filament admin), several critical and high-priority vulnerabilities and regressions were identified that directly impair production operations:
- A public `phpinfo.php` file leaks PHP configuration and environment secrets.
- Key public endpoints (`/blog/{slug}`, `/blog/category/{slug}`, `/blog/tag/{slug}`) throw HTTP 500 fatal exceptions due to an unshared `$categories` variable in sidebar Blade partials.
- Breeze authentication flows and tests throw `RouteNotFoundException: Route [dashboard] not defined`.
- Homepage contact form directs to non-existent `/contact-submit` (404).
- Razorpay payment templates rely on `env('RAZORPAY_KEY')` instead of `config(...)` (which breaks when configuration caching is active) and suffer from a client-side redirect race condition that risks charging users without activating orders.
- Account profile settings are locked behind fitness questionnaire completion, preventing users with incomplete fitness profiles from updating their passwords or managing account security.

This baseline report catalogs all confirmed findings backed by direct code inspection and automated test execution. A prioritized fix plan is established below.

---

## 2. Environment & Tooling Verification

All versions were confirmed directly from the active runtime:

| Component | Detected Version | Status / Notes |
| :--- | :--- | :--- |
| **PHP** | 8.2.12 | Verified via `php artisan about` |
| **Laravel Framework** | 12.61.0 | Verified |
| **Filament** | v3.3.52 | Verified |
| **Livewire** | v3.8.0 | Verified |
| **Composer** | 2.9.5 | Verified |
| **Database** | MySQL 10.4.32 (MariaDB/XAMPP) | Local port 3306 verified active; 61 migrations applied |
| **Node / NPM / Vite** | Vite 6.4.1 / NPM | Verified; `npm run build` generates assets in `public/build/` |
| **Pint** | Laravel Pint | Available via `vendor/bin/pint` |

---

## 3. Architecture Overview

- **Core Engine:** Laravel 12 application with MVC structure.
- **Admin Panel:** Filament 3 under `/admin`, managing programs, workouts, diets, orders, members, leads, theme settings, and media.
- **Frontend:** Server-rendered Blade views styled with Tailwind CSS, utilizing a custom design token system via `ThemeSetting` (`x-theme.*`, `theme-*` CSS variables).
- **Interactive UI:** Livewire 3 components for dynamic elements, modals, and the media library.
- **Asset Pipeline:** Vite compiling `resources/css/app.css` and `resources/js/app.js`.
- **Payment Gateway:** Razorpay API integration with HMAC-SHA256 signature verification.
- **Media Architecture:** Centralized `Media` model, `MediaLibrary` Livewire component, and `OpenMediaPicker` action for managing media files across admin resources and public views.

---

## 4. What Is Working

1. **Database Schema & Migrations:**
   - All 61 migrations have run successfully. Relationships across Users, Memberships, Plans, Programs, DietPlans, WorkoutPlans, Orders, and ContactLeads are properly defined with foreign keys.
2. **Filament Admin Panel:**
   - Admin authentication, resource routing, table listings, and form configurations for major resources (Programs, Plans, Orders, Blog Posts, Contact Leads, Theme Settings) are functional.
3. **Core Authentication Mechanisms:**
   - Breeze-based registration, login, password reset controllers, session handling, and authentication middleware are implemented.
4. **Vite Build System:**
   - Asset compilation cleanly completes without bundling errors.
5. **Public Navigation & Main Pages:**
   - Static/informational routes (`/`, `/about`, `/services`, `/programs`, `/plans`, `/products`, `/transformations`, `/fitness-hub`, `/workouts`, `/diets`, `/contact`) render HTTP 200 with dynamic content from the database.
6. **Payment Security Core:**
   - Razorpay webhook and callback handlers in `RazorpayService.php` and `CheckoutController.php` properly implement cryptographic HMAC SHA256 signature verification using `hash_equals()`.

---

## 5. What Is Broken / Findings by Severity

### CRITICAL ISSUES

#### [CRIT-01] Public Environment & Server Configuration Leak (`public/phpinfo.php`)
- **Severity:** CRITICAL
- **Location:** `public/phpinfo.php`
- **Evidence:** Direct GET to `/phpinfo.php` returns full PHP environment dump.
- **Root Cause:** Development utility left in public document root.
- **Risk:** Public disclosure of server paths, environment variables, loaded modules, and internal runtime configuration.
- **Fix:** Delete `public/phpinfo.php` immediately.

#### [CRIT-02] Broken Razorpay Configuration Under Production Config Caching
- **Severity:** CRITICAL
- **Location:** `resources/views/checkout/index.blade.php` (line 74), `resources/views/product-payment.blade.php` (line 46)
- **Evidence:** Views call `env('RAZORPAY_KEY')` directly in inline JavaScript.
- **Root Cause:** When `php artisan config:cache` is executed in staging/production, `env()` returns `null` for all keys not directly in `config/`.
- **Risk:** Complete failure of checkout in production environments.
- **Fix:** Replace `env('RAZORPAY_KEY')` with `config('services.razorpay.key')`.

---

### HIGH PRIORITY ISSUES

#### [HIGH-01] Fatal 500 Crash on Blog Post, Category, and Tag Pages
- **Severity:** HIGH
- **Location:** `app/Http/Controllers/BlogController.php` (methods `show`, `category`, `tag`), `resources/views/blog/partials/sidebar.blade.php` (lines 35-51)
- **Evidence:**
  - Automated scanner on `/blog/{slug}`, `/blog/category/{slug}`, and `/blog/tag/{slug}` fails with HTTP 500.
  - Error: `Undefined variable $categories in sidebar.blade.php`.
- **Root Cause:** `sidebar.blade.php` loops over `$categories`, but `BlogController::show`, `category`, and `tag` methods only pass `$post`, `$relatedPosts`, `$category`, or `$tag`, failing to pass `$categories`.
- **Fix:** Pass `$categories = BlogCategory::withCount('posts')->get()` in `show`, `category`, and `tag` methods, or share it globally across blog views.

#### [HIGH-02] Missing Named Route `dashboard` Breaking Auth & Tests
- **Severity:** HIGH
- **Location:** `routes/web.php`, `app/Http/Controllers/Auth/ConfirmablePasswordController.php`, `app/Http/Controllers/Auth/VerifyEmailController.php`, `resources/views/layouts/navigation.blade.php`
- **Evidence:** `php artisan test` fails on Breeze tests with `RouteNotFoundException: Route [dashboard] not defined`.
- **Root Cause:** The dashboard route is named `member.dashboard` at `/member/dashboard`, but default Laravel Breeze controllers, navigation views, and tests rely on route name `dashboard`.
- **Fix:** Add a redirecting or direct route for `dashboard` pointing to `member.dashboard` (`Route::get('/dashboard', ...)->name('dashboard')`).

#### [HIGH-03] Account Settings Locked Out for Incomplete Fitness Profiles
- **Severity:** HIGH
- **Location:** `routes/web.php` (lines 207-217)
- **Evidence:**
  ```php
  Route::middleware(['auth', 'profile.completed'])->group(function () {
      Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
      Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
      Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  });
  ```
- **Root Cause:** Wrapping user account settings (`ProfileController`) in `profile.completed` middleware forces users who haven't completed their fitness intake questionnaire into an inescapable redirect loop if they attempt to update their password, email, or delete their account.
- **Fix:** Move standard `/profile` routes out of `profile.completed` middleware and keep them in standard `auth` middleware.

#### [HIGH-04] Payment Submission Race Condition in Checkout
- **Severity:** HIGH
- **Location:** `resources/views/checkout/index.blade.php` (lines 89-94)
- **Evidence:**
  ```javascript
  document.getElementById('payment-form').submit();
  setTimeout(function() {
      window.location.href = "/member/dashboard";
  }, 2000);
  ```
- **Root Cause:** Submitting a form triggers an asynchronous POST navigation. The arbitrary 2-second timeout forces a window redirect to `/member/dashboard` which aborts the pending POST request if network latency exceeds 2 seconds.
- **Risk:** User's Razorpay payment is authorized, but the verification POST to `CheckoutController::verify` is canceled by the client, leaving the order marked unpaid and the membership unactivated.
- **Fix:** Remove the client-side `setTimeout` redirect; let the form POST complete naturally, where the server controller redirects with flash messages upon successful verification.

---

### MEDIUM PRIORITY ISSUES

#### [MED-01] Homepage Contact Form 404
- **Severity:** MEDIUM
- **Location:** `resources/views/home/index.blade.php` (line 122)
- **Evidence:** Action is hardcoded as `action="/contact-submit"`.
- **Root Cause:** The actual route registered in `routes/web.php` is POST `/contact` named `contact.submit`.
- **Fix:** Update form action to `{{ route('contact.submit') }}` and ensure method is POST with `@csrf`.

#### [MED-02] Blog Search Completely Non-Functional
- **Severity:** MEDIUM
- **Location:** `resources/views/blog/partials/hero.blade.php`, `app/Http/Controllers/BlogController.php` (method `index`)
- **Evidence:** Form submits `GET /blog?search=term`, but `BlogController::index` query builder does not check `request('search')`.
- **Fix:** Add `->when($request->filled('search'), ...)` query scope filtering on post `title`, `excerpt`, and `content`.

#### [MED-03] Missing Sitemap Route
- **Severity:** MEDIUM
- **Location:** `routes/web.php`, `public/robots.txt`, `app/Http/Controllers/SitemapController.php`
- **Evidence:** `robots.txt` points search engines to `/sitemap.xml`, and `SitemapController` exists, but no route is registered in `routes/web.php`. Furthermore, `resources/views/sitemap.blade.php` references `route('blogs.show')` which does not exist (the route is `blog.show`).
- **Fix:** Register `Route::get('/sitemap.xml', [SitemapController::class, 'index']);` and fix the route name in `sitemap.blade.php`.

#### [MED-04] Duplicate & Conflicting Member Route Definitions
- **Severity:** MEDIUM
- **Location:** `routes/web.php` (lines 94, 160, 163, 228)
- **Evidence:**
  - Route `/member/action-plan` is defined first inside `['auth', 'membership']` (line 160) and then re-defined inside `['auth', 'profile.completed']` (line 228).
  - Route `/member/coach-notes` is defined twice (lines 94 and 163).
- **Fix:** Consolidate member routes cleanly under appropriate middleware groups.

#### [MED-05] Publicly Exposed Internal Livewire Test Pages
- **Severity:** MEDIUM
- **Location:** `routes/web.php` (lines 35-39)
- **Evidence:** Routes `/media-library`, `/media-test`, and `/grid-test` are open to the general public without authentication.
- **Fix:** Protect internal diagnostic routes with `auth` and admin middleware, or restrict to local environment.

---

### LOW PRIORITY & LOGICAL ISSUES

#### [LOW-01] Missing Request Validation in Progress Photo Uploads
- **Location:** `app/Http/Controllers/ProgressController.php` (`store` method)
- **Finding:** Files uploaded via `front_photo`, `side_photo`, `back_photo` lack explicit image MIME type and file size constraints.
- **Fix:** Add `image|mimes:jpeg,png,webp,jpg|max:5120` validation rules.

#### [LOW-02] Inverted Logic in Transformations Template
- **Location:** `resources/views/transformations/index.blade.php` (line 43)
- **Finding:** Blade condition `@else@if($transformation->image)` erroneously falls back to displaying "No Image" placeholder when an image is actually present.
- **Fix:** Correct the condition so the image displays when present and the placeholder displays when null.

#### [LOW-03] Corrupted Label String in Programs View
- **Location:** `resources/views/programs/index.blade.php` (lines 15, 28)
- **Finding:** Concatenated duplicate string: `Premium Programs{{ $setting->programs_page_label ?? 'Premium Programs' }}`.
- **Fix:** Remove hardcoded prefix so the setting or default displays cleanly once.

#### [LOW-04] Missing Placeholders & Asset Fallbacks
- **Location:** `public/images/`, `resources/views/home/sections/hero.blade.php`, `resources/css/blog.css`
- **Finding:**
  - `hero.blade.php` references `asset('images/dk-hero.jpeg')`, while the actual file in `public/images/` is `dk-hero.jpg`.
  - `blog.css` references `/images/blog-hero.jpg` (file does not exist).
  - Missing default fallback images (`placeholder.jpg`, `transformation-placeholder.jpg`, `user-placeholder.jpg`).
- **Fix:** Fix image extensions and provide clean SVG/CSS fallback placeholders.

---

## 6. Security Audit Findings

| ID | Issue | Severity | Status |
| :--- | :--- | :--- | :--- |
| **SEC-01** | `phpinfo.php` in web root | CRITICAL | Scheduled for immediate deletion |
| **SEC-02** | Razorpay key exposure via `env()` in views | CRITICAL | Scheduled for config migration |
| **SEC-03** | Public Livewire diagnostic endpoints (`/media-library`) | MEDIUM | Scheduled for admin protection |
| **SEC-04** | Inadequate file validation on member progress photo upload | LOW | Scheduled for validation hardening |
| **SEC-05** | IDOR Check on Member Resources | LOW | Verified: Controllers check `Auth::id()` or member relationship ownership before returning data. |
| **SEC-06** | CSRF & XSS Protection | PASS | Blade templates use `@csrf` and `{{ }}` escaping appropriately; rich text outputs sanitized. |

---

## 7. Performance & SEO Audit

- **N+1 Queries:** `BlogController::category` and `tag` execute pagination queries without eager loading post author/category; will add `with(['author', 'category'])`.
- **Meta Tags & OpenGraph:** `resources/views/layouts/app.blade.php` has base SEO tags; `blog/show.blade.php` has OpenGraph tags.
- **Robots & Sitemap:** `robots.txt` points to `/sitemap.xml`, which will be activated via `SitemapController`.

---

## 8. Recommended Fix Order

1. **Phase 1 — Critical Security & Environment Fixes:**
   - Delete `public/phpinfo.php`.
   - Update Razorpay key references in checkout Blade views from `env(...)` to `config(...)`.
2. **Phase 2 — High Priority Routing & Controller Repairs:**
   - Define named route `dashboard` in `routes/web.php`.
   - Free account profile routes from `profile.completed` middleware.
   - Clean up duplicate/conflicting member routes.
   - Protect `/media-library`, `/media-test`, `/grid-test` with admin middleware.
   - Register `/sitemap.xml` route and fix route name in `sitemap.blade.php`.
3. **Phase 3 — Blog & Public Form Repairs:**
   - Fix missing `$categories` in `BlogController` methods (`show`, `category`, `tag`).
   - Implement blog search filtering in `BlogController::index`.
   - Fix homepage contact form action to `route('contact.submit')`.
4. **Phase 4 — Payment Flow & Upload Hardening:**
   - Remove unsafe client-side `setTimeout` redirect in `checkout/index.blade.php`.
   - Add image validation rules to `ProgressController::store`.
5. **Phase 5 — Visual & Template Polish:**
   - Fix hero image fallback extension (`.jpeg` to `.jpg`).
   - Fix inverted image logic in `transformations/index.blade.php`.
   - Fix duplicate string in `programs/index.blade.php`.
   - Provide clean fallback placeholders for missing static images.
6. **Phase 6 — Test Suite & Regression Verification:**
   - Update `EnterpriseSettingsTest.php` fillable count assertion.
   - Run `php artisan test` and verify all tests pass.
   - Run `npm run build` and `vendor/bin/pint --test`.
