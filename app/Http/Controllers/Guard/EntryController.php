<?php

namespace App\Http\Controllers\Guard;

use App\Models\Entry;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EntryController
{
    /**
     * Display the guard entry screen.
     */
    public function index()
    {
        $guard = Auth::user();
        $activeEntry = null;
        $visitor = null;

        // Get the most recent active entry (if any)
        $activeEntry = Entry::where('guard_id', $guard->id)
            ->whereNull('out_time')
            ->with('visitor')
            ->latest('in_time')
            ->first();

        return view('guard.entries.index', compact(
            'guard',
            'activeEntry',
            'visitor'
        ));
    }


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

    /**
     * Search visitor by mobile number.
     */
    public function search(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|max:15',
        ]);

        $guard = Auth::user();
        $mobileNumber = $request->mobile_number;

        // Search for visitor by mobile number
        $visitor = Visitor::where('mobile_number', $mobileNumber)->first();

        // Check if visitor has an active entry (not checked out)
        $activeEntry = null;
        if ($visitor) {
            $activeEntry = Entry::where('visitor_id', $visitor->id)
                ->whereNull('out_time')
                ->with('carryItems')
                ->latest('in_time')
                ->first();
        }

        // If visitor exists, return found with data
        // If visitor doesn't exist, return not found so frontend can redirect to registration
        return response()->json([
            'found' => !is_null($visitor),
            'visitor' => $visitor,
            'active_entry' => $activeEntry,
        ]);
    }

    /**
     * Show visitor registration form (standalone page).
     */
    public function showRegistrationForm(Request $request)
    {
        // Get mobile number from query parameter (for direct access)
        $mobileNumber = $request->query('mobile', '');

        return view('guard.entries.registration', compact('mobileNumber'));
    }

    /**
     * Check-in a visitor (create new entry).
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'purpose' => 'required|string|max:255',
        ]);

        $guard = Auth::user();

        // Check if visitor already has an active entry
        $existingEntry = Entry::where('visitor_id', $request->visitor_id)
            ->whereNull('out_time')
            ->first();

        if ($existingEntry) {
            return response()->json([
                'success' => false,
                'message' => 'Visitor is already checked in. Please check them out first.',
                'active_entry' => $existingEntry,
            ], 400);
        }

        // Create new entry
        $entry = Entry::create([
            'visitor_id' => $request->visitor_id,
            'guard_id' => $guard->id,
            'in_time' => now(),
            'out_time' => null,
            'duration_minutes' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visitor checked in successfully.',
            'entry' => $entry->load('visitor'),
        ]);
    }

    /**
     * Check-out a visitor (update entry with out time).
     * Also marks all carry items as taken out.
     */
    public function checkOut(Request $request)
    {
        try {
            \Log::info('Checkout attempt', ['entry_id' => $request->entry_id, 'user_id' => Auth::id()]);

            $request->validate([
                'entry_id' => 'required|exists:entries,id',
            ]);

            $guard = Auth::user();

            if (!$guard) {
                \Log::error('Checkout failed: No authenticated user');
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required.',
                ], 401);
            }

            $entry = Entry::where('id', $request->entry_id)
                ->where('guard_id', $guard->id)
                ->first();

            if (!$entry) {
                \Log::warning('Checkout failed: Entry not found or unauthorized', [
                    'entry_id' => $request->entry_id,
                    'guard_id' => $guard->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Entry not found or you do not have permission to check out this visitor.',
                ], 404);
            }

            // Verify this entry is not already checked out
            if ($entry->out_time) {
                \Log::info('Checkout attempted on already checked out entry', ['entry_id' => $entry->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Visitor has already been checked out.',
                ], 400);
            }

            // Update entry with out time
            $entry->out_time = now();

            // Calculate duration in minutes and round to integer
            $entry->duration_minutes = (int) round($entry->in_time->diffInMinutes($entry->out_time));

            // Mark all carry items as taken out (only those brought in)
            $itemsUpdated = $entry->carryItems()->where('in_status', true)->update([
                'out_status' => true,
            ]);

            \Log::info('Updated carry items on checkout', ['entry_id' => $entry->id, 'items_updated' => $itemsUpdated]);

            $entry->save();

            \Log::info('Checkout successful', [
                'entry_id' => $entry->id,
                'duration_minutes' => $entry->duration_minutes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visitor checked out successfully. All carry items marked as taken out.',
                'entry' => $entry->load('visitor', 'carryItems'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Checkout validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Checkout exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during checkout. Please try again.',
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show visitor details for check-in.
     */
    public function showVisitorDetails(Visitor $visitor)
    {
        return view('guard.entries.visitor-details', [
            'visitor' => $visitor,
            'activeEntry' => $visitor->activeEntry,
        ]);
    }

    /**
     * Show entry details (for check-out).
     */
    public function showEntryDetails(Entry $entry)
    {
        $guard = Auth::user();

        // Verify guard can only view their own entries
        if ($entry->guard_id !== $guard->id) {
            abort(403, 'You are not authorized to view this entry.');
        }

        return view('guard.entries.entry-details', [
            'entry' => $entry->load('visitor', 'carryItems'),
        ]);
    }

    /**
     * Register a new visitor with mandatory photo upload, optional carry items, and auto check-in.
     */
    public function registerVisitor(Request $request)
    {
        try {
            \Log::info('=== VISITOR REGISTRATION ATTEMPT ===', [
                'guard_id' => Auth::id(),
                'mobile_number' => $request->mobile_number,
                'has_photo' => $request->hasFile('photo'),
                'photo_size' => $request->hasFile('photo') ? $request->file('photo')->getSize() : null,
                'all_fields' => $request->except(['photo', '_token']),
            ]);

            $request->validate([
                'mobile_number' => 'required|string|unique:visitors,mobile_number|max:15',
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'purpose' => 'required|string|max:500',
                'vehicle_number' => 'nullable|string|max:50',
                'photo' => 'nullable|image|max:10240', // Accept any image type, max 10MB
                'auto_checkin' => 'boolean',
                'items' => 'nullable|array',
                'items.*.item_name' => 'required|string|max:255',
                'items.*.item_type' => 'required|in:personal,office,delivery,other',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.item_photo' => 'nullable|image|max:10240',
            ], [
                'photo.max' => 'Visitor photo must not exceed 10MB.',
                'photo.image' => 'Visitor photo must be an image file.',
            ]);

            \Log::info('Validation passed');

            $guard = Auth::user();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Registration validation failed', [
                'errors' => $e->errors(),
                'mobile_number' => $request->mobile_number,
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Registration error during initial validation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        $photoPath = null;

        // Store visitor photo in storage/app/public/visitors
        if ($request->hasFile('photo')) {
            \Log::info('Processing photo upload', [
                'original_name' => $request->file('photo')->getClientOriginalName(),
                'size' => $request->file('photo')->getSize(),
                'mime' => $request->file('photo')->getMimeType(),
            ]);

            $photo = $request->file('photo');

            // Generate unique filename: mobile_number_timestamp.ext
            $fileName = $request->mobile_number . '_' . time() . '.' . $photo->getClientOriginalExtension();

            // Store photo in storage/app/public/visitors
            $photoPath = $photo->storeAs('visitors', $fileName, 'public');

            \Log::info('Photo uploaded successfully', ['path' => $photoPath]);
        } else {
            \Log::info('No photo uploaded');
        }

        // Create visitor
        try {
            \Log::info('Creating visitor record', [
                'mobile_number' => $request->mobile_number,
                'name' => $request->name,
                'has_photo' => !is_null($photoPath),
            ]);

            $visitor = Visitor::create([
                'mobile_number' => $request->mobile_number,
                'name' => $request->name,
                'address' => $request->address,
                'purpose' => $request->purpose,
                'vehicle_number' => $request->vehicle_number,
                'photo_path' => $photoPath,
            ]);

            \Log::info('Visitor created successfully', ['visitor_id' => $visitor->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to create visitor', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        $entry = null;
        $items = [];

        // Auto check-in if requested
        if ($request->boolean('auto_checkin', true)) {
            \Log::info('Auto check-in enabled, creating entry');

            $entry = Entry::create([
                'visitor_id' => $visitor->id,
                'guard_id' => $guard->id,
                'in_time' => now(),
                'out_time' => null,
                'duration_minutes' => null,
            ]);

            \Log::info('Entry created', ['entry_id' => $entry->id]);

            // Add carry items if provided
            if ($request->has('items') && is_array($request->items)) {
                \Log::info('Processing carry items', ['count' => count($request->items)]);

                foreach ($request->items as $index => $itemData) {
                    $itemPhotoPath = null;

                    // Handle item photo if provided
                    if ($request->hasFile("items.{$index}.item_photo")) {
                        $itemPhotoPath = $request->file("items.{$index}.item_photo")
                            ->store('carry-items', 'public');
                    }

                    $item = $entry->carryItems()->create([
                        'item_name' => $itemData['item_name'],
                        'item_type' => $itemData['item_type'],
                        'quantity' => $itemData['quantity'],
                        'item_photo_path' => $itemPhotoPath,
                        'in_status' => true,
                        'out_status' => false,
                    ]);

                    $items[] = $item;
                }

                \Log::info('Carry items created', ['count' => count($items)]);
            }
        }

        \Log::info('=== REGISTRATION COMPLETED SUCCESSFULLY ===', [
            'visitor_id' => $visitor->id,
            'entry_id' => $entry ? $entry->id : null,
            'items_count' => count($items),
        ]);

        return response()->json([
            'success' => true,
            'message' => $entry
                ? 'Visitor registered and checked in successfully!'
                : 'Visitor registered successfully. Mobile number is now their permanent visitor ID.',
            'visitor' => $visitor,
            'entry' => $entry ? $entry->load('carryItems') : null,
            'items_count' => count($items),
        ]);
    }

    /**
     * Store carry item for an entry.
     */
    public function storeCarryItem(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:entries,id',
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|in:personal,office,delivery,other',
            'quantity' => 'required|integer|min:1',
            'item_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'in_status' => 'nullable',
        ]);

        $guard = Auth::user();

        // Verify the entry belongs to this guard
        $entry = Entry::where('id', $request->entry_id)
            ->where('guard_id', $guard->id)
            ->firstOrFail();

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('item_photo')) {
            $photoPath = $request->file('item_photo')->store('carry-items', 'public');
        }

        $carryItem = $entry->carryItems()->create([
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'quantity' => $request->quantity,
            'item_photo_path' => $photoPath,
            'in_status' => $request->boolean('in_status', true),
            'out_status' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Carry item added successfully.',
            'item' => $carryItem,
        ]);
    }

    /**
     * Update carry item status.
     */
    public function updateCarryItem(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'sometimes|required|string|max:255',
            'item_type' => 'sometimes|required|in:personal,office,delivery,other',
            'quantity' => 'sometimes|required|integer|min:1',
            'item_photo_path' => 'nullable|string|max:255',
            'out_status' => 'sometimes|boolean',
        ]);

        $guard = Auth::user();

        // Get carry item and verify it belongs to guard's entry
        $carryItem = \App\Models\CarryItem::with('entry')
            ->whereHas('entry', function ($query) use ($guard) {
                $query->where('guard_id', $guard->id);
            })
            ->findOrFail($id);

        $carryItem->update([
            'item_name' => $request->item_name ?? $carryItem->item_name,
            'item_type' => $request->item_type ?? $carryItem->item_type,
            'quantity' => $request->quantity ?? $carryItem->quantity,
            'item_photo_path' => $request->item_photo_path ?? $carryItem->item_photo_path,
            'out_status' => $request->has('out_status')
                ? $request->boolean('out_status')
                : $carryItem->out_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Carry item updated successfully.',
            'item' => $carryItem->load('entry'),
        ]);
    }

    /**
     * Search for visitor by mobile number and return their details with location history.
     */
    public function searchVisitorByMobile(Request $request)
    {
        $mobileNumber = $request->input('mobile');

        if (!$mobileNumber) {
            return response()->json([
                'found' => false,
                'message' => 'Mobile number is required'
            ], 400);
        }

        $visitor = Visitor::where('mobile_number', $mobileNumber)->first();

        if (!$visitor) {
            return response()->json([
                'found' => false,
                'message' => 'No visitor found with this mobile number'
            ]);
        }

        // Get visitor's location history using the new method
        $locationHistory = $visitor->getLocationHistory();

        return response()->json([
            'found' => true,
            'visitor' => [
                'id' => $visitor->id,
                'mobile_number' => $visitor->mobile_number,
                'name' => $visitor->name,
                'company' => $visitor->company,
                'address' => $visitor->address,
                'purpose' => $visitor->purpose,
                'vehicle_number' => $visitor->vehicle_number,
                'photo_path' => $visitor->photo_path,
            ],
            'history' => $locationHistory,
            'unique_locations' => $visitor->getUniqueLocationsCount(),
        ]);
    }
}
