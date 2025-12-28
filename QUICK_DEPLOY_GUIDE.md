# Quick Deploy Guide - Entry Karo on Render

## 🚀 Your Database is Ready!

Your PostgreSQL database has been created on Render:
- **Database Name**: entry_karo
- **User**: entry_karo_user
- **Host**: dpg-d58ldlili9vc73a55bng-a

## 📋 Step-by-Step Deployment

### Step 1: Set Environment Variables in Render

Go to your Render web service dashboard and add these environment variables:

```bash
# Application Settings
APP_NAME=Entry Karo
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:CVgcBVwrVfqNka3AnNk3H3/lztN2za8yp6S1WVezEfI=
APP_URL=https://your-app-name.onrender.com

# Database (IMPORTANT: Use the Internal Database URL)
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://entry_karo_user:APlrtyae1NSPIpaVhpkOC75TFHqSBHSP@dpg-d58ldlili9vc73a55bng-a/entry_karo

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=error

# Storage & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

# Security
BCRYPT_ROUNDS=12
```

### Step 2: Deploy Your Application

#### Option A: Using render.yaml (Automatic)
1. Push your code to Git:
   ```bash
   git add .
   git commit -m "Deploy to Render"
   git push origin main
   ```

2. In Render Dashboard:
   - Click "New +" → "Blueprint"
   - Connect your repository
   - Render will auto-detect `render.yaml`
   - Click "Apply"

#### Option B: Manual Deployment
1. In Render Dashboard:
   - Click "New +" → "Web Service"
   - Connect your repository
   - **Runtime**: Docker
   - **Plan**: Free
   - Add environment variables from Step 1
   - Click "Create Web Service"

### Step 3: Wait for Build

The build process will:
- ✅ Build Docker image
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Create storage symlink
- ✅ Cache configuration

This takes about 3-5 minutes on first deploy.

### Step 4: Create Super Admin User

Once deployed, go to your web service → **Shell** tab and run:

```bash
php artisan tinker
```

Then paste this:

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@entrykaro.com';
$user->mobile_number = '9999999999';
$user->password = Hash::make('Admin@123');
$user->role = 'super_admin';
$user->save();
echo "Super admin created successfully!\n";
exit
```

**Save these credentials:**
- Email: `admin@entrykaro.com`
- Password: `Admin@123`

⚠️ **Change the password after first login!**

### Step 5: Test Your Application

1. **Health Check**:
   - Visit: `https://your-app-name.onrender.com/up`
   - Should show: "OK"

2. **Login**:
   - Visit: `https://your-app-name.onrender.com/login`
   - Use super admin credentials
   - You should see the admin dashboard

3. **Test Features**:
   - ✅ Create a guard user
   - ✅ Login as guard
   - ✅ Register a visitor
   - ✅ Check-in visitor
   - ✅ Upload carry item photo
   - ✅ Check-out visitor

## 🎯 Important Notes

### Database URL Format
```
postgresql://username:password@host/database
```

Your database URL breakdown:
- **Protocol**: postgresql://
- **Username**: entry_karo_user
- **Password**: APlrtyae1NSPIpaVhpkOC75TFHqSBHSP
- **Host**: dpg-d58ldlili9vc73a55bng-a
- **Database**: entry_karo

### Internal vs External URL
- **Internal URL** (use this): `dpg-d58ldlili9vc73a55bng-a`
- **External URL**: Has `.oregon-postgres.render.com` or similar
- Always use the **Internal URL** for your web service!

### Free Tier Limitations
- Services spin down after 15 minutes of inactivity
- First request after spin-down takes 30-60 seconds (cold start)
- Database limited to 1GB storage
- 90 days of inactivity = automatic deletion

## 🔧 Troubleshooting

### Issue: "No application encryption key"
**Solution**: Make sure APP_KEY is set in environment variables
```bash
APP_KEY=base64:CVgcBVwrVfqNka3AnNk3H3/lztN2za8yp6S1WVezEfI=
```

### Issue: Database connection failed
**Solution**: 
1. Verify DATABASE_URL is correct
2. Make sure you're using the **Internal** database URL
3. Check database status is "Available"

### Issue: 500 Error
**Solution**:
1. Check Render logs (Logs tab in dashboard)
2. Temporarily set `APP_DEBUG=true` to see error details
3. Verify migrations ran successfully
4. Check storage permissions

### Issue: Images not uploading
**Solution**:
1. Verify `FILESYSTEM_DISK=public` is set
2. Check storage symlink was created during build
3. Look for errors in logs

## 📊 Post-Deployment Checklist

- [ ] Application is accessible
- [ ] Health check passes (/up)
- [ ] Super admin can login
- [ ] Admin dashboard loads
- [ ] Can create guards
- [ ] Guard can login
- [ ] Guard can register visitors
- [ ] Guard can check-in/check-out
- [ ] File uploads work
- [ ] Images display correctly
- [ ] No errors in logs

## 🎨 Demo Preparation

Before showing to client:

1. **Create Sample Data**:
   - Create 2-3 guard users
   - Register 5-10 sample visitors
   - Create some entry records
   - Add carry items with photos

2. **Test All Roles**:
   - Login as super admin
   - Login as guard
   - Test all features

3. **Clean Up**:
   - Remove test data if needed
   - Clear logs
   - Verify professional appearance

## 🔐 Security Reminders

- ✅ APP_DEBUG is false
- ✅ Strong APP_KEY is set
- ✅ Database password is secure
- ✅ No .env file in Git
- ✅ HTTPS enabled (automatic on Render)

## 📞 Need Help?

- **Render Logs**: Dashboard → Your Service → Logs tab
- **Laravel Logs**: Shell → `tail -f storage/logs/laravel.log`
- **Database**: Dashboard → Your Database → Info tab

## ✨ Your App URL

After deployment, your app will be available at:
```
https://your-app-name.onrender.com
```

Update the `APP_URL` environment variable to match this URL!

---

**Status**: Ready to Deploy! 🚀  
**Database**: Connected ✅  
**APP_KEY**: Generated ✅  
**Documentation**: Complete ✅

Good luck with your deployment! 🎉
