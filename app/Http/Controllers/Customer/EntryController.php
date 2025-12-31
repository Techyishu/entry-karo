<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all guard IDs belonging to this customer
        $guardIds = Auth::user()->guards()->pluck('id');

        // Fetch entries created by these guards
        $query = Entry::whereIn('guard_id', $guardIds)
            ->with(['visitor', 'guardUser', 'carryItems'])
            ->latest();

        // Optional: Add search functionality if needed later
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        $entries = $query->paginate(10);

        return view('customer.entries.index', compact('entries'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        // Ensure the entry belongs to a guard owned by this customer
        $guardIds = Auth::user()->guards()->pluck('id');

        if (!$guardIds->contains($entry->guard_id)) {
            abort(403, 'Unauthorized access to this entry.');
        }

        $entry->load(['visitor', 'guardUser', 'carryItems']);

        return view('customer.entries.show', compact('entry'));
    }
}
