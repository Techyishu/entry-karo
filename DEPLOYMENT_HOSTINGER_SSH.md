# Deployment Guide for Hostinger (SSH + Git)

This guide covers deploying **Entry Karo** to Hostinger using SSH and Git.

## Prerequisites
- SSH access to your Hostinger account
- Git installed on Hostinger (usually pre-installed)
- Database created in Hostinger hPanel

## Step 1: Prepare Your Git Repository

1. **Push your code to GitHub/GitLab/Bitbucket**:
   ```bash
   git add .
   git commit -m "Ready for deployment"
   git push origin main
   ```

2. **Make sure `.env` is in `.gitignore`** (it should be by default)

## Step 2: Database Setup

1. **Create Database in Hostinger**:
   - Go to hPanel → Databases → MySQL Databases
   - Create new database and user
   - Note down: Database Name, Username, Password
   
   The database will be empty initially - we'll run migrations later to create tables.

## Step 3: SSH into Hostinger

```bash
ssh u123456789@your-domain.com -p 65002
```

(Port may vary - check Hostinger documentation)

## Step 4: Clone Repository

**Recommended Approach**: Clone outside `public_html` for better security.

```bash
# Navigate to home directory
cd ~

# Clone your repository
git clone https://github.com/yourusername/entry-karo.git entry-karo

# Navigate to public_html
cd public_html

# Create symlinks to public folder contents
ln -s ~/entry-karo/public/* .
```

This keeps your application code (including `.env`, `app/`, `config/`) outside the web-accessible directory for better security.

## Step 5: Install Dependencies

```bash
cd ~/entry-karo  # or wherever you cloned

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# If Node.js is available (check with: node -v)
npm install
npm run build
```

**Note**: If Composer is not installed globally, you may need to use:
```bash
php composer.phar install --optimize-autoloader --no-dev
```

## Step 6: Configure Environment

```bash
# Copy example env file
cp .env.example .env

# Edit .env file
nano .env
```

Update these values:
```ini
APP_NAME="Entry Karo"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_entry_karo
DB_USERNAME=u123456789_user
DB_PASSWORD=your_password

# Generate a new key
APP_KEY=
```

## Step 7: Generate Application Key

```bash
php artisan key:generate
```

## Step 8: Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage
```

## Step 9: Run Artisan Commands

```bash
# Create storage link
php artisan storage:link

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create your first Super Admin user
php artisan tinker
```

In the Tinker console, run:
```php
$user = new App\Models\User();
$user->name = 'Your Name';
$user->email = 'admin@yourdomain.com';
$user->mobile_number = '9876543210';
$user->password = bcrypt('your-secure-password');
$user->role = 'super_admin';
$user->save();
exit
```

## Step 10: Test Your Application

Visit `https://yourdomain.com` and verify everything works!

## Future Deployments (Updates)

Create a deployment script for easy updates:

```bash
cd ~/entry-karo
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build  # if you have Node.js
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize:clear
```

Or use the included `deploy.sh` script:
```bash
chmod +x deploy.sh
./deploy.sh
```

## Troubleshooting

### Symlinks Not Working
If your hosting doesn't support symlinks, use this alternative approach:

1. **Clone directly into public_html**:
   ```bash
   cd ~/public_html
   git clone https://github.com/yourusername/entry-karo.git .
   ```

2. **Create `.htaccess` in `public_html` root**:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

3. **Protect sensitive files** by adding to the same `.htaccess`:
   ```apache
   # Deny access to sensitive files
   <FilesMatch "^\.env">
       Order allow,deny
       Deny from all
   </FilesMatch>
   ```

**Note**: This approach is less secure but works when symlinks are disabled.

### 500 Internal Server Error
- Check `storage/logs/laravel.log`
- Verify `.env` database credentials
- Ensure storage permissions: `chmod -R 775 storage`

### Images Not Showing
- Run `php artisan storage:link`
- Check file permissions

### Routes Not Working
- Verify `.htaccess` exists in public folder
- Check if mod_rewrite is enabled

### Database Connection Failed
- Verify database credentials in `.env`
- Check if database exists in phpMyAdmin
- Ensure database user has proper permissions

## Security Checklist

- ✅ `APP_DEBUG=false` in production
- ✅ `.env` file is not in Git repository
- ✅ Strong `APP_KEY` generated
- ✅ Database credentials are secure
- ✅ File permissions are correct (755/775, not 777)
- ✅ SSL certificate is active (HTTPS)
