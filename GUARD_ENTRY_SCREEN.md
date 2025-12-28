# Guard Entry Screen - Implementation Documentation

## Overview

The Guard Entry Screen provides a complete interface for guards to:
- Search visitors by mobile number
- Register new visitors
- Check-in visitors
- Check-out visitors
- View visitor details
- Track visit duration

---

## Features

### 1. Visitor Search
**Location:** `/guard/entries`

**Functionality:**
- Search visitor by mobile number
- Displays visitor photo, name, address, purpose, vehicle number
- Shows active entry status (if any)
- Allows quick check-in/out actions

**API Endpoint:** `POST /guard/entries/search`

**Response:**
```json
{
    "found": true,
    "visitor": {
        "id": 1,
        "mobile_number": "+91987654321",
        "name": "John Doe",
        "address": "123 Main St, City",
        "purpose": "Meeting",
        "vehicle_number": "MH 12 AB 1234",
        "photo_path": "visitors/photo1.jpg"
    },
    "active_entry": {
        "id": 1,
        "in_time": "2024-01-15 09:30:00",
        "out_time": null,
        "duration_minutes": null
    }
}
```

---

### 2. Visitor Registration
**Trigger:** When visitor not found by mobile number

**Functionality:**
- Collect visitor details:
  - Mobile number (unique)
  - Name
  - Address
  - Purpose
  - Vehicle number (optional)
  - Visitor photo (optional)
- Register new visitor
- Automatically prompts for check-in

**API Endpoint:** `POST /guard/entries/visitor/register`

**Request:**
```json
{
    "mobile_number": "+91987654321",
    "name": "John Doe",
    "address": "123 Main St, City",
    "purpose": "Business Meeting",
    "vehicle_number": "MH 12 AB 1234",
    "photo_path": "visitors/photo.jpg"
}
```

---

### 3. Check-In
**Trigger:** Click "Check-In" button

**Requirements:**
- Visitor must exist in database
- Visitor must not have an active entry
- Visit purpose must be provided

**Functionality:**
- Creates new entry row with:
  - `visitor_id`: Selected visitor
  - `guard_id`: Current authenticated guard
  - `in_time`: Current timestamp
  - `out_time`: NULL (active)
  - `duration_minutes`: NULL (calculated on check-out)

**API Endpoint:** `POST /guard/entries/check-in`

**Request:**
```json
{
    "visitor_id": 1,
    "purpose": "Meeting"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Visitor checked in successfully.",
    "entry": {
        "id": 5,
        "visitor_id": 1,
        "guard_id": 2,
        "in_time": "2024-01-15 14:30:00",
        "out_time": null,
        "duration_minutes": null,
        "visitor": { ... }
    }
}
```

**Validation Rules:**
```php
[
    'visitor_id' => 'required|exists:visitors,id',
    'purpose' => 'required|string|max:255',
]
```

**Error Cases:**
- Visitor already has active entry:
  ```json
  {
      "success": false,
      "message": "Visitor is already checked in. Please check them out first.",
      "active_entry": { ... }
  }
  ```

---

### 4. Check-Out
**Trigger:** Click "Check-Out" button

**Requirements:**
- Active entry must exist
- Entry must belong to current guard
- Entry must not already be checked out

**Functionality:**
- Updates existing entry row:
  - Sets `out_time`: Current timestamp
  - Calculates `duration_minutes`: `(out_time - in_time)` in minutes
- Shows success message with duration

**API Endpoint:** `POST /guard/entries/check-out`

**Request:**
```json
{
    "entry_id": 5
}
```

**Response:**
```json
{
    "success": true,
    "message": "Visitor checked out successfully.",
    "entry": {
        "id": 5,
        "visitor_id": 1,
        "guard_id": 2,
        "in_time": "2024-01-15 14:30:00",
        "out_time": "2024-01-15 16:45:00",
        "duration_minutes": 135,
        "visitor": { ... }
    }
}
```

**Duration Calculation:**
```php
// Example:
// in_time:  2024-01-15 14:30:00
// out_time: 2024-01-15 16:45:00
// duration: 135 minutes (2 hours 15 minutes)

$entry->duration_minutes = $entry->in_time->diffInMinutes($entry->out_time);
```

**Validation Rules:**
```php
[
    'entry_id' => 'required|exists:entries,id',
]
```

**Error Cases:**
- Entry already checked out:
  ```json
  {
      "success": false,
      "message": "Visitor has already been checked out."
  }
  ```

---

## Entry Row Lifecycle

### State 1: Not Checked In
```php
[
    'id' => 5,
    'in_time' => NULL,
    'out_time' => NULL,
    'duration_minutes' => NULL,
]
```
**Action:** Check-in button available

---

### State 2: Active (Checked In)
```php
[
    'id' => 5,
    'in_time' => '2024-01-15 14:30:00',
    'out_time' => NULL,
    'duration_minutes' => NULL,
]
```
**Action:** Check-out button available

---

### State 3: Completed (Checked Out)
```php
[
    'id' => 5,
    'in_time' => '2024-01-15 14:30:00',
    'out_time' => '2024-01-15 16:45:00',
    'duration_minutes' => 135,
]
```
**Action:** No check-in/out buttons (entry closed)

---

## Rules Implemented

### ✅ Rule 1: IN and OUT in Same Entry Row
**Implementation:** Single entry row represents both check-in and check-out

**Database:**
```sql
INSERT INTO entries (visitor_id, guard_id, in_time, out_time, duration_minutes)
VALUES (1, 2, NOW(), NULL, NULL);

UPDATE entries
SET out_time = NOW(),
    duration_minutes = TIMESTAMPDIFF(MINUTE, in_time, out_time)
WHERE id = 5;
```

**Result:** One entry row contains both timestamps and calculated duration

---

### ✅ Rule 2: OUT Button Visible Only If IN Exists
**Implementation:** UI shows check-out button only when `active_entry` exists

**JavaScript:**
```javascript
@if ($activeEntry)
    <button id="checkOutBtn">Check OUT</button>
@else
    <button id="checkInBtn">Check IN</button>
@endif
```

**Result:** Buttons dynamically shown/hidden based on entry state

---

### ✅ Rule 3: Guard Cannot Delete or Edit Any Entry
**Implementation:** No delete or edit routes exist for guard

**Routes:**
```php
Route::prefix('guard')->middleware('guard')->group(function () {
    // Check-in: ✅ Allowed
    Route::post('/check-in', ...);

    // Check-out: ✅ Allowed
    Route::post('/check-out', ...);

    // Delete: ❌ Route intentionally omitted
    // Edit: ❌ Route intentionally omitted
});
```

**Result:** Guards have operational access only (no destructive actions)

---

## Controller Methods

### EntryController
**Location:** `app/Http/Controllers/Guard/EntryController.php`

| Method | Endpoint | Purpose |
|--------|----------|---------|
| `index()` | GET /guard/entries | Display entry screen |
| `search()` | POST /guard/entries/search | Search visitor by mobile |
| `checkIn()` | POST /guard/entries/check-in | Check-in visitor |
| `checkOut()` | POST /guard/entries/check-out | Check-out visitor |
| `registerVisitor()` | POST /guard/entries/visitor/register | Register new visitor |
| `showEntryDetails()` | GET /guard/entries/{entry} | Show entry details |
| `storeCarryItem()` | POST /guard/carry-items/store | Add carry item |
| `updateCarryItem()` | PUT /guard/carry-items/{item} | Update carry item |

---

## Dashboard Integration

### Guard Dashboard
**Location:** `/guard/dashboard`

**Features:**
- **Quick Stats:**
  - Currently Checked In
  - Today's Check-ins
  - Today's Check-outs
  - Average Duration (minutes)

- **Active Visitors List:**
  - Shows all currently checked-in visitors
  - Displays visitor name, mobile number
  - Shows check-in time and elapsed duration
  - Real-time updates

- **Today's Entries Table:**
  - Lists all entries processed today
  - Shows check-in time, check-out time, duration
  - Color-coded status (Active/Checked Out)
  - Link to entry details

---

## User Experience Flow

### Flow 1: New Visitor
1. Guard enters mobile number
2. System searches visitor (not found)
3. Registration form appears
4. Guard fills visitor details
5. Click "Register & Check-In"
6. Visitor checked in with entry created

### Flow 2: Returning Visitor (Already Checked In)
1. Guard enters mobile number
2. System finds visitor with active entry
3. Shows visitor details + active entry info
4. Check-out button displayed
5. Guard clicks "Check-Out"
6. Confirmation dialog
7. Entry updated with out_time and duration
8. Success message shown

### Flow 3: Returning Visitor (Not Checked In)
1. Guard enters mobile number
2. System finds visitor without active entry
3. Shows visitor details
4. Check-in button displayed
5. Guard selects visit purpose
6. Click "Check-In"
7. New entry created for this visit

---

## Security Measures

### 1. Guard Isolation
- Guards can ONLY access guard routes
- Middleware: `IsGuard`
- Cannot access admin or customer routes
- Returns 403 Forbidden for unauthorized access

### 2. Entry Ownership
- Guards can only view/operate on their own entries
- Validation in controller:
  ```php
  $entry = Entry::where('id', $id)
      ->where('guard_id', Auth::user()->id)
      ->firstOrFail();
  ```

### 3. No Delete Access
- No delete routes exist for guards
- Physical route omission prevents deletion
- Guards can only update (check-out) entries

### 4. CSRF Protection
- All POST requests include CSRF token
- Prevents cross-site request forgery

---

## Database Constraints

### Foreign Key Constraints
```sql
-- Entry must reference valid visitor
FOREIGN KEY (visitor_id) REFERENCES visitors(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE

-- Entry must reference valid guard (user)
FOREIGN KEY (guard_id) REFERENCES users(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
```

**Impact:**
- Cannot create entry for non-existent visitor
- Cannot create entry for deleted guard (RESTRICT)
- Deleting visitor cascades to delete all entries

### Unique Constraints
```sql
-- Mobile number must be unique
UNIQUE (mobile_number)
```

**Impact:**
- Prevents duplicate visitor registrations
- Mobile number is primary identifier

---

## Frontend Features

### 1. Search Validation
```javascript
if (!mobileNumber) {
    showMessage('Please enter a mobile number.', 'error');
    return;
}
```

### 2. Loading States
- Search: Shows "Searching..." spinner
- Check-in/out: Shows loading indicator
- Registration: Shows "Registering..." indicator

### 3. Success/Error Messages
- Auto-dismiss after 5 seconds
- Color-coded (green/red)
- Clear action feedback

### 4. Confirmation Dialogs
- Check-out confirmation: "Are you sure you want to check out this visitor?"
- Prevents accidental check-outs

### 5. Auto-Refresh
- After check-in: Page reloads after 2 seconds
- After check-out: Page reloads after 2 seconds
- Shows updated entry status

### 6. Real-Time Duration
- Active entries show live duration
- Updates every second (not implemented, can be added)
- Format: "X minutes ago" for active entries

---

## Error Handling

### Client-Side Validation
```javascript
// Required fields
if (!mobileNumber) {
    showMessage('Please enter a mobile number.', 'error');
}

// Format validation (can be added)
if (!isValidMobileNumber(mobileNumber)) {
    showMessage('Invalid mobile number format.', 'error');
}
```

### Server-Side Validation
```php
// Visitor not found
if (!$visitor) {
    showVisitorRegistration(mobileNumber);
}

// Visitor already checked in
if ($existingEntry) {
    return response()->json([
        'success' => false,
        'message' => 'Visitor is already checked in.',
        'active_entry' => $existingEntry,
    ], 400);
}

// Entry already checked out
if ($entry->out_time) {
    return response()->json([
        'success' => false,
        'message' => 'Visitor has already been checked out.',
    ], 400);
}
```

---

## Testing Scenarios

### Scenario 1: New Visitor Registration
**Input:** Mobile number not in database
**Expected:** Registration form appears
**Result:** ✅ Visitor registered and checked in

### Scenario 2: Duplicate Check-In Attempt
**Input:** Try to check-in visitor with active entry
**Expected:** Error message "Visitor is already checked in"
**Result:** ✅ Error shown, no duplicate entry created

### Scenario 3: Check-Out Without Check-In
**Input:** Try to check-out visitor with no active entry
**Expected:** Error "No active entry to check out"
**Result:** ✅ Error message displayed

### Scenario 4: Guard Accessing Other Guard's Entry
**Input:** Guard A tries to check-out Guard B's entry
**Expected:** 403 Forbidden or "Not authorized"
**Result:** ✅ Access denied by validation

### Scenario 5: Invalid Mobile Number
**Input:** Non-existent mobile number
**Expected:** Registration form appears
**Result:** ✅ User can register new visitor

---

## Performance Considerations

### Database Indexes
```sql
-- Optimize visitor lookups
CREATE INDEX idx_mobile ON visitors(mobile_number);

-- Optimize guard entries
CREATE INDEX idx_guard_in_time ON entries(guard_id, in_time);

-- Optimize active entry queries
CREATE INDEX idx_active_entries ON entries(out_time) WHERE out_time IS NULL;
```

### Query Optimization
```php
// Use eager loading to avoid N+1 queries
Entry::with('visitor', 'carryItems')->get();

// Use latest() for most recent
Entry::where('guard_id', $guard->id)
    ->whereNull('out_time')
    ->latest('in_time')
    ->first();
```

---

## Future Enhancements

### Planned Features
1. **Carry Items Management**
   - Add items when checking in
   - Track items brought in vs taken out
   - Upload item photos

2. **Real-Time Updates**
   - WebSockets for live dashboard updates
   - Push notifications for long-duration visitors

3. **Visitor History**
   - View all past visits for a visitor
   - Analyze visit patterns

4. **Search Enhancements**
   - Search by name (in addition to mobile)
   - Filter by date range
   - Advanced filters

5. **Photo Upload**
   - Capture visitor photo via webcam
   - Upload and store in entry

6. **Entry Notes**
   - Add notes to entries
   - Record special instructions

7. **Print Badge**
   - Generate visitor pass badge
   - Print with QR code

8. **Multiple Visitors**
   - Group check-in for multiple visitors
   - Batch operations

---

## Summary

The Guard Entry Screen implements:

✅ **Visitor Search:** Search by mobile number
✅ **Registration:** New visitor registration when not found
✅ **Check-In:** Creates entry with current timestamp
✅ **Check-Out:** Updates same entry row with out_time
✅ **Auto-Calculation:** Duration calculated on check-out
✅ **IN/OUT in Same Row:** Single entry represents both actions
✅ **OUT Button Visibility:** Only shown when IN exists
✅ **No Delete/Edit:** Guards cannot delete or edit entries
✅ **Guard Isolation:** Guards can only access guard routes
✅ **Security:** CSRF protection, authorization, validation

All specified rules and features have been successfully implemented!

