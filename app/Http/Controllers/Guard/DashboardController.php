<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the guard dashboard with entry listing.
     */
    public function index()
    {
        $guard = Auth::user();
        
        // Get most recent active entry (if any)
        $activeEntry = \App\Models\Entry::where('guard_id', $guard->id)
            ->whereNull('out_time')
            ->with('visitor', 'carryItems')
            ->latest('in_time')
            ->first();

        // Get all today's entries for this guard
        $todayEntries = \App\Models\Entry::where('guard_id', $guard->id)
            ->whereDate('in_time', now())
            ->with('visitor', 'carryItems')
            ->latest('in_time')
            ->get();

        // Calculate stats
        $checkInCount = $todayEntries->count();
        $checkOutCount = $todayEntries->whereNotNull('out_time')->count();
        $totalDuration = $todayEntries->whereNotNull('duration_minutes')->sum('duration_minutes');
        $avgDuration = $checkOutCount > 0 ? number_format($totalDuration / $checkOutCount, 0) : 0;

        return view('guard.dashboard', [
            'guard' => $guard,
            'activeEntry' => $activeEntry,
            'todayEntries' => $todayEntries,
            'checkInCount' => $checkInCount,
            'checkOutCount' => $checkOutCount,
            'totalDuration' => $totalDuration,
            'avgDuration' => $avgDuration,
        ]);
    }
}
