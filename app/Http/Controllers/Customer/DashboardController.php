<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function index()
    {
        $customer = Auth::user();

        // Get all guards belonging to this customer
        $guards = $customer->guards()->with('entries')->get();

        // Get all guard IDs for this customer
        $guardIds = $guards->pluck('id');

        // Get all entries made by this customer's guards (paginated)
        $entries = \App\Models\Entry::with(['visitor', 'guardUser', 'carryItems'])
            ->whereIn('guard_id', $guardIds)
            ->orderBy('in_time', 'desc')
            ->paginate(20);

        // Get stats
        $totalEntries = \App\Models\Entry::whereIn('guard_id', $guardIds)->count();
        $todayEntries = \App\Models\Entry::whereIn('guard_id', $guardIds)
            ->whereDate('in_time', now())
            ->count();
        $activeEntries = \App\Models\Entry::whereIn('guard_id', $guardIds)
            ->whereNull('out_time')
            ->count();

        return view('customer.dashboard', [
            'customer' => $customer,
            'guards' => $guards,
            'entries' => $entries,
            'totalEntries' => $totalEntries,
            'todayEntries' => $todayEntries,
            'activeEntries' => $activeEntries,
        ]);
    }
}

