# Deployment & Setup Guide

This guide provides comprehensive instructions for deploying and setting up the SOHMC Music School system in various environments, from local development to production servers.

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Local Development Setup](#local-development-setup)
3. [Database Setup](#database-setup)
4. [Environment Configuration](#environment-configuration)
5. [Asset Building](#asset-building)
6. [Production Deployment](#production-deployment)
7. [Server Configuration](#server-configuration)
8. [Monitoring & Maintenance](#monitoring--maintenance)
9. [Troubleshooting](#troubleshooting)

---

## System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Composer**: 2.0 or higher
- **Node.js**: 18.0 or higher
- **NPM**: 9.0 or higher
- **Database**: MySQL 5.7+ / PostgreSQL 9.6+ / SQLite 3.8+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **PHP Extensions**: 
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
  - GD (for image processing)
  - Zip (for file archives)

### Recommended Requirements
- **PHP**: 8.3 or higher
- **MySQL**: 8.0+ / PostgreSQL 14+
- **RAM**: 2GB minimum, 4GB recommended
- **Storage**: 10GB minimum SSD
- **SSL Certificate**: For secure connections

---

## Local Development Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd SOHMC-Music-School
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sohmc_music_school
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database

```bash
php artisan db:seed
```

### 8. Build Assets

```bash
npm run build
```

### 9. Start Development Server

```bash
# Terminal 1: PHP server
php artisan serve

# Terminal 2: Vite dev server (for hot reload)
npm run dev
```

### 10. Access Application

Open your browser and navigate to:
- **Application**: `http://localhost:8000`
- **Admin**: `http://localhost:8000/admin`
- **Teacher Dashboard**: `http://localhost:8000/teacher/dashboard`
- **Student Dashboard**: `http://localhost:8000/student/dashboard`

---

## Database Setup

### MySQL Setup

#### Create Database

```sql
CREATE DATABASE sohmc_music_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sohmc_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON sohmc_music_school.* TO 'sohmc_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Configure Laravel

Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sohmc_music_school
DB_USERNAME=sohmc_user
DB_PASSWORD=secure_password
```

### PostgreSQL Setup

#### Create Database

```sql
CREATE DATABASE sohmc_music_school;
CREATE USER sohmc_user WITH PASSWORD 'secure_password';
GRANT ALL PRIVILEGES ON DATABASE sohmc_music_school TO sohmc_user;
```

#### Configure Laravel

Update `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sohmc_music_school
DB_USERNAME=sohmc_user
DB_PASSWORD=secure_password
```

### SQLite Setup (Development Only)

#### Create Database File

```bash
touch database/database.sqlite
```

#### Configure Laravel

Update `.env` file:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database/database.sqlite
```

---

## Environment Configuration

### Essential Environment Variables

```env
APP_NAME="SOHMC Music School"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=daily
LOG_LEVEL=debug

DB_CONNECTION=mysql
# ... database configuration ...

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Production Environment Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=warning

# Production database credentials
# Stronger caching
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Production email settings
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
```

### File System Configuration

```env
# Local storage
FILESYSTEM_DISK=local

# Public storage (for uploaded files)
FILESYSTEM_DISK=public

# S3 for production (optional)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
AWS_URL=https://your-bucket.s3.amazonaws.com
```

---

## Asset Building

### Development Build

```bash
npm run dev
```

**Features**:
- Hot module replacement
- Source maps for debugging
- Fast rebuild times
- Development-friendly error messages

### Production Build

```bash
npm run build
```

**Features**:
- Minified CSS and JavaScript
- Optimized for performance
- Smaller file sizes
- Production-ready output

### Build Configuration

The build process is configured in `vite.config.js`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

### Custom Build Scripts

Add custom scripts to `package.json`:

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "build:production": "vite build --mode production",
    "watch": "vite build --watch"
  }
}
```

---

## Production Deployment

### 1. Server Preparation

#### Update System Packages

```bash
# Ubuntu/Debian
sudo apt update
sudo apt upgrade

# CentOS/RHEL
sudo yum update
```

#### Install Required PHP Extensions

```bash
# Ubuntu/Debian
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd

# CentOS/RHEL
sudo yum install php php-fpm php-mysqlnd php-mbstring php-xml php-bcmath php-curl php-zip php-gd
```

#### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Install Node.js and NPM

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Deploy Application Files

#### Upload Files

```bash
# Using SCP
scp -r /local/path/SOHMC-Music-School user@server:/var/www/html/

# Using Git (recommended)
cd /var/www/html/
git clone <repository-url>
cd SOHMC-Music-School
```

#### Set Permissions

```bash
# Storage directory
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Entire application (more permissive)
sudo chown -R www-data:www-data /var/www/html/SOHMC-Music-School
sudo chmod -R 755 /var/www/html/SOHMC-Music-School
```

### 3. Install Dependencies

```bash
# Install Composer dependencies (optimized for production)
composer install --optimize-autoloader --no-dev

# Install NPM dependencies
npm install --production
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
nano .env  # Edit with production values
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

### 6. Build Assets

```bash
npm run build
```

### 7. Additional Setup Commands

```bash
# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### 8. Web Server Configuration

#### Apache Configuration

Create virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/html/SOHMC-Music-School/public

    <Directory /var/www/html/SOHMC-Music-School/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Enable mod_rewrite:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx Configuration

Create server block:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/SOHMC-Music-School/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Test and restart Nginx:

```bash
sudo nginx -t
sudo systemctl restart nginx
```

### 9. SSL Configuration (HTTPS)

#### Using Let's Encrypt

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal is configured automatically
```

#### Manual SSL Configuration

Update Nginx configuration:

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # ... rest of configuration
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

---

## Server Configuration

### PHP Configuration

Edit `php.ini`:

```ini
; Memory limit
memory_limit = 256M

; Maximum execution time
max_execution_time = 300

; Maximum upload size
upload_max_filesize = 20M
post_max_size = 20M

; Timezone
date.timezone = UTC

; Error reporting (production)
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
```

### Database Configuration

#### MySQL Optimization

Edit `my.cnf`:

```ini
[mysqld]
# InnoDB settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2

# Query cache (MySQL 5.7 and below)
query_cache_type = 1
query_cache_size = 64M

# Connection settings
max_connections = 200
```

#### PostgreSQL Optimization

Edit `postgresql.conf`:

```conf
# Memory settings
shared_buffers = 256MB
effective_cache_size = 1GB
maintenance_work_mem = 64MB

# Connection settings
max_connections = 200

# Query optimization
random_page_cost = 1.1
effective_io_concurrency = 200
```

### Queue Configuration

For production, use Redis or database queue:

```env
QUEUE_CONNECTION=redis
```

Install Redis:

```bash
sudo apt install redis-server
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

Run queue worker:

```bash
php artisan queue:work --tries=3 --timeout=90
```

### Supervisor Configuration (for Queue Workers)

Install Supervisor:

```bash
sudo apt install supervisor
```

Create configuration file `/etc/supervisor/conf.d/sohmc-worker.conf`:

```ini
[program:sohmc-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/SOHMC-Music-School/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/SOHMC-Music-School/storage/logs/worker.log
stopwaitsecs=3600
```

Start Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sohmc-worker:*
```

---

## Monitoring & Maintenance

### 1. Log Management

#### Laravel Logs

```bash
# View logs
tail -f storage/logs/laravel.log

# Clear logs
rm storage/logs/*.log
```

#### Log Rotation

Configure log rotation in `/etc/logrotate.d/sohmc`:

```
/var/www/html/SOHMC-Music-School/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 2. Database Backups

#### Manual Backup

```bash
# MySQL
mysqldump -u username -p database_name > backup.sql

# PostgreSQL
pg_dump -U username database_name > backup.sql
```

#### Automated Backups (using Spatie Backup)

The system includes Spatie Laravel Backup. Configure in `config/backup.php`:

```php
'disks' => [
    'local',
    's3', // Add your S3 configuration
],

'backup' => [
    'name' => env('APP_NAME', 'laravel-backup'),
    'source' => [
        'files' => [
            'include' => [
                base_path(),
            ],
            'exclude' => [
                base_path('vendor'),
                base_path('node_modules'),
            ],
        ],
        'databases' => [
            'mysql',
        ],
    ],
    'destination' => [
        'filename_prefix' => '',
        'disks' => [
            'local',
            's3',
        ],
    ],
],
```

Run backup:

```bash
php artisan backup:run
```

Schedule automatic backups in `app/Console/Kernel.php`:

```php
$schedule->command('backup:run')->daily()->at('02:00');
```

### 3. System Monitoring

#### Server Monitoring Tools

- **Uptime monitoring**: UptimeRobot, Pingdom
- **Performance monitoring**: New Relic, Datadog
- **Error tracking**: Sentry, Bugsnag
- **Log aggregation**: ELK Stack, Papertrail

#### Laravel Telescope (Development)

Install Telescope:

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Access at `/telescope`.

### 4. Performance Optimization

#### Enable OPcache

Edit `php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

#### Enable Redis Caching

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

#### Database Query Optimization

```bash
# Analyze slow queries
php artisan telescope:dump

# Use Laravel Debugbar in development
composer require barryvdh/laravel-debugbar --dev
```

### 5. Security Hardening

#### File Permissions

```bash
# Secure sensitive files
chmod 600 .env
chmod 600 storage/oauth-*.key

# Secure directories
chmod 755 bootstrap/cache
chmod 755 storage/framework
```

#### Firewall Configuration

```bash
# Ubuntu UFW
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

#### Regular Updates

```bash
# System updates
sudo apt update && sudo apt upgrade

# Laravel updates
composer update
npm update
```

---

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error

**Check logs**:
```bash
tail -f storage/logs/laravel.log
```

**Common causes**:
- Missing PHP extensions
- Incorrect file permissions
- Database connection issues
- Missing environment variables

#### 2. Database Connection Issues

**Test connection**:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

**Check credentials** in `.env` file.

#### 3. Asset Loading Issues

**Clear cache**:
```bash
php artisan view:clear
php artisan cache:clear
npm run build
```

**Check public path**:
```bash
php artisan storage:link
```

#### 4. Queue Jobs Not Processing

**Check queue worker**:
```bash
php artisan queue:work --tries=3
```

**Clear failed jobs**:
```bash
php artisan queue:flush
```

#### 5. Permission Denied Errors

**Fix permissions**:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 6. Memory Limit Issues

**Increase PHP memory limit**:
```ini
memory_limit = 512M
```

**Increase composer memory**:
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### Debug Mode

Enable debug mode in `.env`:

```env
APP_DEBUG=true
```

**⚠️ Never enable debug mode in production!**

### Health Checks

Create health check endpoint:

```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::get('health_check', 'working'),
    ]);
});
```

Test with:
```bash
curl https://your-domain.com/health
```

---

## Deployment Checklist

### Pre-Deployment
- [ ] Backup current application
- [ ] Backup database
- [ ] Test migrations locally
- [ ] Review environment variables
- [ ] Update dependencies
- [ ] Run tests

### Deployment
- [ ] Upload application files
- [ ] Set correct permissions
- [ ] Install dependencies
- [ ] Configure environment
- [ ] Run migrations
- [ ] Build assets
- [ ] Clear caches
- [ ] Restart services

### Post-Deployment
- [ ] Test core functionality
- [ ] Verify database connections
- [ ] Check email functionality
- [ ] Test file uploads
- [ ] Monitor error logs
- [ ] Test user authentication
- [ ] Verify cron jobs
- [ ] Test queue workers
- [ ] Monitor performance

---

## Support & Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [FullCalendar Documentation](https://fullcalendar.io/docs)

### Community Support
- [Laravel Forums](https://laracasts.com/discuss)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [GitHub Issues](https://github.com/laravel/laravel/issues)

### Emergency Contacts
- System Administrator
- Database Administrator
- DevOps Team

---

**Last Updated**: August 19, 2026  
**Version**: 1.0  
**Status**: Production Ready