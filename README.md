# Entry Karo - Visitor Management System

A comprehensive visitor management system built with Laravel 12, designed for efficient entry tracking, visitor registration, and security management.

## Features

### 🔐 Role-Based Access Control
- **Super Admin**: Full system access, user management, entry deletion
- **Customer**: View entries, manage guards, cannot delete entries
- **Guard**: Entry check-in/check-out, visitor registration, carry item tracking

### 👥 Visitor Management
- Mobile number-based visitor identification
- Quick visitor registration
- Photo capture and storage
- Vehicle number tracking
- Purpose of visit logging

### 📋 Entry Tracking
- Real-time check-in/check-out
- Automatic duration calculation
- Entry history and analytics
- Detailed entry reports

### 📦 Carry Items Management
- Track items brought in by visitors
- Photo documentation of carry items
- Item-level tracking per entry

### 🎯 Guard Dashboard
- Streamlined entry interface
- Quick visitor search by mobile number
- Inline visitor registration
- Entry list with filters

## Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2
- **Database**: PostgreSQL (production) / SQLite (development)
- **Frontend**: Blade Templates, Vite
- **Deployment**: Docker, Render
- **Server**: Apache

## Quick Start

### Local Development

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd entry-karo
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations**
   ```bash
   php artisan migrate
   ```

5. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

8. **Create a super admin user**
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   $user = new App\Models\User();
   $user->name = 'Admin';
   $user->email = 'admin@example.com';
   $user->mobile_number = '1234567890';
   $user->password = Hash::make('password');
   $user->role = 'super_admin';
   $user->save();
   exit
   ```

## Deployment to Render

### Prerequisites
- Render account
- Git repository (GitHub/GitLab/Bitbucket)

### Quick Deploy

1. **Push code to Git**
   ```bash
   git add .
   git commit -m "Deploy to Render"
   git push origin main
   ```

2. **Deploy on Render**
   - Go to [Render Dashboard](https://dashboard.render.com)
   - Click "New +" → "Blueprint"
   - Connect your repository
   - Render will auto-detect `render.yaml` and deploy

3. **Access your app**
   - Your app will be live at: `https://your-app-name.onrender.com`

For detailed deployment instructions, see [RENDER_DEPLOYMENT.md](RENDER_DEPLOYMENT.md)

## Documentation

- **[Deployment Guide](RENDER_DEPLOYMENT.md)** - Complete Render deployment instructions
- **[Deployment Checklist](DEPLOYMENT_CHECKLIST.md)** - Pre and post-deployment checklist
- **[Environment Variables](RENDER_ENV_VARS.md)** - Quick reference for Render env vars
- **[Database Schema](DATABASE_SCHEMA.md)** - Database structure and relationships
- **[Access Control](ACCESS_CONTROL_SUMMARY.md)** - Role-based permissions
- **[Security Audit](SECURITY_AUDIT_REPORT.md)** - Security implementation details

## Project Structure

```
entry-karo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Customer/       # Customer controllers
│   │   │   └── Guard/          # Guard controllers
│   │   └── Middleware/         # Role-based middleware
│   └── Models/                 # Eloquent models
├── database/
│   └── migrations/             # Database migrations
├── resources/
│   └── views/                  # Blade templates
├── routes/
│   └── web.php                 # Application routes
├── storage/
│   └── app/public/             # File uploads
├── Dockerfile                  # Docker configuration
├── render.yaml                 # Render deployment config
└── render-build.sh             # Build script
```

## Security Features

- ✅ CSRF protection enabled
- ✅ Role-based access control
- ✅ Password hashing (bcrypt)
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade templating)
- ✅ Security headers configured
- ✅ Environment-based configuration
- ✅ No hardcoded credentials

## Testing

Run the test suite:
```bash
php artisan test
```

Test Docker build locally:
```bash
./test-docker.sh
```

## License

This project is proprietary software. All rights reserved.

## Support

For issues or questions, please contact the development team.

---

**Built with ❤️ using Laravel**
