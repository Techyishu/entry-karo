# Database Schema Documentation

## Overview

The Entry Karo system uses four main tables to manage visitors, entries, and carry items with role-based access control.

## Tables

### 1. users

Stores system users with role-based access.

**Purpose:** Manage authentication and authorization for super admins, customers, and guards.

| Column | Type | Attributes | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | Primary Key | Auto-incrementing ID |
| name | string | Required | User's full name |
| email | string | Nullable, Unique | Email address (nullable for guards who may not have email) |
| email_verified_at | timestamp | Nullable | Email verification timestamp |
| password | string | Required | Encrypted password |
| role | string | Default: 'customer' | Role enum: 'super_admin', 'customer', 'guard' |
| customer_id | bigint unsigned | Nullable, Foreign Key | ID of customer this guard is assigned to (self-referencing) |
| remember_token | string | Nullable | Remember me token for "remember me" functionality |
| created_at | timestamp | Auto | Creation timestamp |
| updated_at | timestamp | Auto | Last update timestamp |

**Foreign Keys:**
- `customer_id` → `users(id)` on DELETE SET NULL, on UPDATE CASCADE

**Indexes:**
- Primary key on `id`
- Unique index on `email`
- Foreign key index on `customer_id`

**Notes:**
- Email is nullable to support guards who may not have email accounts
- `customer_id` allows assigning guards to specific customers (self-referencing foreign key)
- Roles determine dashboard and feature access
- Super admin has access to all features

---

### 2. visitors

Stores visitor information.

**Purpose:** Maintain visitor records with unique mobile number as primary identifier.

| Column | Type | Attributes | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | Primary Key | Auto-incrementing ID |
| mobile_number | string | Unique, Required | Mobile number (primary identifier) |
| name | string | Required | Visitor's full name |
| address | string | Required | Visitor's address |
| purpose | text | Required | Purpose of visit |
| vehicle_number | string | Nullable | Vehicle number (if applicable) |
| photo_path | string | Nullable | Path to visitor's photo |
| created_at | timestamp | Auto | Creation timestamp |
| updated_at | timestamp | Auto | Last update timestamp |

**Indexes:**
- Primary key on `id`
- Unique index on `mobile_number`

**Notes:**
- `mobile_number` is the primary identifier for visitors
- Multiple visitors can have the same name but must have unique mobile numbers
- Vehicle number and photo are optional
- Purpose field uses TEXT to accommodate longer descriptions

---

### 3. entries

Tracks visitor check-ins and check-outs.

**Purpose:** Record visitor entry/exit with duration tracking.

| Column | Type | Attributes | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | Primary Key | Auto-incrementing ID |
| visitor_id | bigint unsigned | Required, Foreign Key | Reference to visitors table |
| guard_id | bigint unsigned | Required, Foreign Key | Reference to users table (guard) |
| in_time | datetime | Required | Check-in timestamp |
| out_time | datetime | Nullable | Check-out timestamp (null when active) |
| duration_minutes | integer | Nullable | Total visit duration in minutes |
| created_at | timestamp | Auto | Record creation timestamp |
| updated_at | timestamp | Auto | Last update timestamp |

**Foreign Keys:**
- `visitor_id` → `visitors(id)` on DELETE CASCADE, on UPDATE CASCADE
- `guard_id` → `users(id)` on DELETE RESTRICT, on UPDATE CASCADE

**Indexes:**
- Primary key on `id`
- Index on `in_time`
- Index on `out_time`
- Composite index on `visitor_id`, `guard_id`

**Notes:**
- One row contains both IN and OUT timestamps
- `out_time` is NULL when visitor is currently checked in
- `duration_minutes` calculated when `out_time` is set
- Guard deletion is restricted (RESTRICT) to maintain data integrity
- Visitor deletion cascades to remove all associated entries

**Entry Lifecycle:**
1. **Check-in:** Create entry with `in_time` (OUT fields NULL)
2. **Active Entry:** Visitor is on premises (out_time is NULL)
3. **Check-out:** Update entry with `out_time` and calculate `duration_minutes`
4. **Completed Entry:** Both timestamps and duration are populated

---

### 4. carry_items

Tracks items carried by visitors during visits.

**Purpose:** Manage items visitors bring in and take out.

| Column | Type | Attributes | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | Primary Key | Auto-incrementing ID |
| entry_id | bigint unsigned | Required, Foreign Key | Reference to entries table |
| item_name | string | Required | Name/Description of item |
| item_type | enum | Default: 'other' | Type: 'personal', 'office', 'delivery', 'other' |
| quantity | integer | Default: 1 | Quantity of items |
| item_photo_path | string | Nullable | Path to item photo |
| in_status | boolean | Default: true | Item was brought in |
| out_status | boolean | Default: false | Item was taken out |
| created_at | timestamp | Auto | Record creation timestamp |
| updated_at | timestamp | Auto | Last update timestamp |

**Foreign Keys:**
- `entry_id` → `entries(id)` on DELETE CASCADE, on UPDATE CASCADE

**Indexes:**
- Primary key on `id`
- Composite index on `entry_id`, `item_type`

**Item Types:**
- `personal` - Personal belongings (bags, electronics, etc.)
- `office` - Office equipment/supplies
- `delivery` - Delivery items/packages
- `other` - Other types of items

**Item Status Examples:**
1. **Item only brought in:** `in_status: true`, `out_status: false`
2. **Item brought in and taken out:** `in_status: true`, `out_status: true`
3. **Item not brought in but taken out:** `in_status: false`, `out_status: true` (rare case)

**Notes:**
- Multiple items can be tracked per entry
- Item photos are optional for visual documentation
- Status flags allow tracking item movement
- Cascading delete removes items when entry is deleted

---

## Relationships

### User Model (app/Models/User.php)

**Belongs To:**
- `customer()` - User belongs to a customer (for guards)

**Has Many:**
- `guards()` - Customer has many guards (for customers)
- `entries()` - Guard has many entries (for guards)

**Methods:**
- `hasRole($role)` - Check specific role
- `isSuperAdmin()` - Check if super admin
- `isAdmin()` - Check if admin (includes super_admin)
- `isGuard()` - Check if guard
- `isCustomer()` - Check if customer

---

### Visitor Model (app/Models/Visitor.php)

**Has Many:**
- `entries()` - All visit entries
- `latestEntry()` - Most recent entry
- `activeEntry()` - Current active entry (where out_time is NULL)

**Methods:**
- `isCheckedIn()` - Check if currently checked in

---

### Entry Model (app/Models/Entry.php)

**Belongs To:**
- `visitor()` - Belongs to a visitor
- `guard()` - Belongs to a guard (User)

**Has Many:**
- `carryItems()` - All carry items for this entry

**Methods:**
- `isCheckedIn()` - Check if visitor is still checked in (out_time is NULL)
- `hasCheckedOut()` - Check if visitor has checked out
- `calculateDuration()` - Calculate and set duration_minutes

---

### CarryItem Model (app/Models/CarryItem.php)

**Belongs To:**
- `entry()` - Belongs to an entry

**Methods:**
- `wasBroughtIn()` - Check if item was brought in
- `wasTakenOut()` - Check if item was taken out
- `isInside()` - Check if item is still inside (brought in but not taken out)
- `visitor()` - Get visitor through entry relationship

---

## Foreign Key Relationships

```
users (customer_id) ────────────┐
                                    │
                                    └─> users (id)
                                    (Self-referencing: guards assigned to customers)

visitors (id) <────────── entries (visitor_id)
   │                              │
   │                              └─> users (id)
   │                                  (guard_id)
   │
   └─> entries (id) <────── carry_items (entry_id)
```

## Role-Based Access

### Super Admin
- Full access to all dashboards and features
- Can manage all users (super_admin, customer, guard)
- Can view all entries and visitors
- Can manage system settings

### Customer
- Access to customer dashboard
- Can view their assigned guards
- Can view visitor entries to their location
- Can manage guard assignments

### Guard
- Access to guard dashboard
- Can create visitor entries (check-in)
- Can update visitor entries (check-out)
- Can track carry items
- Can only access data for their assigned customer

---

## Migration Order

Run migrations in this order (Laravel handles automatically):

1. `0001_01_01_000000_create_users_table.php` - Create users table
2. `2025_12_28_042219_create_visitors_table.php` - Create visitors table
3. `2025_12_28_042220_create_entries_table.php` - Create entries table
4. `2025_12_28_042221_create_carry_items_table.php` - Create carry_items table

---

## Running Migrations

```bash
# Run all migrations
php artisan migrate

# Run migrations with rollback support
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback
```

---

## Seeders

### UserSeeder

Creates test users:
- **Super Admin:** admin@entrykaro.com / password
- **Customer 1:** acme@entrykaro.com / password
- **Customer 2:** tech@entrykaro.com / password
- **Guard 1:** John Guard / password (assigned to Customer 1)
- **Guard 2:** Jane Guard / password (assigned to Customer 2)

```bash
php artisan db:seed --class=UserSeeder
```

---

## Data Integrity Rules

1. **Mobile Number Uniqueness:** Each visitor must have a unique mobile number
2. **Email Uniqueness:** Email must be unique (or null) across users
3. **Guard Assignment:** Guards can be assigned to only one customer
4. **Entry Lifecycle:** One entry row captures both check-in and check-out
5. **Cascading Deletes:**
   - Deleting a visitor deletes all their entries and carry items
   - Deleting an entry deletes all associated carry items
   - Deleting a customer sets guard's customer_id to NULL
   - Deleting a guard is restricted if they have processed entries

---

## Performance Considerations

1. **Indexes on Timestamps:** `entries.in_time` and `entries.out_time` for date-based queries
2. **Composite Indexes:** `entries(visitor_id, guard_id)` for visitor-guard lookups
3. **Foreign Key Indexes:** Automatically created by Laravel for foreign keys
4. **Unique Constraints:** Mobile numbers for fast visitor lookups

---

## Example Queries

### Get Active Visitors
```php
$activeVisitors = Visitor::whereHas('activeEntry')
    ->with(['activeEntry.guard', 'activeEntry.carryItems'])
    ->get();
```

### Get Visitor History
```php
$visitorHistory = Visitor::with(['entries.guard', 'entries.carryItems'])
    ->where('mobile_number', '+919876543210')
    ->first();
```

### Get Guard's Today's Entries
```php
$todayEntries = Entry::with('visitor', 'carryItems')
    ->where('guard_id', $guardId)
    ->whereDate('in_time', today())
    ->get();
```

### Get Items Currently Inside
```php
$itemsInside = CarryItem::where('in_status', true)
    ->where('out_status', false)
    ->with(['entry.visitor', 'entry.guard'])
    ->get();
```

---

## Database Storage Requirements

- **Photos:** Store file paths in `photo_path` and `item_photo_path` columns
- **Actual Files:** Store in `storage/app/public/visitors` and `storage/app/public/items`
- **Access:** Create symbolic link: `php artisan storage:link`

---

## Notes for Development

1. **Entry Management:** Always check `out_time` for NULL to determine active status
2. **Duration Calculation:** Calculate duration only when `out_time` is set
3. **Guard Assignment:** Update `customer_id` when reassigning guards
4. **Photo Uploads:** Use Laravel's Storage facade for file management
5. **Soft Deletes:** Not implemented by default, can be added if needed

---

## Future Enhancements

Potential additions:
- Add `softDeletes()` to models for trash functionality
- Add visitor photo thumbnails
- Add visitor signature fields
- Add entry validation/verification steps
- Add carry item categories
- Add visitor blacklisting
- Add recurring visitor features
- Add visitor analytics/reporting tables

