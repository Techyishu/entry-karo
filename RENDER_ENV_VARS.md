# Render Environment Variables - Quick Reference

Copy and paste these into your Render dashboard under "Environment" tab.

## Required Variables

```bash
# Application
APP_NAME=Entry Karo
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_THIS_WITH_php_artisan_key:generate_--show
APP_URL=https://your-app-name.onrender.com

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=error

# Database (Render provides DATABASE_URL automatically)
DB_CONNECTION=pgsql
# DATABASE_URL will be auto-populated by Render when you link the database

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# File Storage
FILESYSTEM_DISK=public

# Security
BCRYPT_ROUNDS=12
```

## How to Set APP_KEY

### Option 1: Generate Locally (Recommended)
```bash
# In your local terminal
php artisan key:generate --show
```
Copy the output (e.g., `base64:xxxxx...`) and paste into Render's APP_KEY variable.

### Option 2: Generate in Render Shell (After First Deploy)
```bash
# In Render Shell
php artisan key:generate --show
```
Then update the APP_KEY environment variable in Render dashboard and redeploy.

## How to Link Database

1. Create PostgreSQL database in Render
2. In your web service, add environment variable:
   - **Key**: `DATABASE_URL`
   - **Value**: Click "Insert from database" → Select your database → Choose "Internal Connection String"

## Optional Variables (for future use)

```bash
# Mail (if you add email functionality)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@entrykaro.com
MAIL_FROM_NAME="${APP_NAME}"

# AWS S3 (if you want to use S3 for file storage)
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
FILESYSTEM_DISK=s3
```

## Verification

After setting all variables:
1. Click "Save Changes"
2. Render will automatically redeploy
3. Check logs for any errors
4. Visit `/up` endpoint to verify health

## Notes

- **Never commit** these values to Git
- **Always use** strong, random APP_KEY
- **Update APP_URL** to match your actual Render URL
- **DATABASE_URL** is automatically provided by Render when you link a database
- Free tier databases spin down after inactivity - first request may be slow
