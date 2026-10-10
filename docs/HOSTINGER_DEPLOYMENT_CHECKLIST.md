# DK Singh Fitness — Hostinger Production Deployment Checklist

**Target Domain:** `dksinghfitness.com` (or target Hostinger domain)
**Application Stack:** Laravel 12.x / PHP 8.2+ / MySQL / Filament v3 / Vite (Tailwind CSS)
**Canonical Package:** `storage/app/content_export/website_content_package.json` (SHA-256 verified)
**Security Level:** Production / Financial & Payment Processing (Razorpay)

---

## CRITICAL SAFETY & RISK WARNINGS

> [!CAUTION]
> **1. PERMANENT PRODUCTION APP_KEY PRESERVATION**
> Once the production `APP_KEY` is generated in `.env`, it **must NEVER be unconditionally regenerated or overwritten**.
> Application migrations and database models encrypt sensitive courier API keys and communication credentials using Laravel's `Crypt::encryptString()` (derived directly from `APP_KEY`). Regenerating `APP_KEY` after database initialization will permanently destroy access to all encrypted credentials and invalidate active user sessions.
> **Rule:** Always check whether `APP_KEY` is already present before running any key generation. Preserve existing keys. Generate a key *only* for a confirmed first-time installation where `APP_KEY` is completely unset. Never print or log the key.

> [!WARNING]
> **2. UNKNOWN DATABASE STATE & MIGRATION AUDIT BEFORE RUNNING `--force`**
> Treat the production database state as **completely unknown** until inspected. Never blindly run `php artisan migrate --force`.
> - Always take a verified `mysqldump` backup before running migrations.
> - Run `php artisan migrate:status` to inspect the exact state of migrations on the production database.
> - Migration `2026_08_05_000002_expand_settings_and_theme_design_system.php` drops legacy columns after consolidating them into JSON structures. In a clean installation, this executes properly, but running migrations against an existing partially populated database requires a verified pre-migration SQL dump.
> - Migration `2026_08_05_000003_encrypt_provider_credentials.php` encrypts provider credentials using the production `APP_KEY`.

> [!WARNING]
> **3. SAFE CONTENT IMPORT & BACKUP PREREQUISITES**
> Never run `content:import` on a non-empty database without first running a full `--dry-run` and generating a verified database backup. `php artisan content:import` (verified in `app/Console/Commands/ImportWebsiteContent.php`) natively supports `--dry-run` and `--backup`. It is guarded against touching customer tables (`users`, `orders`, `payments`, etc.), but it will truncate/replace content tables (`settings`, `products`, `programs`, `articles`). Always use the `--backup` flag:
> ```bash
> php artisan content:import --file=storage/app/content_export/website_content_package.json --dry-run
> php artisan content:import --file=storage/app/content_export/website_content_package.json --backup
> ```

> [!IMPORTANT]
> **4. FILAMENT ADMIN USER PROVISIONING & AUTHORIZATION RULES**
> Installed Filament version is **v3.3.52**. The `App\Models\User` model implements `FilamentUser` with `canAccessPanel()` delegating to `isAdmin()`, which requires `account_type === 'admin' || is_admin === true`.
> Running `php artisan make:filament-user` creates a user record with standard fields (`name`, `email`, `password`), but sets default `account_type='customer'` and `is_admin=false`. Therefore, creating the user alone will not grant panel access until administrative status is explicitly granted and verified.

> [!CAUTION]
> **5. SAFE ROLLBACK PROCEDURE (DO NOT ROUTINELY DROP TABLES)**
> Never adopt "dropping tables" or `migrate:fresh` / `db:wipe` as a rollback method on production. A rollback must restore the verified pre-deployment MySQL dump and file snapshot. Test the restore procedure in a staging or local environment before proceeding.

---

## STEP-BY-STEP HOSTINGER DEPLOYMENT CHECKLIST

### Phase 1: Hostinger Pre-Flight Evidence Collection
*All items in this phase require verification before performing any actions.*

- [ ] **1.1. Hostinger Account & Plan Verification** `[OWNER APPROVAL REQUIRED]`
  - Hostinger Cloud Hosting or Business Web Hosting confirmed.
  - SSH Access enabled in hPanel (`hPanel -> Advanced -> SSH Access`).
  - Terminal SSH connection tested successfully.
- [ ] **1.2. PHP Environment Evidence** `[OWNER APPROVAL REQUIRED]`
  - PHP CLI and Web version set to **PHP 8.2 or 8.3** (`php -v`).
  - Required PHP extensions confirmed via `php -m`:
    - Core: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`
    - Media / PDF: `gd` (or `imagick`), `intl`
  - Memory limit set to `>= 256M` (preferably `512M`), `max_execution_time >= 120`.
- [ ] **1.3. Web Root & Document Directory Strategy** `[OWNER APPROVAL REQUIRED]`
  - Confirm document root setup in hPanel:
    - *Preferred:* Configure custom document root to `/home/uXXXXXXXXX/dk-singh-fitness/public`.
    - *Alternative:* Place Laravel in `/home/uXXXXXXXXX/laravel/` and create symlink from `/home/uXXXXXXXXX/public_html` pointing to `/home/uXXXXXXXXX/laravel/public`.
  - **Never** extract the entire Laravel source directly inside `public_html/`.

---

### Phase 2: Backup & Rollback Prerequisites
*Protect all existing data before introducing release files.*

- [ ] **2.1. Existing Hostinger Web & DB Backup** `[OWNER APPROVAL REQUIRED]`
  - If a website or database already exists under this domain, create a complete snapshot via `hPanel -> Backups` and download it locally.
  - Export existing MySQL database dump:
    ```bash
    mysqldump -u <user> -p <database_name> > pre_deploy_backup_$(date +%Y%m%d_%H%M%S).sql
    ```
- [ ] **2.2. Emergency Rollback Preparation**
  - Verify access to emergency 503 maintenance mode:
    ```bash
    php artisan down --secret="emergency-bypass-token" --render="errors::503"
    ```
  - Document restore command for the database:
    ```bash
    mysql -u <user> -p <database_name> < pre_deploy_backup_YYYYMMDD_HHMMSS.sql
    ```

---

### Phase 3: Production Code & Asset Packaging (Local Preparation)
*Build and verify all assets locally before transferring.*

- [ ] **3.1. Local Code Baseline Verification**
  - Verify git branch is synchronized: commit `edf2d7e` on `review/phase2-security` (or approved merged `main`).
  - Verify working tree is clean.
  - Run full test suite: `php artisan test` (must report 274 passed tests with 1,856 assertions).
- [ ] **3.2. Compile Production Assets Locally**
  - Run `npm run build` locally.
  - Verify `public/build/manifest.json` exists and references all CSS/JS bundles.
- [ ] **3.3. Verify Canonical Content Package & Media**
  - Verify `storage/app/content_export/website_content_package.json` exists (2,507,101 bytes).
  - Verify SHA-256 checksum: `a444c0e24071040297245132662ff19c22de98bc97cd5d3ed0038fe5d494e688`.
  - Verify 364 media files in `storage/app/public/` (130 MB).
- [ ] **3.4. Create Clean Release Archive (Exclusions Applied)**
  - **Application Archive Exclusions:**
    - `vendor/` (installed on server via `composer install --no-dev`)
    - `node_modules/`
    - `.git/`, `.github/`, `.agents/`
    - `tests/`, `phpunit.xml`, `pest.php`
    - Secret env files: `.env`, `.env.local*`, `.env.backup*`, `.env.testing*` (preserves safe `.env.example` and `.env.production.example`)
    - `database/*.sqlite*`, `*.sql` (e.g. `dk_singh_fitness.sql`)
    - `storage/logs/*`, `storage/backups/*`, `storage/framework/cache/*`, `storage/framework/sessions/*`, `storage/framework/views/*`, `storage/framework/testing*`, `livewire-tmp*`
    - Media directory: `storage/app/public/*` and symlink `public/storage*` (packaged separately)
    - `*.bak` files, `*.txt` audit logs, `screenshots_evidence*`
  - **Step A: Package Application Code & Seed Data (~6 MB):**
    ```bash
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
        --exclude='*screenshots_evidence*' \
        --exclude='*.bak*' \
        --exclude='*.sql' \
        --exclude='*.txt' \
        -czvf dk-singh-fitness-release.tar.gz .
    ```
  - **Step B: Package Media Assets Separately (~126 MB compressed, 364 files):**
    ```bash
    tar -czvf dk-singh-media.tar.gz -C storage/app public
    ```
  - **Step C: Inspect Archive Listings Before Approving:**
    ```bash
    # 1. Verify NO credentials, SQL dumps, logs, backups, tests, or sqlite files are inside application archive (must return 0 output):
    tar -tzvf dk-singh-fitness-release.tar.gz | grep -E '(^\./\.env$|\.sqlite|\.bak$|\.sql$|storage/logs/|storage/backups/|tests/|vendor/|node_modules/)'

    # 2. Confirm public/build, manifest, and website_content_package.json are present:
    tar -tzvf dk-singh-fitness-release.tar.gz | grep 'public/build/manifest.json'
    tar -tzvf dk-singh-fitness-release.tar.gz | grep 'storage/app/content_export/website_content_package.json'
    ```
  - **Web Directory Isolation:**
    Confirm that no credentials, SQL dumps, local logs, or backups are located in `public/` or can enter the public web directory.

---

### Phase 4: Hostinger MySQL Database Provisioning
*Provision clean production database.*

- [ ] **4.1. Create Database & User in hPanel** `[OWNER APPROVAL REQUIRED]`
  - Create new database: e.g. `uXXXXXXXXX_dksingh`
  - Create database user: e.g. `uXXXXXXXXX_dkuser`
  - Generate a secure, high-entropy password (do not store in Git).
  - Note host (typically `localhost` or `127.0.0.1`).

---

### Phase 5: File Transfer & Storage Setup
*Transfer codebase and establish permissions.*

- [ ] **5.1. Upload Application Archive & Media**
  - Upload application archive to `/home/uXXXXXXXXX/dk-singh-fitness/`.
  - Extract archive.
  - Upload `storage/app/public/` contents to server.
- [ ] **5.2. Install Composer Dependencies on Server**
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- [ ] **5.3. Set Permissions & Storage Symlink**
  ```bash
  chmod -R 775 storage bootstrap/cache
  php artisan storage:link
  ```
  - **Verify Symlink:** Check that `public/storage` points to `storage/app/public` (`ls -la public/storage`).
  - *Hostinger Fallback:* If `symlink()` is disabled in PHP CLI, run direct shell command:
    ```bash
    ln -s "$(pwd)/storage/app/public" "$(pwd)/public/storage"
    ```

---

### Phase 6: Production Environment (.env) Configuration
*Configure environment without exposing secrets.*

- [ ] **6.1. Generate Production .env File** `[OWNER APPROVAL REQUIRED]`
  - Copy `.env.example` to `.env` on server.
  - Set `APP_ENV=production`
  - Set `APP_DEBUG=false`
  - Set `APP_URL=https://dksinghfitness.com`
  - Set `DB_CONNECTION=mysql`, `DB_HOST=localhost`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
  - Set `SESSION_DRIVER=database`
  - Set `CACHE_STORE=database`
  - Set `QUEUE_CONNECTION=sync`
  - Set `FILESYSTEM_DISK=public`
- [ ] **6.2. Verify or Generate Permanent Application Key** `[OWNER APPROVAL REQUIRED]`
  - **Check for Existing Key First (Mandatory):**
    ```bash
    grep -E "^APP_KEY=base64:.+" .env
    ```
  - **Rule 1 (Existing Key Found):** If a valid key already exists, **DO NOT REGENERATE.** Retain and preserve the existing key to avoid breaking encrypted credentials and sessions.
  - **Rule 2 (Confirmed First-Time Installation Only):** If and ONLY IF this is a brand new installation with an empty/unset `APP_KEY=`, generate the key safely without printing or logging it:
    ```bash
    php artisan key:generate --no-interaction
    ```
  - **Safety Warning:** Never run `key:generate --show` and never print or log the key in shell history or shared documentation. Store a copy of `.env` securely in an offline password manager.
- [ ] **6.3. Configure SMTP & Payment Credentials** `[OWNER APPROVAL REQUIRED]`
  - Configure Hostinger Titan Mail or third-party SMTP:
    - `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`.
  - Configure Razorpay Live credentials:
    - `RAZORPAY_KEY` (rzp_live_...)
    - `RAZORPAY_SECRET`
    - `RAZORPAY_WEBHOOK_SECRET`

---

### Phase 7: Database Migration & Content Seeding
*Treat production database as unknown. Verify backup and inspect schema before executing.*

- [ ] **7.1. Database State Inspection & Pre-Migration Backup** `[OWNER APPROVAL REQUIRED]`
  - Treat the production database state as **completely unknown** until inspected.
  - Generate a verified pre-deployment MySQL database dump:
    ```bash
    mysqldump -u <user> -p <database_name> > pre_migration_backup_$(date +%Y%m%d_%H%M%S).sql
    ```
  - Check the live migration status against all 117 PHP migrations in the codebase (plus 2 `.bak` legacy files excluded by release packaging):
    ```bash
    php artisan migrate:status
    ```
  - Review pending migrations, particularly:
    - `2026_08_05_000002_expand_settings_and_theme_design_system.php` (drops legacy columns after consolidating to JSON).
    - `2026_08_05_000003_encrypt_provider_credentials.php` (encrypts credentials using `APP_KEY`).
  - If approved by the owner, execute migrations safely:
    ```bash
    php artisan migrate --force
    ```
  - Verify that all migrations finish with status `Ran` without errors.
- [ ] **7.2. Dry-Run & Execute Content Import** `[OWNER APPROVAL REQUIRED]`
  - *Tooling Verification:* Confirmed that `app/Console/Commands/ImportWebsiteContent.php` natively supports `--dry-run` and `--backup`.
  - Always execute `--dry-run` first to validate package checksum, table whitelist, and ensure forbidden tables (`users`, `orders`, `payments`, etc.) are protected:
    ```bash
    php artisan content:import --file=storage/app/content_export/website_content_package.json --dry-run
    ```
  - Only after a successful dry-run, execute the canonical import with pre-import table backup:
    ```bash
    php artisan content:import --file=storage/app/content_export/website_content_package.json --backup
    ```
- [ ] **7.3. Administrator Provisioning & Verification** `[OWNER APPROVAL REQUIRED]`
  - *Installed Environment:* Filament **v3.3.52**. The `App\Models\User` model implements `FilamentUser` where `canAccessPanel()` requires `isAdmin() === true` (`account_type === 'admin' || is_admin === true`).
  - *Standard Prompt Limitation:* Running `php artisan make:filament-user` creates the user with default `account_type='customer'` and `is_admin=false`, which blocks panel access.
  - **Step A: Create Administrator Account (Interactive):**
    ```bash
    php artisan make:filament-user
    ```
  - **Step B: Elevate Account to Administrator Role:**
    ```bash
    php artisan tinker --execute="App\Models\User::where('email', '<admin_email>')->update(['account_type' => 'admin', 'is_admin' => 1]);"
    ```
  - **Step C: Read-Only Authorization Verification (Do Not Elevate Blindly):**
    ```bash
    php artisan tinker --execute="echo App\Models\User::where('email', '<admin_email>')->first()?->isAdmin() ? 'ADMIN_ACCESS_OK' : 'ACCESS_DENIED';"
    ```
    Confirm output is `ADMIN_ACCESS_OK`.

---

### Phase 8: Optimization & Security Hardening
*Cache configuration and enforce SSL.*

- [ ] **8.1. Build Laravel Production Caches**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```
- [ ] **8.2. Enable SSL & HTTPS in hPanel** `[OWNER APPROVAL REQUIRED]`
  - Install free Let's Encrypt SSL certificate in hPanel.
  - Enable "Force HTTPS".
- [ ] **8.3. Configure Cron Scheduler (if required)**
  - Add Cron job in hPanel:
    `* * * * * cd /home/uXXXXXXXXX/dk-singh-fitness && php artisan schedule:run >> /dev/null 2>&1`

---

### Phase 9: Post-Deployment Smoke Tests
*Verify all critical public and administrative user journeys.*

- [ ] **9.1. Homepage Smoke Test**
  - Verify HTTPS redirect and green padlock.
  - Check hero section typography and image loading.
  - Confirm promo banner renders above navbar (if active in settings).
  - Test navbar sticky scroll, mobile drawer, and mega-menu.
- [ ] **9.2. Products & Catalog Test**
  - Visit `/products` and verify all 11 products render with prices and thumbnails.
  - Visit a product show page (`/products/{slug}`) and check variant selector, badges, and layout.
- [ ] **9.3. Programs & Services Test**
  - Visit unified `/programs` destination and verify coaching program cards render.
  - Verify individual program details and call-to-action buttons.
- [ ] **9.4. Blog & Articles Test**
  - Visit `/blog` and verify article listing.
  - Open a blog post and verify markdown formatting, tags, and reading time.
- [ ] **9.5. Razorpay Checkout Modal Test (Live Mode Verification)** `[OWNER APPROVAL REQUIRED]`
  - Open checkout on a product.
  - Verify modal opens with correct rupee price (accurate paise conversion).
  - Verify Razorpay Checkout SDK initiates properly.
  - Conduct one test transaction or live verification as agreed with owner.
- [ ] **9.6. Dynamic Offers & Coupon Application Smoke Test**
  - On product checkout page, apply coupon `POWDER15` via AJAX coupon box.
  - Verify dynamic discount calculation, subtotal vs discount vs final amount breakdown, and updated Razorpay order creation.
  - Test coupon removal (`__remove__`) and verify total returns to full catalog price.
- [ ] **9.7. Razorpay Asynchronous Webhook Endpoint Verification**
  - Send POST test ping to `https://dksinghfitness.com/webhooks/razorpay` without signature headers.
  - Verify endpoint responds with HTTP 400 (`{"status":"error","message":"Signature missing"}`) instead of 404 (route missing) or 419 (CSRF mismatch), confirming CSRF exemption in `bootstrap/app.php`.
  - In Razorpay Dashboard -> Webhooks, trigger sample webhook delivery and confirm HTTP 200 response.
- [ ] **9.8. Filament Admin Panel Verification** `[OWNER APPROVAL REQUIRED]`
  - Log in at `https://dksinghfitness.com/admin` using the created admin account.
  - Verify dashboard widgets load without database errors.
  - Verify `Offers` admin resource is accessible and lists default `POWDER15` promo.
  - Test updating a non-critical setting in `SettingResource` (e.g. promo banner text) and confirm immediate public reflection.
- [ ] **9.9. Error Log Inspection**
  - Review `storage/logs/laravel.log` on the server to ensure zero uncaught errors or permission denials occurred during testing.

---

### Phase 10: Sign-Off & Handover
*Formal production deployment completion.*

- [ ] **10.1. Owner Acceptance & Sign-Off** `[OWNER APPROVAL REQUIRED]`
- [ ] **10.2. Disable Maintenance Mode (if active)**
  ```bash
  php artisan up
  ```
