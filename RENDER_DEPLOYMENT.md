# Render Deployment Guide

This guide will help you deploy the Entry Karo Laravel application to Render using Docker and PostgreSQL.

## Prerequisites

- A Render account (sign up at https://render.com)
- Git repository with this code pushed to GitHub/GitLab/Bitbucket

## Deployment Steps

### Option 1: Using render.yaml (Recommended)

1. **Push your code to a Git repository**
   ```bash
   git add .
   git commit -m "Prepare for Render deployment"
   git push origin main
   ```

2. **Connect to Render**
   - Go to https://dashboard.render.com
   - Click "New +" → "Blueprint"
   - Connect your repository
   - Render will automatically detect `render.yaml` and create:
     - PostgreSQL database (entry-karo-db)
     - Web service (entry-karo)

3. **Wait for deployment**
   - Render will automatically build and deploy your application
   - The build process will:
     - Install dependencies
     - Generate APP_KEY
     - Run migrations
     - Create storage symlink
     - Cache configuration

4. **Access your application**
   - Your app will be available at: `https://entry-karo.onrender.com`
   - Update the `APP_URL` environment variable to match your actual URL

### Option 2: Manual Setup

1. **Create PostgreSQL Database**
   - Go to Render Dashboard
   - Click "New +" → "PostgreSQL"
   - Name: `entry-karo-db`
   - Plan: Free
   - Click "Create Database"
   - Copy the "Internal Database URL"

2. **Create Web Service**
   - Click "New +" → "Web Service"
   - Connect your repository
   - Configure:
     - **Name**: entry-karo
     - **Runtime**: Docker
     - **Plan**: Free
     - **Health Check Path**: /

3. **Configure Environment Variables**
   Add these in the Render dashboard:
   
   ```
   APP_NAME=Entry Karo
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:XXXXX  (generate with: php artisan key:generate --show)
   APP_URL=https://your-app.onrender.com
   
   LOG_CHANNEL=errorlog
   LOG_LEVEL=error
   
   DB_CONNECTION=pgsql
   DATABASE_URL=<paste internal database URL>
   
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   FILESYSTEM_DISK=public
   
   BCRYPT_ROUNDS=12
   ```

4. **Deploy**
   - Click "Create Web Service"
   - Render will build and deploy your application

## Post-Deployment

### Create Super Admin User

After deployment, you need to create a super admin user to access the admin panel:

1. **Access Render Shell**
   - Go to your web service in Render dashboard
   - Click "Shell" tab
   - Run:
   ```bash
   php artisan tinker
   ```

2. **Create Super Admin**
   ```php
   $user = new App\Models\User();
   $user->name = 'Admin';
   $user->email = 'admin@example.com';
   $user->mobile_number = '1234567890';
   $user->password = Hash::make('your-secure-password');
   $user->role = 'super_admin';
   $user->save();
   exit
   ```

### Verify Deployment

1. **Check Health**
   - Visit: `https://your-app.onrender.com/up`
   - Should return "OK"

2. **Test Login**
   - Visit: `https://your-app.onrender.com/login`
   - Login with super admin credentials

3. **Check Database**
   - Ensure migrations ran successfully
   - Verify tables exist

## Troubleshooting

### Application Key Error
If you see "No application encryption key has been specified":
- Ensure `APP_KEY` is set in environment variables
- Generate a new key: `php artisan key:generate --show`
- Add it to Render environment variables

### Database Connection Error
- Verify `DATABASE_URL` is correctly set
- Check database is in "Available" status
- Ensure you're using the "Internal Database URL"

### Storage/Upload Issues
- Ensure `FILESYSTEM_DISK=public` is set
- Verify storage symlink was created during build
- Check file permissions in logs

### 500 Internal Server Error
- Set `APP_DEBUG=true` temporarily to see error details
- Check logs in Render dashboard
- Verify all migrations ran successfully

## Important Notes

1. **Free Tier Limitations**
   - Services spin down after 15 minutes of inactivity
   - First request after spin-down will be slow (cold start)
   - Database has 1GB storage limit

2. **File Uploads**
   - Files are stored in `/storage/app/public`
   - Uploaded files will persist on Render's disk
   - For production, consider using S3 or similar

3. **Security**
   - Never commit `.env` file
   - Always use strong passwords
   - Keep `APP_DEBUG=false` in production
   - Regularly update dependencies

4. **Scaling**
   - Upgrade to paid plan for:
     - No spin-down
     - More resources
     - Custom domains
     - SSL certificates

## Updating the Application

To deploy updates:

```bash
git add .
git commit -m "Your update message"
git push origin main
```

Render will automatically detect changes and redeploy.

## Support

For issues:
- Check Render logs in dashboard
- Review Laravel logs: `storage/logs/laravel.log`
- Consult Render documentation: https://render.com/docs
