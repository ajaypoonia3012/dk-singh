# DK Singh Fitness — Final Hostinger Production Readiness Audit

**Report Date:** October 10, 2026
**Audited Target:** DK Singh Fitness & Nutrition (`dksinghfitness.com`)
**Application Stack:** Laravel 12.0 / PHP 8.2+ / MySQL 8.0+ / Filament v3.3.52 / Vite (Tailwind CSS) / Razorpay
**Audit Scope:** Comprehensive, Read-Only Pre-Deployment Readiness Inspection
**Audit Outcome:** **CONDITIONALLY READY FOR HOSTINGER DEPLOYMENT** (Subject to Owner Provisioning & Git Commit of Verified Changes)

---

## Executive Summary

A comprehensive, read-only production-readiness audit of the DK Singh Fitness Laravel application was conducted prior to deployment to Hostinger. The newly developed dynamic **Offers & Discounts Management System** and the **Asynchronous Razorpay Webhook Reconciliation System** were verified, along with the full platform test suite (274 passing tests, 1,856 assertions, 0 failures).

The application codebase is secure, structurally sound, free of hardcoded secrets or debug statements, and adheres to strict server-side financial validations. This audit details the local technical evidence, flags operational considerations for Hostinger shared/cloud hosting, and provides a prioritized step-by-step deployment procedure.

---

## 1. Code and Git Audit

### 1.1 Current Repository Status
* **Current Branch:** `review/phase2-security`
* **Current Commit Hash:** `edf2d7e69a8c589cfd3a88254196c1ab84e0e5b7`
* **Remote Tracking:** Synchronized with `origin/review/phase2-security` (up to commit `edf2d7e`)
* **Production (`main`) Branch Status:** Commit `66985c46371cceb3be65dc9e034ff28372919b5b`
* **Branch Divergence:** The `review/phase2-security` branch is **18 commits ahead of `origin/main`**.

### 1.2 Uncommitted Working-Tree Changes
The working tree contains the newly implemented and functionally verified **Dynamic Offers & Discounts Management System**, along with the deployment documentation:

| Status | File Path | Description |
| :--- | :--- | :--- |
| **Modified** | `app/Filament/Resources/OrderResource.php` | Added discount and offer columns/filters to Filament orders table |
| **Modified** | `app/Http/Controllers/Front/ProductController.php` | Injected valid offer badges onto product cards and product details |
| **Modified** | `app/Http/Controllers/Front/ProductPaymentController.php` | Dynamic offer validation, AJAX promo endpoints, and Razorpay reinitialization |
| **Modified** | `app/Models/Order.php` | Added `offer_id`, `promo_code`, `discount_amount`, `subtotal`, `pricing_snapshot` |
| **Modified** | `app/Models/Product.php` | Added `activeOffers()` and `featuredOffer()` relationships |
| **Modified** | `resources/views/checkout/product-payment.blade.php` | Interactive coupon box, applied offer pill, and dynamic price breakdown |
| **Modified** | `resources/views/components/promo/powder-banner.blade.php` | Dynamic promo banner powered by active/featured offer settings |
| **Modified** | `resources/views/products/index.blade.php` | Offer badges on product catalog cards |
| **Modified** | `resources/views/products/show.blade.php` | Offer badges and promo announcements on product detail page |
| **Modified** | `routes/web.php` | Added `POST /checkout/product/{id}/apply-offer` route |
| **Untracked** | `app/Filament/Resources/OfferResource.php` | Dedicated Filament admin CRUD resource for marketing offers |
| **Untracked** | `app/Filament/Resources/OfferResource/*` | Filament offer form, table, and lifecycle pages |
| **Untracked** | `app/Models/Offer.php` | Core Offer model with percentage/flat discount types and validity scopes |
| **Untracked** | `app/Models/OfferRedemption.php` | Redemption audit trail linking offers, orders, and users |
| **Untracked** | `app/Services/OfferService.php` | Centralized discount calculation, coupon validation, and redemption recording |
| **Untracked** | `database/migrations/2026_10_10_120000_create_offers_table.php` | Schema for offers table |
| **Untracked** | `database/migrations/2026_10_10_120100_add_discount_fields_to_orders_table.php` | Schema for orders discount audit columns |
| **Untracked** | `database/migrations/2026_10_10_120200_seed_default_powder15_offer.php` | Initial seed migration for default `POWDER15` promo |
| **Untracked** | `docs/HOSTINGER_DEPLOYMENT_CHECKLIST.md` | Comprehensive operational deployment checklist |
| **Untracked** | `tests/Feature/Offers/*` | Feature test suites for offer admin CRUD and checkout application |
| **Untracked** | `tests/Unit/OfferCalculationTest.php` | Unit tests for percentage and flat discount edge cases |

### 1.3 Intended Production Branch & Deployment Strategy
* **Recommendation:** The 18 commits on `review/phase2-security` plus the uncommitted dynamic offers changes contain essential production infrastructure:
  1. Cryptographic HMAC-SHA256 Razorpay payment verification.
  2. Enterprise settings and theme token persistence.
  3. Safe content import/export with SHA-256 validation.
  4. Database indexing and query optimization.
  5. The complete Dynamic Offers & Discounts engine.
* **Action Required Before Deploying:**
  - Commit the uncommitted working-tree changes to `review/phase2-security`.
  - Create a pull request or merge `review/phase2-security` into `main`, OR build the release tarball directly from the committed `review/phase2-security` branch.
  - Never deploy `origin/main` in its current state (`66985c4`), as it lacks all Phase 2 security, branding, and offer fixes.

### 1.4 Debug Code, Exposed Secrets & Code Smells
* **Debug Statements (`dd`, `dump`, `var_dump`, `ray`):** Scanned entire `app/` codebase — **0 occurrences found** (PASS).
* **Unfinished Comments (`TODO`, `FIXME`, `HACK`):** Scanned entire `app/` codebase — **0 occurrences found** (PASS).
* **Exposed Secrets:** No API keys, passwords, or tokens hardcoded in configuration or application code. All third-party providers read from `env()` (PASS).
* **Unsafe Defaults:**
  - `config/app.php` defaults `APP_DEBUG` to `false` when env is unset.
  - `.env.example` has `APP_DEBUG=true` (must be explicitly set to `false` in production `.env`).
  - `config/session.php` defaults `SESSION_SECURE_COOKIE` to `null` if not set (must be explicitly set to `true` in production `.env`).

---

## 2. Laravel & Hostinger Compatibility Audit

### 2.1 PHP Version & Extension Requirements
* **Framework:** Laravel 12.0
* **Composer PHP Constraint:** `^8.2`
* **Hostinger Recommended Version:** **PHP 8.2** or **PHP 8.3** (configured via hPanel PHP Configuration).
* **Required PHP Extensions:**
  - Standard Laravel Core: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `json`, `libxml`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql`, `session`, `tokenizer`, `xml`, `zip`.
  - Media & Filament Processing: `gd` (or `imagick`), `intl`.
* **Memory & Execution Limits:**
  - `memory_limit >= 256M` (recommended: `512M`).
  - `max_execution_time >= 120`.

### 2.2 Composer Installation Procedure
On Hostinger (via SSH terminal):
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```
* Note: Development dependencies (`phpunit`, `pestphp`, `fakerphp`, `mockery`) must never be installed in production.

### 2.3 Required Writable Directories
The web server process (`www-data` or the Hostinger account user) requires read-write permissions (`chmod -R 775` or `755`) on:
- `storage/app/`
- `storage/app/public/`
- `storage/framework/cache/data/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `storage/logs/`
- `bootstrap/cache/`

### 2.4 Production Build Assets & Vite Manifest
Vite asset compilation was verified locally. The `public/build/` directory is fully compiled and ready:
* **Manifest Path:** `public/build/manifest.json` (Verified intact, 770 bytes).
* **Compiled Asset Chunks on Disk:**
  - `public/build/assets/app-ET2CgyA6.css` (350,516 bytes)
  - `public/build/assets/app-CmIcN3HT.js` (223,285 bytes)
  - `public/build/assets/app-BOMoYPYa.css` (29,602 bytes)
  - `public/build/assets/theme-DIWJCroO.css` (15,370 bytes)
  - `public/build/assets/blog-DtVH0wjW.css` (2,745 bytes)
* **Hostinger Implication:** Node.js and npm are **NOT required** on the Hostinger server. All assets are pre-compiled and served directly from `public/build/`.

### 2.5 Document Root & Storage Link Architecture
Hostinger Shared Hosting accounts typically assign `public_html/` as the default document root.

> [!CAUTION]
> **NEVER upload the entire Laravel root directory directly into `public_html/`!**
> Doing so would expose `.env`, `composer.json`, `storage/logs/`, and the `database/` directory to the public internet via direct URL requests.

#### Recommended Directory Layout on Hostinger:
1. Place the Laravel application in a non-public folder, e.g.:
   `/home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness/`
2. Configure Document Root:
   - **Option A (Preferred):** In Hostinger hPanel -> Websites -> Domains -> Edit, change the Document Root to `/domains/dksinghfitness.com/dk-singh-fitness/public`.
   - **Option B (Symlink):** If hPanel restricts changing the root, delete the default `public_html` directory and create a symlink:
     ```bash
     ln -s /home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness/public /home/uXXXXXXXXX/domains/dksinghfitness.com/public_html
     ```

#### Storage Symlink Configuration:
Run via SSH:
```bash
php artisan storage:link
```
* **Hostinger Fallback:** If PHP CLI `symlink()` is disabled by Hostinger security policy, execute directly in bash:
  ```bash
  ln -s /home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness/storage/app/public /home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness/public/storage
  ```

---

## 3. Database & Migrations Audit

### 3.1 Migration Inventory & Local Status
* **Total Migration Files:** 117 sequential PHP migrations in `database/migrations/` (plus 2 `.bak` legacy backup files that are excluded from release archives).
* **Local Migration Status:** All 117 migrations have successfully run against the local MySQL instance (`php artisan migrate:status` reports all `Ran`).
* **Latest Migrations (Dynamic Offers System):**
  - `2026_10_10_120000_create_offers_table`: Creates `offers` table with code, discount type, value, expiry, redemption tracking.
  - `2026_10_10_120100_add_discount_fields_to_orders_table`: Adds `subtotal`, `discount_amount`, `offer_id`, `promo_code`, and `pricing_snapshot` (JSON) to `orders`.
  - `2026_10_10_120200_seed_default_powder15_offer`: Idempotent data migration seeding default 15% off `POWDER15` offer.

### 3.2 High-Risk Migrations Requiring Production Caution
1. `2026_08_05_000002_expand_settings_and_theme_design_system.php`:
   - Consolidates legacy flat settings into grouped JSON columns, then drops the legacy columns.
   - Safe on clean installs. If executed against an existing populated database, pre-migration database backup is mandatory.
2. `2026_08_05_000003_encrypt_provider_credentials.php`:
   - Encrypts SMS, WhatsApp, and email provider API secrets using Laravel's `Crypt::encryptString()` (derived from `APP_KEY`).
   - Requires that the production `APP_KEY` remains permanent and unchanged.

### 3.3 Core Tables Inventory
The database contains the following critical functional schemas:
* **Offers & Redemptions:** `offers`, `offer_redemptions`
* **Orders & Commerce:** `orders`, `order_items`, `shipments`, `shipment_events`, `courier_providers`
* **Catalog:** `products`, `programs`, `services`, `plans`, `exercises`, `workout_plans`, `diet_plans`
* **Media Management:** `media` (364 records), `media_categories` (9 categories)
* **Users & Auth:** `users`, `sessions`, `password_reset_tokens`
* **CMS & Content:** `settings`, `hero_settings`, `theme_settings`, `website_sections`, `homepage_cards`, `blog_posts`, `blog_categories`, `blog_tags`, `blog_post_tag`, `testimonials`, `transformations`, `transformation_photos`
* **Auditing:** `security_audit_logs`, `communication_logs`

### 3.4 Production Database Compatibility
* **Driver:** `mysql` (MariaDB compatible).
* **Charset / Collation:** `utf8mb4` / `utf8mb4_unicode_ci`.
* **Foreign Keys & Strict Mode:** Strict mode enabled (`strict => true`), InnoDB foreign keys with proper cascades.
* **Safety Rule:** **NEVER run `migrate:fresh` or `db:wipe` on production.** Always take a verified `mysqldump` snapshot before executing `php artisan migrate --force`.

---

## 4. Content & Media Audit

### 4.1 Content Export Package Verification
* **File Location:** `storage/app/content_export/website_content_package.json`
* **File Size:** 2,507,101 bytes (~2.5 MB)
* **File OS SHA-256 Checksum:** `C27ECC6B0E809674B2DF80C3056FE96598D2A87110F9B4CDC9312EB64CEB96B3`
* **Package Checksum (Payload Checksum):** `a444c0e24071040297245132662ff19c22de98bc97cd5d3ed0038fe5d494e688`
* **Application Validation:** Executed `php artisan content:import --file=storage/app/content_export/website_content_package.json --dry-run`:
  - Result: `Package checksum verified: a444c0e24071040297245132662ff19c22de98bc97cd5d3ed0038fe5d494e688` (PASS).
  - Dry-Run Status: All 22 content tables verified as valid.
* **Content Protection:**
  - Whitelist enforcement strictly protects transactional and sensitive tables (`users`, `orders`, `payments`, `offers`, `offer_redemptions`). Content import will never overwrite customer accounts, orders, or custom marketing offers.

### 4.2 Media Integrity Audit
* **Database Records:** 364 active media items in `media` table.
* **Disk Files in `storage/app/public/`:** 379 physical files totaling 136,477,743 bytes (~136.5 MB).
* **Integrity Script Execution:** Executed path verification checking every `Media::pluck('path')` against `storage/app/public/{path}`:
  - **Total Media in DB:** 364
  - **Missing on disk:** 0
  - **Verdict:** **100% integrity match.** (The additional 15 files on disk represent system avatars and legacy test assets).

### 4.3 Media Transfer & Restoration Procedure
1. Create compressed media archive locally:
   ```bash
   tar -czvf dk-singh-media.tar.gz -C storage/app public
   ```
2. Upload `dk-singh-media.tar.gz` to the server under `storage/app/`.
3. Extract archive into `storage/app/`:
   ```bash
   cd /home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness/storage/app
   tar -xzvf dk-singh-media.tar.gz
   ```
4. Confirm permissions and symlink:
   ```bash
   chmod -R 775 public
   php artisan storage:link
   ```
5. Import canonical content package safely:
   ```bash
   php artisan content:import --file=storage/app/content_export/website_content_package.json --dry-run
   php artisan content:import --file=storage/app/content_export/website_content_package.json --backup
   ```

---

## 5. Payments & Security Audit

### 5.1 Razorpay Production Configuration
* **Required Live Credentials in `.env`:**
  - `RAZORPAY_KEY` (or `RAZORPAY_KEY_ID`): `rzp_live_xxxxxxxxxxxxxxxx`
  - `RAZORPAY_SECRET` (or `RAZORPAY_KEY_SECRET`): High-entropy secret key provided by Razorpay dashboard
  - `RAZORPAY_WEBHOOK_SECRET`: Webhook secret configured in Razorpay dashboard
* **Credential Secrecy:** Verified that no credentials exist in Git or template files.

### 5.2 Server-Side Payment Verification & Idempotency
* **Order Creation:** `ProductPaymentController::checkout` creates the Razorpay order server-side via `RazorpayService::createOrder()` using the discounted total in paise (`(int) round($final_amount * 100)`).
* **Cryptographic Verification:**
  - `RazorpayService::verify()` invokes `$this->api->utility->verifyPaymentSignature()` (HMAC-SHA256 signature verification).
  - Fetches the order and payment records directly from the Razorpay REST API.
  - Enforces `hash_equals` checks on order IDs and payment IDs.
  - Verifies exact paise amount match: `(int) $order['amount'] === (int) $expected['amount']` and `(int) $payment['amount'] === (int) $expected['amount']`.
  - Verifies INR currency and `$payment['status'] === 'captured'`.
* **Idempotency Protection:**
  - `Order::where('payment_id', $validated['razorpay_payment_id'])->exists()` blocks duplicate order processing if a customer submits the form repeatedly.
* **Discount Calculation Atomicity:**
  - Offer redemption and order creation run inside a database transaction (`DB::transaction(...)`).
  - Uses `lockForUpdate()` on the `Offer` record to prevent race conditions exceeding `max_uses`.
  - Persists an immutable `pricing_snapshot` JSON array in `orders` storing the exact discount math at the moment of payment.

### 5.3 Webhook Handling Review
* **Current State:** Asynchronous webhook listener is fully implemented at `POST /webhooks/razorpay` (`RazorpayWebhookController`), registered in `routes/web.php`, and exempted from CSRF verification in `bootstrap/app.php`.
* **Security & Idempotency:**
  - Raw body HMAC-SHA256 signature verification (`$request->getContent()`) validated against `config('services.razorpay.webhook_secret')`.
  - Concurrency lock (`Cache::lock("razorpay_payment_processing_{$paymentId}", 10)->block(5)`) shared between webhooks and browser redirects.
  - Idempotent order matching (`Order::where('payment_id', $paymentId)->exists()`) ensures duplicate webhook deliveries or browser race conditions never duplicate orders.
  - Reconciles against trusted checkout records (`razorpay_order_{order_id}`). If cached metadata is missing or evicted, the controller queries the authoritative Razorpay API order (`RazorpayService::fetchOrder`) to reconstruct trusted metadata. If trusted records cannot be established, the payment is strictly rejected with HTTP 422 (catalog price fallback eliminated).
* **Verdict:** **HARDENED & PASS**. Captures background and orphan transactions safely while actively blocking tampered amounts.

### 5.4 Authentication, Admin Authorization & Session Security
* **Authentication:** Protected by standard Laravel session guards. Guest access to checkout payment processing is rejected.
* **Admin Authorization (Filament v3.3.52):**
  - `App\Models\User` implements `FilamentUser`.
  - `canAccessPanel()` enforces `$this->isAdmin()`, which requires `account_type === 'admin' || is_admin === true`.
  - Note: Standard `php artisan make:filament-user` creates users with `account_type='customer'` and `is_admin=false`. To grant panel access, admin elevation via Tinker or SQL is required.
* **CSRF Protection:** Enabled on all state-altering web POST routes via Laravel's `VerifyCsrfToken` middleware.
* **Session Security:**
  - `.env.production.example` configures `SESSION_DRIVER=database`, `SESSION_ENCRYPT=true`, `SESSION_SECURE_COOKIE=true`, `SESSION_LIFETIME=120`, and `same_site=lax`.

---

## 6. Operations Audit

### 6.1 Mail & SMTP
* **Configuration:** Requires Hostinger Titan Email or third-party transactional SMTP:
  - `MAIL_MAILER=smtp`
  - `MAIL_HOST=smtp.hostinger.com` (or provider)
  - `MAIL_PORT=465` (SSL) or `587` (TLS)
  - `MAIL_USERNAME` / `MAIL_PASSWORD`
  - `MAIL_FROM_ADDRESS="info@dksinghfitness.com"`
* **Communication Logs:** `CommunicationService` writes dispatch logs to `communication_logs` table.

### 6.2 Cache & Sessions
* `CACHE_STORE=database`: Uses `cache` and `cache_locks` tables.
* `SESSION_DRIVER=database`: Uses `sessions` table.
* **Production Optimization Commands:**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```

### 6.3 Queues on Hostinger Shared Hosting
* Shared hosting does not support long-running supervisor daemons (`php artisan queue:work`).
* **Options:**
  1. `QUEUE_CONNECTION=sync` (Recommended for immediate simplicity).
  2. `QUEUE_CONNECTION=database` with a Hostinger Cron Job running every minute:
     ```bash
     * * * * * cd /home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
     ```

### 6.4 Logging & Storage Permissions
* `LOG_CHANNEL=daily`
* `LOG_LEVEL=error`
* Prevents log file bloating on shared hosting quotas.

### 6.5 Hostinger Scheduled Tasks (Cron)
Configure in hPanel -> Advanced -> Cron Jobs:
```bash
* * * * * cd /home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness && php artisan schedule:run >> /dev/null 2>&1
```

---

## 7. Final Verdict & Deployment Checklist

### 7.1 Audit Finding Classifications

| Scope | Item | Status | Notes |
| :--- | :--- | :---: | :--- |
| **Code & Git** | Zero debug code (`dd`, `dump`, `ray`) | **PASS** | 0 occurrences in `app/` |
| **Code & Git** | Zero incomplete TODOs | **PASS** | 0 occurrences in `app/` |
| **Code & Git** | Automated Test Suite (274 tests) | **PASS** | 100% tests passing locally (1,856 assertions) |
| **Code & Git** | Working Tree Status | **WARNING** | Dynamic offers and webhook changes are currently uncommitted; must commit before release |
| **Build** | Compiled assets & manifest (`public/build`) | **PASS** | Manifest and all 5 chunk assets verified on disk |
| **Compatibility** | PHP 8.2+ / MySQL 8.0+ Compatibility | **PASS** | Strict mode & standard SQL compliant |
| **Content** | Content package SHA-256 Checksum | **PASS** | Validated via `content:import --dry-run` |
| **Media** | Media library integrity (364 records) | **PASS** | 364/364 database media records exist on disk |
| **Payments** | Server-side Razorpay verification & idempotency | **PASS** | Cryptographic HMAC & paise amount matching |
| **Payments** | Razorpay asynchronous webhook endpoint | **PASS** | Asynchronous webhook implemented, raw HMAC verified, and hardened |
| **Security** | Production secrets isolation | **PASS** | No credentials hardcoded |
| **Operations** | Hostinger server environment | **BLOCKER** | Requires Hostinger account access to configure PHP, MySQL, SSL, and `.env` |
| **Operations** | Production APP_KEY safety | **BLOCKER** | Existing production key must never be overwritten; new key generated only on initial install |

---

### 7.2 Distinguishing Locally Verified Facts vs Hostinger Prerequisites

#### Locally Verified Facts (100% Confirmed):
1. The dynamic Offers & Discounts system functions properly across admin, frontend, and checkout.
2. The complete test suite passes (274 tests, 1,856 assertions).
3. The content export package checksum (`a444c0e2...`) matches the internal hash algorithm and passes dry-run import.
4. All 364 database media records exist in `storage/app/public/`.
5. `public/build/manifest.json` exists and references all CSS/JS bundles.
6. The codebase contains zero debug statements (`dd`, `dump`, `var_dump`) or hardcoded secrets.

#### Hostinger Prerequisites (Cannot Be Verified Locally):
1. Hostinger SSH terminal and file manager access.
2. Production MySQL database name, username, and password.
3. Live Razorpay API keys (`rzp_live_...`).
4. Let's Encrypt SSL certificate issuance and "Force HTTPS" toggle in hPanel.
5. Verification of server PHP version (`php -v` >= 8.2) and enabled extensions.

---

### 7.3 Prioritized Deployment Checklist

#### Step 1: Pre-Deployment Git Finalization (Local)
- [ ] Commit all uncommitted changes on `review/phase2-security`:
  ```bash
  git add app/ resources/ routes/ database/migrations/ tests/ docs/
  git commit -m "feat(offers): implement dynamic offers and discount management system"
  ```
- [ ] Create release packaging tarballs:
  ```bash
  # Application Code (excluding vendor, node_modules, .git, .env, tests, media)
  tar --exclude='*vendor*' \
      --exclude='*node_modules*' \
      --exclude='*.git*' \
      --exclude='*.agents*' \
      --exclude='*tests*' \
      --exclude='*phpunit*' \
      --exclude='*pest.php*' \
      --exclude='./.env' \
      --exclude='./.env.local*' \
      --exclude='./.env.backup*' \
      --exclude='./.env.testing*' \
      --exclude='*database/*.sqlite*' \
      --exclude='*storage/logs/*' \
      --exclude='*storage/backups/*' \
      --exclude='*storage/framework/cache/*' \
      --exclude='*storage/framework/sessions/*' \
      --exclude='*storage/framework/views/*' \
      --exclude='*storage/framework/testing*' \
      --exclude='*storage/app/public/*' \
      --exclude='*public/storage*' \
      --exclude='*livewire-tmp*' \
      --exclude='*.bak*' \
      --exclude='*.sql' \
      --exclude='*.txt' \
      -czvf dk-singh-fitness-release.tar.gz .

  # Media Assets
  tar -czvf dk-singh-media.tar.gz -C storage/app public
  ```

#### Step 2: Hostinger Environment Provisioning (hPanel)
- [ ] Ensure PHP version is set to **8.2** or **8.3**.
- [ ] Create MySQL database and database user; note credentials.
- [ ] Install Let's Encrypt SSL certificate and enable "Force HTTPS".
- [ ] Set up directory structure: Place Laravel in `dk-singh-fitness/` outside `public_html/`.

#### Step 3: Server Extraction & Setup (SSH)
- [ ] Upload and extract `dk-singh-fitness-release.tar.gz` into `/home/uXXXXXXXXX/domains/dksinghfitness.com/dk-singh-fitness/`.
- [ ] Upload and extract `dk-singh-media.tar.gz` into `storage/app/`.
- [ ] Install composer dependencies:
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- [ ] Set directory permissions:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```
- [ ] Create storage symlink:
  ```bash
  php artisan storage:link
  ```

#### Step 4: Environment Configuration & APP_KEY Rule
- [ ] Copy `.env.production.example` to `.env`.
- [ ] Set database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
- [ ] Set Razorpay live credentials (`RAZORPAY_KEY`, `RAZORPAY_SECRET`).
- [ ] Set `APP_DEBUG=false`, `APP_ENV=production`, `APP_URL=https://dksinghfitness.com`.
- [ ] **APP_KEY Check:**
  - If a production `APP_KEY` already exists: **DO NOT REGENERATE.**
  - If brand-new install with unset key: Run `php artisan key:generate --no-interaction`.

#### Step 5: Database Migration & Content Import
- [ ] Take pre-migration database backup (`mysqldump`).
- [ ] Run migrations:
  ```bash
  php artisan migrate --force
  ```
- [ ] Dry-run content import:
  ```bash
  php artisan content:import --file=storage/app/content_export/website_content_package.json --dry-run
  ```
- [ ] Run canonical content import:
  ```bash
  php artisan content:import --file=storage/app/content_export/website_content_package.json --backup
  ```

#### Step 6: Admin Provisioning & Caching
- [ ] Create Filament admin user:
  ```bash
  php artisan make:filament-user
  ```
- [ ] Elevate user to administrator:
  ```bash
  php artisan tinker --execute="App\Models\User::where('email', '<admin_email>')->update(['account_type' => 'admin', 'is_admin' => 1]);"
  ```
- [ ] Compile production caches:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```

#### Step 7: Post-Deployment Smoke Test
- [ ] Visit `https://dksinghfitness.com/` — Verify HTTPS padlock and visual layout.
- [ ] Visit `/products` and product details — Verify offer badges and active promo banner.
- [ ] Test checkout modal — Verify Razorpay live modal launches with accurate paise amount.
- [ ] Log in at `/admin` — Verify Filament dashboard, `Offers` resource, and settings management.
- [ ] Check `storage/logs/laravel.log` — Confirm zero uncaught exceptions.

---
*Audit completed by Antigravity AI Engineering Assistant.*
