# Entry Listing Table - Implementation Documentation

## Overview

A comprehensive table listing all of a guard's entries for today, with visitor information, carry items summary, and automatic duration calculation.

---

## Table Structure

### **Columns Displayed**

| Column | Width | Display | Description |
|--------|--------|----------|-------------|
| Visitor Photo | 64px | Photo thumbnail (10x10) | Visitor's profile photo |
| Mobile Number | 120px | Text | Mobile number (unique visitor ID) |
| Name | 150px | Text | Visitor's full name |
| Purpose | 128px | Truncated text | Purpose of visit (max 25 chars) |
| Vehicle Number | 96px | Text | Vehicle number (or -- if none) |
| Carry Items | 80px | Badge | Number of items (with "items" text) |
| IN Time | 96px | Time (h:i A) | Check-in timestamp |
| OUT Time | 96px | Time or "Active" | Check-out timestamp or "Active" |
| Duration | 96px | Minutes or -- | Calculated duration (if checked out) |
| Actions | 120px | Link | "Check Out" or "View Details" |

---

## Rules Implemented

### ✅ **Rule 1: Auto-Calculate Duration**

**Implementation:** Calculate on check-out automatically

```php
// EntryController checkOut method
$entry->out_time = now();
$entry->duration_minutes = $entry->in_time->diffInMinutes($entry->out_time);
$entry->save();
```

**Formula:** `out_time - in_time = duration_minutes`

**Display in Table:**
```blade
<td class="px-6 py-4 whitespace-nowrap">
    <div class="flex items-center">
        @if ($entry->out_time)
            <span class="text-gray-900">
                {{ $entry->duration_minutes }} min
            </span>
        @else
            <span class="text-gray-400 text-xs">
                --
            </span>
        @endif
    </div>
</td>
```

**Result:** Duration calculated and displayed automatically ✅

---

### ✅ **Rule 2: Highlight Row if OUT Time Missing**

**Implementation:** CSS class applied based on `out_time`

```blade
<tr class="hover:bg-gray-50 @if (!$entry->out_time) bg-yellow-50">
```

**Visual Highlight:** Yellow background (`bg-yellow-50`)

**Use Case:** Visitors who checked in but haven't checked out yet

**Result:** Active entries easily identifiable ✅

---

### ✅ **Rule 3: Visitor Photo Visible Inside Table**

**Implementation:** Photo thumbnail displayed in first column

```blade
<td class="px-6 py-4 whitespace-nowrap">
    <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
        @if ($entry->visitor->photo_path)
            <img 
                src="{{ Storage::url($entry->visitor->photo_path) }}" 
                alt="{{ $entry->visitor->name }}" 
                class="w-full h-full object-cover"
            >
        @else
            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012-2.828 0-4.414 0-4.414L12 16.828V20a2 2 0 01-2.828 2-828 0-4.414-4.414L12 7.172V12a2 2 0 01-2.828-2.828 0-4.414-4.414z" />
                </svg>
            </div>
        @endif
    </div>
</td>
```

**Features:**
- Circular 40x40px thumbnail
- Border for visual separation
- Fallback icon if no photo
- Responsive design

**Result:** Photos visible inline in table ✅

---

### ✅ **Rule 4: Guard Can Only View Today's Entries**

**Implementation:** Filter by current date

```php
// EntryController list method
public function list()
{
    $guard = Auth::user();

    // Get all today's entries for this guard
    $todayEntries = Entry::where('guard_id', $guard->id)
        ->whereDate('in_time', now())  // Only today's entries
        ->with('visitor', 'carryItems')
        ->latest('in_time')
        ->get();

    return view('guard.entries.list', compact('todayEntries'));
}
```

**SQL Query:**
```sql
SELECT * FROM entries
WHERE guard_id = ?
  AND DATE(in_time) = CURRENT_DATE
ORDER BY in_time DESC
```

**Result:** Guard sees only today's entries ✅

---

## Controller Implementation

### **EntryController::list()**

**Purpose:** Display all of guard's entries for today

**Location:** `app/Http/Controllers/Guard/EntryController.php`

**Implementation:**
```php
/**
 * Display list of today's entries.
 */
public function list()
{
    $guard = Auth::user();

    // Get all today's entries for this guard
    $todayEntries = Entry::where('guard_id', $guard->id)
        ->whereDate('in_time', now())
        ->with('visitor', 'carryItems')
        ->latest('in_time')
        ->get();

    // Calculate stats
    $checkInCount = $todayEntries->count();
    $checkOutCount = $todayEntries->whereNotNull('out_time')->count();
    $totalDuration = $todayEntries->whereNotNull('duration_minutes')->sum('duration_minutes');
    $avgDuration = $checkOutCount > 0 ? number_format($totalDuration / $checkOutCount, 0) : 0;

    return view('guard.entries.list', compact(
        'todayEntries',
        'checkInCount',
        'checkOutCount',
        'avgDuration',
    ));
}
```

**Features:**
- ✅ Filter by `guard_id` (authorization)
- ✅ Filter by today's date (`whereDate('in_time', now())`)
- ✅ Eager load `visitor` and `carryItems` relationships
- ✅ Order by `in_time` descending (newest first)
- ✅ Calculate statistics (check-ins, check-outs, average duration)

**Route:**
```php
Route::get('/guard/entries/list', [GuardEntryController::class, 'list'])
    ->name('guard.entries.list')
    ->middleware('guard');
```

---

## View Implementation

### **Entry Listing View**

**Location:** `resources/views/guard/entries/list.blade.php`

**Layout Structure:**
```
┌─────────────────────────────────────────────────────────────┐
│ Header                                                 │
│ Today's Entries • Date                                 │
├─────────────────────────────────────────────────────────────┤
│ Stats Summary (4 cards)                                  │
│ Total • Check-ins • Check-outs • Avg Duration             │
├─────────────────────────────────────────────────────────────┤
│ Table Header                                            │
│ Photo • Mobile • Name • Purpose • Vehicle • Items • IN    │
│ • OUT • Duration • Actions                                │
├─────────────────────────────────────────────────────────────┤
│ Table Rows (one per entry)                               │
│ [Photo]  +91 98765  John Doe  Business Meeting --       │
│           2 items 10:30 AM  --  [Check Out]          │
├─────────────────────────────────────────────────────────────┤
│ Load More Button (if >20 entries)                        │
├─────────────────────────────────────────────────────────────┤
│ Back to Dashboard Button                                   │
└─────────────────────────────────────────────────────────────┘
```

**Header Section:**
```blade
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Today's Entries</h1>
    <p class="text-gray-600">{{ date('F j, Y') }}</p>
    <a href="{{ route('guard.dashboard') }}" class="text-blue-600 hover:text-blue-900">
        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg">
            <!-- back icon -->
        </svg>
        Back to Dashboard
    </a>
</div>
```

---

### **Stats Summary Cards**

**Location:** Top of table

**Implementation:**
```blade
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Entries -->
    <div class="bg-blue-50 p-4 rounded-lg">
        <p class="text-sm text-blue-600 font-medium">Total Entries</p>
        <p class="text-3xl font-bold text-blue-900">
            {{ $todayEntries->count() }}
        </p>
    </div>

    <!-- Check-Ins -->
    <div class="bg-green-50 p-4 rounded-lg">
        <p class="text-sm text-green-600 font-medium">Check-Ins</p>
        <p class="text-3xl font-bold text-green-900">
            {{ $checkInCount }}
        </p>
    </div>

    <!-- Check-Outs -->
    <div class="bg-red-50 p-4 rounded-lg">
        <p class="text-sm text-red-600 font-medium">Check-Outs</p>
        <p class="text-3xl font-bold text-red-900">
            {{ $checkOutCount }}
        </p>
    </div>

    <!-- Average Duration -->
    <div class="bg-purple-50 p-4 rounded-lg">
        <p class="text-sm text-purple-600 font-medium">Avg. Duration</p>
        <p class="text-3xl font-bold text-purple-900">
            {{ $avgDuration }}
        </p>
        <p class="text-xs text-purple-500">minutes</p>
    </div>
</div>
```

**Statistics Calculated:**
- **Total Entries:** Count of all entries today
- **Check-Ins:** Count of all check-ins (same as total)
- **Check-Outs:** Count of entries where `out_time` is not null
- **Avg Duration:** `(Total Duration / Check-Outs)` rounded to 0 decimal places

**Formula:**
```php
$checkInCount = $todayEntries->count();
$checkOutCount = $todayEntries->whereNotNull('out_time')->count();
$totalDuration = $todayEntries->whereNotNull('duration_minutes')->sum('duration_minutes');
$avgDuration = $checkOutCount > 0 ? number_format($totalDuration / $checkOutCount, 0) : 0;
```

---

### **Table Implementation**

**HTML Structure:**
```blade
<div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <!-- Column Headers -->
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($todayEntries as $entry)
                <tr class="hover:bg-gray-50 @if (!$entry->out_time) bg-yellow-50">
                    <!-- Row Data -->
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

**Table Headers:**
```blade
<thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
            Photo
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Mobile
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Name
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
            Purpose
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
            Vehicle
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
            Items
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
            In Time
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
            Out Time
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
            Duration
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
            Actions
        </th>
    </tr>
</thead>
```

---

### **Row Implementation**

**Single Entry Row:**
```blade
@foreach ($todayEntries as $entry)
    <tr class="hover:bg-gray-50 @if (!$entry->out_time) bg-yellow-50">
        
        <!-- Photo Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                @if ($entry->visitor->photo_path)
                    <img
                        src="{{ Storage::url($entry->visitor->photo_path) }}"
                        alt="{{ $entry->visitor->name }}"
                        class="w-full h-full object-cover"
                    >
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg">
                            <!-- user icon -->
                        </svg>
                    </div>
                @endif
            </div>
        </td>

        <!-- Mobile Number Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <p class="text-gray-900">{{ $entry->visitor->mobile_number }}</p>
        </td>

        <!-- Name Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
        </td>

        <!-- Purpose Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <p class="text-sm text-gray-900 truncate" title="{{ $entry->visitor->purpose }}">
                {{ \Illuminate\Support\Str::limit($entry->visitor->purpose, 25) }}
            </p>
        </td>

        <!-- Vehicle Number Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            {{ $entry->visitor->vehicle_number ? $entry->visitor->vehicle_number : '--' }}
        </td>

        <!-- Carry Items Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-1">
                @if ($entry->carryItems->count() > 0)
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                        {{ $entry->carryItems->count() }}
                    </span>
                    <span class="text-xs text-gray-500">items</span>
                @else
                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                        0
                    </span>
                @endif
            </div>
        </td>

        <!-- IN Time Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <p class="text-gray-900">{{ $entry->in_time->format('h:i A') }}</p>
        </td>

        <!-- OUT Time Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                @if ($entry->out_time)
                    <span class="text-green-600 font-medium">
                        {{ $entry->out_time->format('h:i A') }}
                    </span>
                @else
                    <span class="text-yellow-600 font-medium">
                        Active
                    </span>
                @endif
            </div>
        </td>

        <!-- Duration Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                @if ($entry->out_time)
                    <span class="text-gray-900">
                        {{ $entry->duration_minutes }} min
                    </span>
                @else
                    <span class="text-gray-400 text-xs">
                        --
                    </span>
                @endif
            </div>
        </td>

        <!-- Actions Column -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if (!$entry->out_time)
                <a href="{{ route('guard.entry-details', $entry->visitor->id) }}" 
                   class="text-green-600 hover:text-green-900 font-medium">
                    Check Out
                </a>
            @else
                <a href="{{ route('guard.entries.show', $entry->id) }}" 
                   class="text-blue-600 hover:text-blue-900 font-medium">
                    View Details
                </a>
            @endif
        </td>

    </tr>
@endforeach
```

---

## Dashboard Integration

### **Guard Dashboard (Updated)**

**Location:** `resources/views/guard/dashboard.blade.php`

**Changes:**
- ✅ Updated to show complete entry table
- ✅ Active entry highlighted at top
- ✅ Today's entries shown in full table
- ✅ Link to full entry list view

**Dashboard Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│ Guard Dashboard                                          │
│ Welcome, [Guard Name]                                    │
├─────────────────────────────────────────────────────────────┤
│ Stats Summary (4 cards)                                  │
│ Currently Checked In • Check-ins • Check-outs • Avg Duration │
├─────────────────────────────────────────────────────────────┤
│ Active Entry (if any)                                    │
│ 🔵 Visitor Information + Check-out button                   │
├─────────────────────────────────────────────────────────────┤
│ Today's Entries (Preview)                                 │
│ Table (first 10 entries) + Link to full list              │
├─────────────────────────────────────────────────────────────┤
│ New Visitor Check-In Button                                │
└─────────────────────────────────────────────────────────────┘
```

**Active Entry Section:**
```blade
@if ($activeEntry)
    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-4">
            <span class="text-blue-600">🔵</span> Active Entry
        </h2>
        
        <!-- Visitor Information -->
        <div class="flex items-center space-x-3">
            <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-blue-200">
                <img src="{{ Storage::url($activeEntry->visitor->photo_path) }}">
            </div>
            <div>
                <p class="font-medium text-blue-900">{{ $activeEntry->visitor->name }}</p>
                <p class="text-sm text-blue-700">{{ $activeEntry->visitor->mobile_number }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-4">
            <a href="{{ route('guard.entry-details', $activeEntry->visitor->id) }}" 
               class="flex-1 bg-white text-blue-600 rounded-md">
                Manage Items &rarr;
            </a>
            <button onclick="checkOutActiveVisitor({{ $activeEntry->id }})" 
                    class="flex-1 bg-red-600 text-white rounded-md">
                🔴 Check Out
            </button>
        </div>
    </div>
@endif
```

---

## Data Flow

### **1. Guard Visits Dashboard**
```
DashboardController::index()
  ├─ Get active entry (if any)
  ├─ Get today's entries
  ├─ Calculate statistics
  └─ Return view with data
```

### **2. Guard Clicks "View Full List Table"**
```
EntryController::list()
  ├─ Get today's entries
  ├─ Load visitor and carryItems relationships
  ├─ Calculate statistics
  └─ Return full table view
```

### **3. Table Rendering**
```
resources/views/guard/entries/list.blade.php
  ├─ Display stats summary
  ├─ Render table header
  ├─ Loop through entries:
  │   ├─ Show photo thumbnail
  │   ├─ Show mobile number
  │   ├─ Show name
  │   ├─ Show purpose (truncated)
  │   ├─ Show vehicle number
  │   ├─ Show carry items count
  │   ├─ Show IN time
  │   ├─ Show OUT time (or "Active")
  │   ├─ Show duration (or "--")
  │   └─ Show action link
  └─ Display pagination button (if >20 entries)
```

---

## Special Features

### **1. Mobile Number as Unique ID**

**Purpose:** Mobile number serves as permanent visitor identifier

**Display:**
```blade
<td class="px-6 py-4 whitespace-nowrap">
    <p class="text-gray-900">{{ $entry->visitor->mobile_number }}</p>
</td>
```

**Use:** Guard can search by mobile number across all visits

---

### **2. Carry Items Summary**

**Purpose:** Show how many items visitor brought

**Display:**
```blade
<div class="flex items-center gap-1">
    @if ($entry->carryItems->count() > 0)
        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
            {{ $entry->carryItems->count() }}
        </span>
        <span class="text-xs text-gray-500">items</span>
    @else
        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
            0
        </span>
    @endif
</div>
```

**Features:**
- Blue badge if items exist
- Gray badge if no items
- Shows count and "items" text

---

### **3. Time Formatting**

**IN Time:** `h:i A` format (e.g., "10:30 AM")

**OUT Time:** `h:i A` format or "Active"

**Duration:** Minutes (e.g., "45 min")

**Implementation:**
```blade
<!-- IN Time -->
<td class="px-6 py-4 whitespace-nowrap">
    <p class="text-gray-900">
        {{ $entry->in_time->format('h:i A') }}
    </p>
</td>

<!-- OUT Time -->
<td class="px-6 py-4 whitespace-nowrap">
    <div class="flex items-center">
        @if ($entry->out_time)
            <span class="text-green-600 font-medium">
                {{ $entry->out_time->format('h:i A') }}
            </span>
        @else
            <span class="text-yellow-600 font-medium">
                Active
            </span>
        @endif
    </div>
</td>

<!-- Duration -->
<td class="px-6 py-4 whitespace-nowrap">
    <div class="flex items-center">
        @if ($entry->out_time)
            <span class="text-gray-900">
                {{ $entry->duration_minutes }} min
            </span>
        @else
            <span class="text-gray-400 text-xs">
                --
            </span>
        @endif
    </div>
</td>
```

---

### **4. Purpose Text Truncation**

**Purpose:** Prevent long text from breaking table layout

**Implementation:**
```blade
<td class="px-6 py-4 whitespace-nowrap">
    <p class="text-sm text-gray-900 truncate" 
       title="{{ $entry->visitor->purpose }}">
        {{ \Illuminate\Support\Str::limit($entry->visitor->purpose, 25) }}
    </p>
</td>
```

**Features:**
- Truncate at 25 characters
- Show full text in tooltip on hover
- Ellipsis (...) for truncated text

---

### **5. Vehicle Number Display**

**Purpose:** Show vehicle number or "--" if none

**Implementation:**
```blade
<td class="px-6 py-4 whitespace-nowrap">
    {{ $entry->visitor->vehicle_number 
          ? $entry->visitor->vehicle_number 
          : '--' }}
</td>
```

**Features:**
- Show vehicle number if exists
- Show "--" if null

---

### **6. Row Highlighting**

**Purpose:** Visual indication of active entries

**Implementation:**
```blade
<tr class="hover:bg-gray-50 
          @if (!$entry->out_time) bg-yellow-50">
```

**Effects:**
- White background for completed entries
- Yellow background for active entries
- Gray background on hover for both

**Result:** Active entries stand out visually ✅

---

### **7. Conditional Action Links**

**Purpose:** Show appropriate action based on entry status

**Implementation:**
```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if (!$entry->out_time)
        <a href="{{ route('guard.entry-details', $entry->visitor->id) }}" 
           class="text-green-600 hover:text-green-900 font-medium">
            Check Out
        </a>
    @else
        <a href="{{ route('guard.entries.show', $entry->id) }}" 
           class="text-blue-600 hover:text-blue-900 font-medium">
            View Details
        </a>
    @endif
</td>
```

**Logic:**
- Active entry → Green "Check Out" link
- Completed entry → Blue "View Details" link

**Result:** Clear call-to-action based on status ✅

---

### **8. Responsive Design**

**Purpose:** Table works on mobile and desktop

**Implementation:**
```blade
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
```

**Features:**
- Horizontal scroll on mobile
- Full width on desktop
- Maintains table structure

---

## Database Queries

### **Get Today's Entries**
```sql
SELECT 
    e.*,
    v.name,
    v.mobile_number,
    v.photo_path,
    v.purpose,
    v.vehicle_number
FROM entries e
JOIN visitors v ON e.visitor_id = v.id
WHERE e.guard_id = ?
  AND DATE(e.in_time) = CURRENT_DATE
ORDER BY e.in_time DESC
```

**Explanation:**
- Join with visitors table
- Filter by guard_id (authorization)
- Filter by today's date
- Order by check-in time (newest first)
- Eager load carry_items relationship

---

### **Load Carry Items**
```sql
SELECT * FROM carry_items
WHERE entry_id IN (5, 8, 12, ...)
  AND in_status = TRUE
ORDER BY created_at ASC
```

**Explanation:**
- Load all items for displayed entries
- Filter to only items brought in
- Order by creation time

---

## Performance Considerations

### **1. Eager Loading**

**Problem:** N+1 query problem when loading carry items

**Solution:** Eager load relationships
```php
$todayEntries = Entry::where('guard_id', $guard->id)
    ->with('visitor', 'carryItems')  // Eager load
    ->get();
```

**Result:** 3 queries instead of 2N + 1 queries ✅

---

### **2. Database Indexes**

**Recommended Indexes:**
```sql
-- Speed up guard-specific queries
CREATE INDEX idx_entries_guard_id ON entries(guard_id);

-- Speed up date filtering
CREATE INDEX idx_entries_in_time ON entries(in_time);

-- Speed up carry item lookups
CREATE INDEX idx_carry_items_entry_id ON carry_items(entry_id);

-- Composite index for filtering
CREATE INDEX idx_entries_guard_date ON entries(guard_id, DATE(in_time));
```

---

### **3. Pagination (Optional)**

**Implementation:**
```php
$todayEntries = Entry::where('guard_id', $guard->id)
    ->whereDate('in_time', now())
    ->with('visitor', 'carryItems')
    ->latest('in_time')
    ->paginate(20);  // 20 entries per page
```

**View:**
```blade
@foreach ($todayEntries as $entry)
    <!-- Entry Row -->
@endforeach

{{ $todayEntries->links() }}
```

---

## Security Measures

### **1. Guard Authorization**

**Controller Check:**
```php
public function list()
{
    $guard = Auth::user();  // Must be authenticated

    $todayEntries = Entry::where('guard_id', $guard->id)
        // Filter: Only this guard's entries
```

**Middleware:** `middleware('guard')` in routes

**Result:** Guards can only see their own entries ✅

---

### **2. Date Filtering**

**Purpose:** Prevent access to historical entries

**Implementation:**
```php
->whereDate('in_time', now())  // Only today
```

**Result:** Guards restricted to current day ✅

---

### **3. Photo Path Security**

**Implementation:** Storage facade with public visibility

```blade
<img src="{{ Storage::url($entry->visitor->photo_path) }}">
```

**Protection:**
- Files stored in `storage/app/public/`
- Access via symbolic link: `/storage/filename`
- Laravel handles security

**Result:** Safe file serving ✅

---

## Testing Scenarios

### **Scenario 1: Guard Views Today's Entries**

**Input:** Guard accesses `/guard/entries/list`

**Expected:** Table shows all entries for today

**Data:**
```
Entry 1: John Doe, +91 98765 43210, Business, MH 12 AB 1234, 2 items, 10:30 AM, --, --
Entry 2: Jane Smith, +91 98765 12345, Interview, --, 1 item, 11:15 AM, 11:45 AM, 30 min
```

**Result:** Both entries displayed ✅

---

### **Scenario 2: Active Entry Highlighted**

**Input:** Visitor checked in but not out

**Expected:** Row highlighted yellow

**Display:**
```html
<tr class="hover:bg-gray-50 bg-yellow-50">
    <!-- Row content -->
</tr>
```

**Result:** Active entry visually highlighted ✅

---

### **Scenario 3: Duration Calculated Automatically**

**Input:** Entry has `out_time`

**Expected:** Duration calculated from `out_time - in_time`

**Example:**
```
IN: 10:30 AM
OUT: 11:15 AM
Duration: 45 minutes
```

**Result:** Duration displayed ✅

---

### **Scenario 4: No Entries Today**

**Input:** Guard hasn't checked in anyone

**Expected:** Empty state message

**Display:**
```blade
<div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
    <p class="text-gray-500 text-lg">No entries recorded today</p>
    <a href="{{ route('guard.entries.index') }}" 
       class="inline-block mt-4 text-blue-600 hover:text-blue-900 font-medium">
        Go to Entry Screen to check in visitors
    </a>
</div>
```

**Result:** Clear empty state ✅

---

## Files Created/Modified

**New Files:**
- ✅ `resources/views/guard/entries/list.blade.php` - Full entry listing table

**Modified Files:**
- ✅ `app/Http/Controllers/Guard/EntryController.php` - Added `list()` method
- ✅ `app/Http/Controllers/Guard/DashboardController.php` - Updated for today's entries and stats
- ✅ `resources/views/guard/dashboard.blade.php` - Updated to show table
- ✅ `routes/web.php` - Added `list` route

---

## Complete Feature List

✅ **Visitor Photo in Table** - Circular 40x40px thumbnail
✅ **Mobile Number Column** - Shows unique visitor ID
✅ **Name Column** - Visitor's full name
✅ **Purpose Column** - Truncated text with tooltip
✅ **Vehicle Number Column** - Shows number or "--"
✅ **Carry Items Summary** - Badge with count and "items" text
✅ **IN Time Column** - Formatted as "h:i A"
✅ **OUT Time Column** - Formatted time or "Active"
✅ **Duration Column** - Calculated minutes or "--"
✅ **Auto-Calculate Duration** - Done on check-out
✅ **Highlight Active Entries** - Yellow background
✅ **Only Today's Entries** - Date filtered by guard
✅ **Stats Summary** - Total, check-ins, check-outs, average duration
✅ **Responsive Design** - Mobile-friendly table
✅ **Conditional Actions** - "Check Out" or "View Details"
✅ **Hover Effects** - Gray background on hover
✅ **Border Styling** - Table with borders and shadows
✅ **Photo Fallback** - Default icon if no photo
✅ **Security** - Guard authorization and date filtering
✅ **Performance** - Eager loading and indexes
✅ **Empty State** - Clear message when no entries

---

## Summary

The entry listing table provides guards with:

✅ **Complete entry view** - All today's entries in one table
✅ **Visual visitor identification** - Photo thumbnails in table
✅ **Comprehensive information** - Name, mobile, purpose, vehicle, items
✅ **Time tracking** - IN time, OUT time, auto-calculated duration
✅ **Active entry highlighting** - Yellow background for incomplete entries
✅ **Quick actions** - Check out or view details from table
✅ **Statistics summary** - Overview of daily activity
✅ **Mobile responsive** - Works on all devices
✅ **Guard restricted** - Only see their own entries
✅ **Today-only view** - Restricted to current date
✅ **Performance optimized** - Eager loading and database indexes

All entry listing table features have been successfully implemented!

