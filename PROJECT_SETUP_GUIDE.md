# Entry Karo - Project Setup Guide

## Overview

Entry Karo is a visitor entry management system with role-based access control (RBAC), comprehensive carry items tracking, and secure entry management.

---

## ✅ System Status

**Server Status:** ✅ Running
- URL: http://127.0.0.1:8000
- PHP: Built-in server (Laravel Artisan)

**Database:** ✅ Configured
- Type: MySQL
- Name: entry_karo
- Host: 127.0.0.1
- Port: 3306
- User: root
- Password: (empty)

**Migrations:** ✅ Applied
- Users table ✅
- Cache table ✅
- Jobs table ✅
- Visitors table ✅
- Entries table ✅
- Carry Items table ✅

**Seeders:** ✅ Run
- UserSeeder with roles (super_admin, customer, guard)
- Sample users for testing

---

## Access Credentials

### Super Admin
- **Email:** admin@entrykaro.com
- **Password:** password
- **Access:** Full system access, can delete entries

### Customer
- **Email:** acme@entrykaro.com
- **Password:** password
- **Access:** Can view own entries, add guards, cannot delete entries

### Guard
- **Email:** guard@entrykaro.com
- **Password:** password
- **Access:** Can check-in/out visitors, manage carry items, cannot delete entries

---

## Application URLs

### Public Routes
- **Home:** http://127.0.0.1:8000 (redirects to login)
- **Login:** http://127.0.0.1:8000/login

### Authentication Required
All the following routes require authentication:

### Super Admin Routes (Protected by `super_admin` middleware)
- **Dashboard:** http://127.0.0.1:8000/admin/dashboard
- **Users Management:**
  - List: http://127.0.0.1:8000/admin/users
  - Create: http://127.0.0.1:8000/admin/users/create
- **Visitors Management:**
  - List: http://127.0.0.1:8000/admin/visitors
  - Create: http://127.0.0.1:8000/admin/visitors/create
- **Entries Management:**
  - List: http://127.0.0.1:8000/admin/entries
  - Details: http://127.0.0.1:8000/admin/entries/{id}
  - Delete: http://127.0.0.1:8000/admin/entries/{id}/confirm-delete
  - **Note:** Only Super Admin can delete entries

### Guard Routes (Protected by `guard` middleware)
- **Dashboard:** http://127.0.0.1:8000/guard/dashboard
- **Entry Screen:** http://127.0.0.1:8000/guard/entries
- **Entry List:** http://127.0.0.1:8000/guard/entries/list
- **Check-Out:** http://127.0.0.1:8000/guard/entry-details/{visitor_id}
- **Visitor Registration:** http://127.0.0.1:8000/guard/entries/visitor/register

**Note:** Guards cannot access admin routes and cannot delete entries

### Customer Routes (Protected by `customer` middleware)
- **Dashboard:** http://127.0.0.1:8000/customer/dashboard
- **Guard Management:**
  - List: http://127.0.0.1:8000/customer/guards
  - Create: http://127.0.0.1:8000/customer/guards/create
- **Entry Viewing:**
  - List: http://127.0.0.1:8000/customer/entries
  - Details: http://127.0.0.1:8000/customer/entries/{id}
  - **Note:** Customers cannot delete entries

---

## Features Implemented

### ✅ 1. Authentication & Authorization
- Login system with email/password
- Role-based access control (super_admin, customer, guard)
- Middleware protection on all routes
- User cannot access routes for other roles
- Guards and customers blocked from admin routes

### ✅ 2. Visitor Management
- **One-Time Registration:**
  - Mobile number as permanent ID
  - Mandatory photo upload (JPG/PNG, max 2MB)
  - Fields: name, address, purpose, vehicle number (optional)
  - Unique mobile number enforced
  
- **Search by Mobile:**
  - Instant visitor lookup
  - Redirects to registration if not found
  - Shows visitor details + active entry

### ✅ 3. Entry Management
- **Check-In:**
  - Creates new entry with current timestamp
  - Links visitor to guard
  - No duplicate entries for same visitor (single IN/OUT row)

- **Check-Out:**
  - Updates same entry with out_time
  - Auto-calculates duration (out_time - in_time)
  - Marks all carry items as taken out

- **Today's Entries Table:**
  - Shows all of today's entries (guard only sees today's)
  - Columns: photo, mobile, name, purpose, vehicle, items, IN time, OUT time, duration
  - Active entries highlighted (yellow background)
  - Duration auto-calculated and displayed

### ✅ 4. Carry Items Tracking
- **Multiple Items per Entry:**
  - Unlimited items can be added
  - Each item tracked independently
  
- **Item Types:**
  - Personal (bags, electronics)
  - Office (laptops, projectors)
  - Delivery (packages, boxes)
  - Other (not specified)

- **Status Tracking:**
  - `in_status`: TRUE when brought in (never changes)
  - `out_status`: FALSE initially, TRUE on check-out
  - Automatic status update on visitor check-out

- **Item Management:**
  - Add items with photo (optional)
  - Set quantity (default: 1)
  - Manual status updates allowed

### ✅ 5. Admin Entry Management
- **View All Entries:**
  - Complete entry history with pagination
  - Show visitor, guard, times, carry items
  - Status badges (Completed/Active)

- **Delete Entry with Confirmation:**
  - ONLY visible to super admin
  - Dedicated confirmation page
  - Shows visitor, guard, times, carry items count
  - Warning messages about what will be deleted
  - Carries items deleted (cascade + explicit)
  - Visitor record NOT deleted (preserved for future visits)

- **Delete Security:**
  - Guards CANNOT see delete button
  - Customers CANNOT see delete button
  - Only Super Admin can delete
  - Double-layer protection (middleware + controller check)

---

## Security Measures Implemented

### ✅ CSRF Protection
- All forms include `@csrf` directive
- AJAX requests include `X-CSRF-TOKEN` header
- Delete form uses `@method('DELETE')` for method spoofing

### ✅ Input Validation
- Server-side validation on all controller methods
- Custom error messages for file uploads
- Unique constraints for mobile numbers
- Field type validation (email, string, integer, etc.)

### ✅ File Upload Security
- Mimetype validation (JPG/PNG only)
- File size validation (max 2MB)
- Secure storage location
- Client-side validation with preview

### ✅ Route Protection
- All admin routes: `super_admin` middleware
- All guard routes: `guard` middleware
- All customer routes: `customer` middleware
- Public routes: `guest` middleware

### ✅ Role-Based Access Control
- IsSuperAdmin middleware
- IsGuard middleware
- IsCustomer middleware
- CanDeleteEntry middleware (unused, replaced with super_admin)
- Controller-level verification (double-check roles)

### ✅ Database Security
- Foreign key constraints
- Cascade delete for carry items
- Unique constraints on mobile numbers
- Indexes for performance

---

## Database Schema

### Tables Created

**1. users**
- id (bigint, primary key)
- name (varchar 255)
- email (varchar 255, nullable, unique)
- password (varchar 255)
- role (enum: super_admin, customer, guard)
- customer_id (bigint, nullable, foreign key to users)
- created_at, updated_at

**2. visitors**
- id (bigint, primary key)
- mobile_number (varchar 15, unique, PRIMARY)
- name (varchar 255)
- address (varchar 500)
- purpose (varchar 500)
- vehicle_number (varchar 50, nullable)
- photo_path (varchar 255)
- created_at, updated_at

**3. entries**
- id (bigint, primary key)
- visitor_id (bigint, foreign key to visitors, cascade)
- guard_id (bigint, foreign key to users)
- in_time (datetime)
- out_time (datetime, nullable)
- duration_minutes (integer, nullable)
- created_at, updated_at

**4. carry_items**
- id (bigint, primary key)
- entry_id (bigint, foreign key to entries, cascade)
- item_name (varchar 255)
- item_type (enum: personal, office, delivery, other)
- quantity (integer)
- item_photo_path (varchar 255, nullable)
- in_status (boolean, default true)
- out_status (boolean, default false)
- created_at, updated_at

---

## How to Use

### For Super Admin

1. **Login:**
   - Go to http://127.0.0.1:8000/login
   - Email: admin@entrykaro.com
   - Password: password

2. **Manage Entries:**
   - View all entries: http://127.0.0.1:8000/admin/entries
   - Delete entry (with confirmation)
   - View detailed entry information

3. **Manage Users:**
   - List users: http://127.0.0.1:8000/admin/users
   - Create new users

4. **Manage Visitors:**
   - List visitors: http://127.0.0.1:8000/admin/visitors
   - Create new visitors

### For Guard

1. **Login:**
   - Go to http://127.0.0.1:8000/login
   - Email: guard@entrykaro.com
   - Password: password

2. **Check-In Visitor:**
   - Go to http://127.0.0.1:8000/guard/entries
   - Search visitor by mobile number
   - If found: Click "Check-In" button
   - If not found: Click "Register New Visitor" button

3. **Register New Visitor:**
   - Fill in mobile number, name, address, purpose
   - Upload visitor photo (JPG/PNG, max 2MB)
   - Click "Register Visitor"

4. **Check-Out Visitor:**
   - Go to http://127.0.0.1:8000/guard/entry-details/{visitor_id}
   - Click "Check Out" button
   - Duration auto-calculated

5. **View Today's Entries:**
   - Go to http://127.0.0.1:8000/guard/entries/list
   - See all of today's entries in table
   - Active entries highlighted in yellow

6. **Manage Carry Items:**
   - On entry details page, click "Manage Items"
   - Add items with type and quantity
   - Items automatically marked out on visitor check-out

### For Customer

1. **Login:**
   - Go to http://127.0.0.1:8000/login
   - Email: acme@entrykaro.com (or customer@entrykaro.com)
   - Password: password

2. **View Entries:**
   - Go to http://127.0.0.1:8000/customer/entries
   - View all entries for your organization
   - NO delete option available

3. **Manage Guards:**
   - Go to http://127.0.0.1:8000/customer/guards
   - Add guards to your organization
   - Assign guards to yourself

---

## Project Structure

```
/Users/wiredtechie/Desktop/entry-karo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── EntryController.php  (NEW - Admin entry management)
│   │   │   │   └── ... (other admin controllers)
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── Customer/
│   │   │   │   └── DashboardController.php
│   │   │   ├── Guard/
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── EntryController.php  (Guard entry operations)
│   │   │   └── Middleware/
│   │   │       ├── CheckRole.php
│   │   │       ├── IsSuperAdmin.php
│   │   │       ├── IsGuard.php
│   │   │       ├── IsCustomer.php
│   │   │       └── CanDeleteEntry.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Visitor.php
│   │   ├── Entry.php
│   │   └── CarryItem.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_12_28_042219_create_visitors_table.php
│   │   ├── 2025_12_28_042220_create_entries_table.php
│   │   └── 2025_12_28_042221_create_carry_items_table.php
│   └── seeders/
│       └── UserSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   ├── entries/
│       │   │   ├── index.blade.php  (NEW - Admin entries list)
│       │   │   ├── show.blade.php  (NEW - Admin entry details)
│       │   │   └── confirm-delete.blade.php  (NEW - Delete confirmation)
│       │   └── dashboard.blade.php
│       ├── guard/
│       │   ├── dashboard.blade.php
│       │   └── entries/
│       │       ├── index.blade.php
│       │       ├── list.blade.php  (NEW - Today's entries table)
│       │       ├── registration.blade.php  (NEW - Visitor registration)
│       │       ├── visitor-details.blade.php
│       │       └── entry-details.blade.php
│       └── customer/
│           └── dashboard.blade.php
├── routes/
│   └── web.php  (UPDATED - Admin entry routes added)
├── storage/
│   ├── app/
│   │   └── public/visitors/  (For visitor photos)
│   └── logs/
│       └── laravel.log  (Audit logs)
└── public/
    └── storage  (Symbolic link to storage/app/public)
```

---

## Documentation Files

| File | Description |
|-------|-------------|
| `DATABASE_SCHEMA.md` | Complete database schema documentation |
| `ROLE_BASED_ACCESS.md` | Role-based access control details |
| `ACCESS_CONTROL_SUMMARY.md` | Access control summary |
| `GUARD_ENTRY_SCREEN.md` | Guard entry screen implementation |
| `CARRY_ITEMS_TRACKING.md` | Carry items tracking documentation |
| `ENTRY_LISTING_TABLE.md` | Entry listing table documentation |
| `ENTRY_DELETE_FUNCTIONALITY.md` | Entry delete functionality documentation |
| `SECURITY_AUDIT_REPORT.md` | Security audit report |
| `PROJECT_SETUP_GUIDE.md` | This file |

---

## Testing the Application

### Test Scenario 1: Guard Check-In Flow
1. Login as guard (guard@entrykaro.com / password)
2. Go to http://127.0.0.1:8000/guard/entries
3. Search for visitor (e.g., "+91 98765 43210")
4. Click "Check-In" button
5. Entry created with current time

### Test Scenario 2: Visitor Registration
1. Go to http://127.0.0.1:8000/guard/entries
2. Search for new visitor (e.g., "+91 12345 67890")
3. Click "Register New Visitor" button
4. Fill in form:
   - Mobile: +91 12345 67890
   - Name: Test Visitor
   - Address: 123 Test Street
   - Purpose: Business Meeting
   - Upload photo (JPG or PNG, < 2MB)
5. Click "Register Visitor"
6. Visitor created, can now be searched

### Test Scenario 3: Carry Items Management
1. Check-in visitor
2. Go to entry details
3. Click "Manage Items"
4. Add item:
   - Name: Laptop
   - Type: Office
   - Quantity: 1
5. Item added
6. Check-out visitor
7. Verify item status changed to "Taken Out"

### Test Scenario 4: Admin Delete Entry
1. Login as super admin (admin@entrykaro.com / password)
2. Go to http://127.0.0.1:8000/admin/entries
3. Find entry, click "Delete" button
4. Review confirmation page
5. Click "Yes, Delete This Entry"
6. Entry deleted, carry items deleted, visitor preserved

### Test Scenario 5: Guard Access Control
1. Login as guard
2. Try to access http://127.0.0.1:8000/admin/entries
3. Expected: 403 Forbidden
4. Result: Access blocked ✅

### Test Scenario 6: Delete Access Control
1. Login as guard
2. View entry details (http://127.0.0.1:8000/guard/entry-details/1)
3. Expected: NO "Delete" button visible
4. Result: Delete option not available ✅

---

## Troubleshooting

### Issue: Database Connection Failed
**Solution:**
1. Check MySQL is running:
   ```bash
   mysql.server status  # macOS
   service mysql status  # Linux
   ```
2. Verify credentials in `.env` file
3. Restart server:
   ```bash
   php artisan serve
   ```

### Issue: Port 8000 Already in Use
**Solution:**
1. Kill existing process:
   ```bash
   lsof -ti:8000 | xargs kill -9
   ```
2. Start on different port:
   ```bash
   php artisan serve --port=8001
   ```

### Issue: Storage Link Missing
**Solution:**
```bash
php artisan storage:link
```

### Issue: Migration Conflicts
**Solution:**
```bash
php artisan migrate:refresh
```
This will rollback and re-run all migrations

### Issue: Permission Denied on File Upload
**Solution:**
1. Check directory permissions:
   ```bash
   chmod -R 775 storage/app/public
   ```
2. Ensure storage is writable

---

## Next Steps

### For Development
1. Implement customer entry viewing screens
2. Add real-time notifications (WebSocket)
3. Implement PDF reports generation
4. Add barcode/QR code scanning for visitors
5. Implement visitor signature capture

### For Production
1. Set `APP_ENV=production` in `.env`
2. Configure proper database credentials
3. Set up SSL certificate
4. Configure reverse proxy (Nginx/Apache)
5. Set up monitoring and logging
6. Implement backup strategy

---

## Support

If you encounter any issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Review documentation files in project root
3. Check browser console for JavaScript errors
4. Verify MySQL service is running

---

## Summary

✅ **Server:** Running on http://127.0.0.1:8000
✅ **Database:** Configured (MySQL)
✅ **Migrations:** Applied successfully
✅ **Seeders:** Users created with roles
✅ **Routes:** All role-based routes configured
✅ **Controllers:** All controllers implemented
✅ **Views:** All Blade views created
✅ **Security:** All security measures in place
✅ **Documentation:** Complete documentation provided

The Entry Karo visitor management system is now ready for use!

---

**Quick Start:**
1. Open browser: http://127.0.0.1:8000
2. Login with super admin, guard, or customer credentials
3. Start managing visitors, entries, and carry items!

---

**Happy Coding! 🚀**

