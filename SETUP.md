# Entry Karo - Visitor Entry System

A Laravel 12-based visitor entry management system with role-based access control.

## Project Setup

This project has been configured with:

- **Laravel 12** - Latest version of the framework
- **Blade Templates** - No Vue, React, or Inertia
- **MySQL Database** - Configured for MySQL connection
- **Default Authentication** - Login/logout with Blade views
- **No Jetstream/Breeze** - Clean setup without extra UI packages
- **Role-Based Access** - Prepared for admin, guard, and customer roles

## Installation & Setup

### 1. Database Configuration

Update your MySQL credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=entry_karo
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 2. Create Database

```bash
mysql -u root -p
CREATE DATABASE entry_karo;
exit;
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Seed Test Users

```bash
php artisan db:seed --class=UserSeeder
```

This will create three test users:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@entrykaro.com | password |
| Guard | guard@entrykaro.com | password |
| Customer | customer@entrykaro.com | password |

### 5. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Project Structure

### Routes

- `routes/web.php` - Main web routes
  - `/login` - Login form
  - `/admin/dashboard` - Admin dashboard (admin role only)
  - `/guard/dashboard` - Guard dashboard (guard role only)
  - `/customer/dashboard` - Customer dashboard (customer role only)

### Controllers

- `app/Http/Controllers/Auth/LoginController.php` - Authentication logic
- `app/Http/Controllers/Admin/DashboardController.php` - Admin dashboard
- `app/Http/Controllers/Guard/DashboardController.php` - Guard dashboard
- `app/Http/Controllers/Customer/DashboardController.php` - Customer dashboard

### Middleware

- `app/Http/Middleware/CheckRole.php` - Role-based access control
  - Registered as `role` middleware
  - Usage: `->middleware('role:admin')`

### Views

- `resources/views/layouts/app.blade.php` - Main layout template
- `resources/views/auth/login.blade.php` - Login form
- `resources/views/admin/dashboard.blade.php` - Admin dashboard
- `resources/views/guard/dashboard.blade.php` - Guard dashboard
- `resources/views/customer/dashboard.blade.php` - Customer dashboard

### Models

- `app/Models/User.php` - Extended with role helper methods:
  - `hasRole($role)` - Check if user has specific role
  - `isAdmin()` - Check if user is admin
  - `isGuard()` - Check if user is guard
  - `isCustomer()` - Check if user is customer

### Database

- `database/migrations/0001_01_01_000000_create_users_table.php` - Users table with `role` column
- `database/seeders/UserSeeder.php` - Seed initial users

## Role-Based Access

The system supports three user roles:

1. **Admin** - Full system access
   - Can view admin dashboard
   - Manages visitors, guards, and customers

2. **Guard** - Front-line staff
   - Can view guard dashboard
   - Check-in/check-out visitors

3. **Customer** - End users
   - Can view customer dashboard
   - View visit history

### Using Role Middleware

Protect routes by adding the role middleware:

```php
Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Admin-only routes
    });

    Route::middleware('role:guard')->prefix('guard')->group(function () {
        // Guard-only routes
    });

    Route::middleware('role:customer')->prefix('customer')->group(function () {
        // Customer-only routes
    });
});
```

## Next Steps

This is a clean setup ready for feature development. The following can be built next:

- Visitor management (CRUD operations)
- Check-in/check-out system
- Visitor history tracking
- Guard assignment to gates
- Customer self-service features
- Reporting and analytics

## Key Features Ready

✅ Laravel 12 with Blade templates
✅ MySQL database connection
✅ Authentication system (login/logout)
✅ Role-based access control (admin/guard/customer)
✅ Role middleware
✅ Organized folder structure
✅ Clean UI with Tailwind CSS
✅ User seeder for testing

## Notes

- The project uses Tailwind CSS via CDN for simplicity
- All views use server-side rendering with Blade
- No JavaScript frameworks are used
- Database sessions are enabled
- CSRF protection is enabled

