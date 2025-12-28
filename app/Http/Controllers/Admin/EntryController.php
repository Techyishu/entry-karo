<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EntryController extends Controller
{
    /**
     * Display all entries (admin view).
     */
    public function index()
    {
        $entries = Entry::with('visitor', 'guardUser', 'carryItems')
            ->orderBy('in_time', 'desc')
            ->paginate(50);

        return view('admin.entries.index', compact('entries'));
    }

    /**
     * Show entry details (admin view).
     */
    public function show(Entry $entry)
    {
        $entry->load('visitor', 'guardUser', 'carryItems');

        return view('admin.entries.show', compact('entry'));
    }

    /**
     * Delete an entry with cascade deletion of carry items.
     * Only accessible by super_admin.
     */
    public function destroy(Request $request, Entry $entry)
    {
        // Verify user is super admin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access. Only Super Admin can delete entries.');
        }

        // Log deletion details for auditing
        $visitorName = $entry->visitor->name;
        $guardName = $entry->guardUser->name;
        $inTime = $entry->in_time->format('Y-m-d H:i:s');
        $itemsCount = $entry->carryItems->count();

        // Delete all related carry items
        // This is handled automatically by database cascade delete,
        // but we'll explicitly do it for clarity and audit
        $entry->carryItems()->delete();

        // Delete the entry
        $entry->delete();

        // Log the deletion
        \Log::info('Entry deleted', [
            'entry_id' => $entry->id,
            'visitor' => $visitorName,
            'guard' => $guardName,
            'in_time' => $inTime,
            'carry_items_deleted' => $itemsCount,
            'deleted_by' => Auth::user()->name,
            'deleted_at' => now(),
        ]);

        return redirect()
            ->route('admin.entries.index')
            ->with('success', sprintf(
                'Entry deleted successfully. Visitor: %s, Items deleted: %d',
                $visitorName,
                $itemsCount
            ));
    }

    /**
     * Show delete confirmation page.
     */
    public function confirmDelete(Entry $entry)
    {
        // Verify user is super admin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access. Only Super Admin can delete entries.');
        }

        $entry->load('visitor', 'guardUser', 'carryItems');

        return view('admin.entries.confirm-delete', compact('entry'));
    }
}
