# Role-Based Access Control Documentation

## Overview

Entry Karo implements a three-tier role-based access control system using Laravel middleware to ensure proper authorization across all routes.

---

## Roles

### 1. Super Admin
**Role:** `super_admin`
**Access Level:** Full system access
**Symbol:** 🛡️

**Permissions:**
- Full access to all dashboards (Admin, Customer, Guard)
- Create, Read, Update, Delete (CRUD) all resources
- Manage all users (Super Admins, Customers, Guards)
- Manage all visitors
- Delete entries (only role with this permission)
- Manage guards assignments to customers
- Access all reports and analytics
- System configuration and settings

**Access Pattern:** Unrestricted access to all routes

---

### 2. Customer
**Role:** `customer`
**Access Level:** Business/Owner access
**Symbol:** 🏢

**Permissions:**
- Access to Customer dashboard only
- Create, Read, Update, Delete their assigned guards
- View all entries at their location
- View visitor history for their premises
- **CANNOT delete entries** (read-only access to entries)
- Manage guard assignments
- View reports for their location

**Access Pattern:** Restricted to customer features, read-only on entries

---

### 3. Guard
**Role:** `guard`
**Access Level:** Operational/Staff access
**Symbol:** 👮

**Permissions:**
- Access to Guard dashboard only
- Create new entries (check-in visitors)
- Update entries (check-out visitors)
- Track carry items (add, update status)
- View their own entry history
- **CANNOT access Admin or Customer routes**
- **CANNOT delete entries**
- Cannot manage users or guards

**Access Pattern:** Highly restricted, operational access only

---

## Middleware

### Role-Specific Middleware

#### 1. IsSuperAdmin
**Alias:** `super_admin`
**File:** `app/Http/Middleware/IsSuperAdmin.php`

**Purpose:** Restrict routes to super admin only

**Behavior:**
- Checks if user is authenticated
- Validates user has `super_admin` role
- Returns 403 Forbidden if not super admin

**Usage:**
```php
Route::middleware('super_admin')->group(function () {
    // Only super admin can access
});
```

---

#### 2. IsCustomer
**Alias:** `customer`
**File:** `app/Http/Middleware/IsCustomer.php`

**Purpose:** Restrict routes to customers only

**Behavior:**
- Checks if user is authenticated
- Validates user has `customer` role
- Returns 403 Forbidden if not customer

**Usage:**
```php
Route::middleware('customer')->group(function () {
    // Only customers can access
});
```

---

#### 3. IsGuard
**Alias:** `guard`
**File:** `app/Http/Middleware/IsGuard.php`

**Purpose:** Restrict routes to guards only

**Behavior:**
- Checks if user is authenticated
- Validates user has `guard` role
- Returns 403 Forbidden if not guard

**Usage:**
```php
Route::middleware('guard')->group(function () {
    // Only guards can access
});
```

---

### Permission Middleware

#### 4. CanDeleteEntry
**Alias:** `can_delete_entry`
**File:** `app/Http/Middleware/CanDeleteEntry.php`

**Purpose:** Restrict entry deletion to super admin only

**Behavior:**
- Checks if user is authenticated
- Validates user has `super_admin` role
- **Customers cannot delete entries** - denied
- **Guards cannot delete entries** - denied
- Returns 403 Forbidden if not super admin

**Usage:**
```php
Route::delete('/entries/{entry}', fn() => 'Delete')
    ->middleware('can_delete_entry');
```

**Security Note:** This ensures only super admin can permanently delete entry records. Guards can only update (check-out) entries, not delete them.

---

### Generic Role Middleware

#### 5. CheckRole
**Alias:** `role`
**File:** `app/Http/Middleware/CheckRole.php`

**Purpose:** Generic role checking for multiple roles

**Behavior:**
- Checks if user is authenticated
- Super admin bypasses all role checks (has full access)
- Validates user role matches one of the provided roles
- Returns 403 Forbidden if role doesn't match

**Usage:**
```php
// Multiple roles allowed
Route::middleware('role:admin,customer')->group(function () {
    // Admins or customers can access
});

// Single role required
Route::middleware('role:guard')->group(function () {
    // Only guards can access
});
```

**Note:** Super admin passes all `role` middleware checks automatically.

---

## Middleware Aliases

All middleware are registered in `bootstrap/app.php`:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
    'super_admin' => \App\Http\Middleware\IsSuperAdmin::class,
    'customer' => \App\Http\Middleware\IsCustomer::class,
    'guard' => \App\Http\Middleware\IsGuard::class,
    'can_delete_entry' => \App\Http\Middleware\CanDeleteEntry::class,
]);
```

---

## Route Protection

### Super Admin Routes
**Prefix:** `/admin`
**Middleware:** `super_admin`

**Protected Features:**
- Dashboard
- User Management (CRUD all users)
- Visitor Management (CRUD all visitors)
- Entry Management (CRUD + DELETE)
- System Settings
- Reports & Analytics

**Example Routes:**
```php
Route::prefix('admin')->middleware('super_admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::delete('/entries/{entry}', [EntryController::class, 'delete'])
        ->middleware('can_delete_entry'); // Additional protection
});
```

---

### Customer Routes
**Prefix:** `/customer`
**Middleware:** `customer`

**Protected Features:**
- Dashboard
- Guard Management (CRUD assigned guards)
- Entry Viewing (READ ONLY)
- Visitor History (READ ONLY)
- Reports for their location

**Important Restrictions:**
- **NO DELETE routes for entries** - customers cannot delete entries
- Only READ access to entry data
- Cannot access admin or guard routes

**Example Routes:**
```php
Route::prefix('customer')->middleware('customer')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index']);
    Route::get('/entries', [EntryController::class, 'index']); // View only
    Route::get('/entries/{entry}', [EntryController::class, 'show']); // View only
    // NO DELETE ROUTE - Intentionally omitted
});
```

---

### Guard Routes
**Prefix:** `/guard`
**Middleware:** `guard`

**Protected Features:**
- Dashboard
- Create Entry (Check-in visitors)
- Update Entry (Check-out visitors)
- Carry Items Management (Add, Update status)
- View own entry history

**Strict Restrictions:**
- **NO DELETE routes** - guards cannot delete entries
- Cannot access admin routes
- Cannot access customer routes
- Can only view entries they've processed

**Example Routes:**
```php
Route::prefix('guard')->middleware('guard')->group(function () {
    Route::get('/dashboard', [GuardDashboardController::class, 'index']);
    Route::post('/entries', [EntryController::class, 'store']); // Check-in
    Route::put('/entries/{entry}/checkout', [EntryController::class, 'checkout']); // Check-out
    // NO DELETE ROUTE - Guards cannot delete
});
```

---

## Access Matrix

| Feature | Super Admin | Customer | Guard |
|---------|-------------|-----------|--------|
| Admin Dashboard | ✅ | ❌ | ❌ |
| Customer Dashboard | ✅ | ✅ | ❌ |
| Guard Dashboard | ✅ | ❌ | ✅ |
| View All Entries | ✅ | ✅ (own location) | ✅ (own entries) |
| Create Entries | ✅ | ❌ | ✅ |
| Update Entries | ✅ | ❌ | ✅ (check-out) |
| **Delete Entries** | ✅ | ❌ | ❌ |
| Manage Users | ✅ | ✅ (guards) | ❌ |
| Manage Guards | ✅ | ✅ (own) | ❌ |
| Manage Visitors | ✅ | ❌ | ❌ |
| View Reports | ✅ | ✅ (own) | ✅ (own) |

---

## Security Rules

### 1. Guard Isolation
- Guards **CANNOT** access `/admin/*` routes (blocked by middleware)
- Guards **CANNOT** access `/customer/*` routes (blocked by middleware)
- Guards only have access to `/guard/*` routes

**Implementation:**
```php
Route::prefix('admin')->middleware('super_admin')->group(function () {
    // Guard access blocked - 403 Forbidden
});

Route::prefix('customer')->middleware('customer')->group(function () {
    // Guard access blocked - 403 Forbidden
});

Route::prefix('guard')->middleware('guard')->group(function () {
    // Only guard can access
});
```

---

### 2. Entry Deletion Restriction
- **Only super admin** can delete entries
- **Customers cannot delete entries** - they have read-only access
- **Guards cannot delete entries** - they can only check-out

**Implementation:**
```php
// Super Admin Route - DELETE allowed
Route::delete('/admin/entries/{entry}', [EntryController::class, 'delete'])
    ->middleware('super_admin')
    ->middleware('can_delete_entry');

// Customer Routes - DELETE intentionally omitted
Route::prefix('/customer')->middleware('customer')->group(function () {
    Route::get('/entries', [EntryController::class, 'index']);
    Route::get('/entries/{entry}', [EntryController::class, 'show']);
    // NO DELETE ROUTE
});

// Guard Routes - DELETE intentionally omitted
Route::prefix('/guard')->middleware('guard')->group(function () {
    Route::post('/entries', [EntryController::class, 'store']);
    Route::put('/entries/{entry}/checkout', [EntryController::class, 'checkout']);
    // NO DELETE ROUTE
});
```

---

### 3. Customer Entry Access
- Customers can **view** all entries at their location
- Customers **cannot modify** entries (read-only)
- Customers **cannot delete** entries

**Implementation:**
```php
Route::prefix('customer')->middleware('customer')->group(function () {
    // View entries - ALLOWED
    Route::get('/entries', [EntryController::class, 'index']);
    Route::get('/entries/{entry}', [EntryController::class, 'show']);

    // Create/Update/Delete entries - BLOCKED (routes not defined)
});
```

---

### 4. Guard Operational Access
- Guards can **create** entries (check-in)
- Guards can **update** entries (check-out only)
- Guards **cannot delete** entries
- Guards can only view entries they've processed

**Implementation:**
```php
Route::prefix('guard')->middleware('guard')->group(function () {
    // Create entry (Check-in) - ALLOWED
    Route::post('/entries', [EntryController::class, 'store']);

    // Update entry (Check-out) - ALLOWED
    Route::put('/entries/{entry}/checkout', [EntryController::class, 'checkout']);

    // Delete entry - BLOCKED (route not defined)
});
```

---

## Model Permission Methods

### User Model Helper Methods

```php
// Role checks
$user->isSuperAdmin()  // Returns true if super_admin
$user->isAdmin()        // Returns true if admin or super_admin
$user->isGuard()        // Returns true if guard
$user->isCustomer()     // Returns true if customer
$user->hasRole($role)   // Check specific role

// Permission checks
$user->canDeleteEntries()     // Only super_admin returns true
$user->canManageGuards()      // super_admin or customer returns true
```

---

## Testing Access Control

### Test Scenarios

#### Scenario 1: Guard Accessing Admin Routes
**Expected:** 403 Forbidden
**Test:**
```php
// Login as guard
Auth::loginUsingId($guardId);

// Try to access admin dashboard
$response = $this->get('/admin/dashboard');
$response->assertStatus(403);
```

---

#### Scenario 2: Customer Deleting Entry
**Expected:** 403 Forbidden (or 404 Not Found if route doesn't exist)
**Test:**
```php
// Login as customer
Auth::loginUsingId($customerId);

// Try to delete entry
$response = $this->delete('/customer/entries/1');
$response->assertStatus(403);
```

---

#### Scenario 3: Super Admin Deleting Entry
**Expected:** 200 OK (or 204 No Content)
**Test:**
```php
// Login as super admin
Auth::loginUsingId($superAdminId);

// Delete entry
$response = $this->delete('/admin/entries/1');
$response->assertStatus(204);
```

---

#### Scenario 4: Guard Checking Out Visitor
**Expected:** 200 OK
**Test:**
```php
// Login as guard
Auth::loginUsingId($guardId);

// Check-out visitor
$response = $this->put('/guard/entries/1/checkout');
$response->assertStatus(200);
```

---

## Best Practices

### 1. Always Apply Middleware
Never skip middleware for sensitive routes:

❌ **Bad:**
```php
Route::delete('/entries/{id}', fn() => 'Delete'); // No protection
```

✅ **Good:**
```php
Route::delete('/entries/{id}', fn() => 'Delete')
    ->middleware('can_delete_entry'); // Protected
```

---

### 2. Use Specific Middleware Over Generic
Use specific role middleware when only one role should access:

❌ **Bad:**
```php
Route::middleware('role:guard')->group(...); // Generic
```

✅ **Good:**
```php
Route::middleware('guard')->group(...); // Specific
```

---

### 3. Omit Routes Instead of Middleware
Don't create routes with middleware that blocks them - omit the routes entirely:

❌ **Bad:**
```php
Route::delete('/entries/{id}', fn() => 'Delete')
    ->middleware('cannot_delete_entry'); // Weird middleware
```

✅ **Good:**
```php
// Don't create the delete route at all for customers/guards
Route::prefix('customer')->middleware('customer')->group(function () {
    // View only - no delete route
});
```

---

### 4. Document Middleware Usage
Always document why a specific middleware is used:

```php
// Only super admin can delete entries to maintain data integrity
Route::delete('/admin/entries/{id}', [EntryController::class, 'delete'])
    ->middleware('can_delete_entry');
```

---

## Common Mistakes to Avoid

### ❌ Mistake 1: Using `role` middleware for single role
```php
Route::middleware('role:guard')->group(function () {
    // This works but less explicit
});
```

✅ **Correct:** Use specific middleware
```php
Route::middleware('guard')->group(function () {
    // More explicit and self-documenting
});
```

---

### ❌ Mistake 2: Forgetting Super Admin Exception
```php
// Generic role middleware without super admin bypass
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (!in_array($userRole, $roles)) {
        abort(403);
    }
    // Super admin won't pass!
}
```

✅ **Correct:** CheckRole middleware handles this
```php
// Super admin bypasses all role checks
if ($userRole === 'super_admin') {
    return $next($request);
}
```

---

### ❌ Mistake 3: Creating Delete Routes for Customers
```php
Route::prefix('customer')->middleware('customer')->group(function () {
    Route::delete('/entries/{id}', fn() => 'Delete'); // Wrong!
});
```

✅ **Correct:** Don't create the route
```php
Route::prefix('customer')->middleware('customer')->group(function () {
    // View only - no delete route
    Route::get('/entries', fn() => 'Index');
    Route::get('/entries/{id}', fn() => 'Show');
});
```

---

## Summary

### Access Control Rules

1. **Super Admin:** Full access, can delete anything
2. **Customer:** Read-only on entries, can manage guards
3. **Guard:** Operational access (check-in/out only), cannot delete

### Key Protections

✅ Guards cannot access Admin/Customer routes
✅ Customers cannot delete entries (read-only)
✅ Only super admin can delete entries
✅ All routes protected by authentication first
✅ Specific middleware ensures clear intent
✅ Routes omitted rather than blocked for better security

---

## Next Steps

1. Create controllers for each route group
2. Implement authorization in controllers
3. Add form request validation
4. Create tests for access control
5. Document API endpoints with access levels

