# DK SINGH FITNESS & NUTRITION — PRODUCTION DEPLOYMENT CHECKLIST

**Target Application:** DK Singh Fitness & Nutrition  
**Framework:** Laravel 12 (PHP 8.2+) with Filament 3, Livewire 3, Tailwind/Vanilla CSS, MySQL  
**Code Status:** **FROZEN** (All 156 tests passing, zero outstanding build issues)

---

## 1. Server Environment Prerequisites

### 1.1 Operating System & Web Server
- **OS:** Ubuntu 22.04 LTS / 24.04 LTS, Debian 12, or AlmaLinux 9
- **Web Server:** Nginx or Apache 2.4+
- **Document Root:** Must be pointed strictly to the `/public` directory (never the project root).
- **SSL / TLS:** Valid SSL Certificate (Let's Encrypt / Certbot) with HTTP-to-HTTPS redirect enabled.

### 1.2 PHP Runtime
- **PHP Version:** PHP 8.2 or PHP 8.3
- **Required Extensions:**
  - `php-bcmath` (precision math)
  - `php-ctype`
  - `php-curl` (Razorpay & Delhivery API communication)
  - `php-dom` / `php-xml` (DomPDF invoice and report generation)
  - `php-fileinfo` (MIME validation for media uploads)
  - `php-gd` or `php-imagick` (Image processing & resizing)
  - `php-mbstring`
  - `php-mysql` / `pdo_mysql` (Database connectivity)
  - `php-zip`
- **Recommended `php.ini` Settings:**
  - `upload_max_filesize = 25M`
  - `post_max_size = 30M`
  - `memory_limit = 256M`
  - `max_execution_time = 60`

### 1.3 Database Server
- **Engine:** MySQL 8.0+ or MariaDB 10.5+
- **Charset:** `utf8mb4`
- **Collation:** `utf8mb4_unicode_ci`

---

## 2. Environment Variables (.env) Configuration

Create or update the production `.env` file with these exact keys:

```dotenv
# Application State
APP_NAME="DK Singh Fitness & Nutrition"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:p7RCElcCoV8uhxQOum3POJPPDGA5cZ0YDlEO5xYZT5c=

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dk_singh_fitness_prod
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_db_password

# Drivers (Verified Synchronous Architecture)
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

# Mail / SMTP Configuration (Production Transactional Mailer)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD="your_smtp_password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=support@yourdomain.com
MAIL_FROM_NAME="DK Singh Fitness"

# Razorpay LIVE Credentials (Do NOT use rzp_test_ keys in production)
RAZORPAY_KEY=rzp_live_xxxxxxxxxxxxxxxx
RAZORPAY_SECRET=your_live_razorpay_secret
```

> **Note on Queues:** All production-critical workflows (payments, order creation, courier waybill generation, email dispatch, and PDF generation) execute synchronously. Setting `QUEUE_CONNECTION=sync` eliminates the need for an external queue daemon while running 100% reliably.

---

## 3. Directory Permissions

Ensure the web server user (`www-data` or `nginx`) owns and can write to the necessary paths:

```bash
# Set ownership
sudo chown -R www-data:www-data /path/to/dk-singh-fitness

# Set permissions
sudo find /path/to/dk-singh-fitness -type f -exec chmod 644 {} \;
sudo find /path/to/dk-singh-fitness -type d -exec chmod 755 {} \;

# Storage and cache write permissions
sudo chmod -R 775 /path/to/dk-singh-fitness/storage
sudo chmod -R 775 /path/to/dk-singh-fitness/bootstrap/cache
```

---

## 4. Production Deployment Sequence (Commands)

Execute these commands in order during the deployment pipeline:

```bash
# 1. Pull latest verified release code
git pull origin main

# 2. Install production PHP dependencies (no dev dependencies, optimized classmap)
composer install --no-dev --prefer-dist --optimize-autoloader

# 3. Create public storage symlink (critical for media library and hero images)
php artisan storage:link

# 4. Run database migrations safely
php artisan migrate --force

# 5. Clear old runtime caches
php artisan optimize:clear

# 6. Build optimized production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Restart PHP-FPM to load clean OPcache
sudo systemctl reload php8.2-fpm
```

---

## 5. Web Server Configuration

### 5.1 Nginx Server Block Example

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    root /path/to/dk-singh-fitness/public;
    index index.php index.html;

    client_max_body_size 30M;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive hidden files (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static asset caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

---

## 6. Post-Deployment Verification Checklist

1. [ ] **Site Health:** Visit `https://yourdomain.com/` and confirm HTTP 200 with styled tokens.
2. [ ] **Security:** Verify that `https://yourdomain.com/.env` returns `403 Forbidden` or `404 Not Found`.
3. [ ] **Admin Login:** Log in at `https://yourdomain.com/admin/login` using your administrative account.
4. [ ] **Storage Link:** Verify hero banners, coach photos, and program thumbnails load at `/storage/media/...`.
5. [ ] **Courier Settings:** In `/admin/courier-providers`, verify your active Delhivery provider configuration.
6. [ ] **Payment Verification:** Perform a ₹1 live test checkout to confirm live Razorpay key exchange and callback.
7. [ ] **Email Delivery:** Submit the public contact form (`/contact`) and confirm receipt of confirmation email.
