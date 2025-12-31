<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visitor;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with statistics.
     */
    public function index()
    {
        $user = Auth::user();

        // Get statistics
        $stats = [
            'total_visitors' => Visitor::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_guards' => User::where('role', 'guard')->count(),
            'total_entries' => Entry::count(),
            'today_entries' => Entry::whereDate('in_time', today())->count(),
            'active_entries' => Entry::whereNull('out_time')->count(),
            'today_visitors' => Entry::whereDate('in_time', today())->distinct('visitor_id')->count('visitor_id'),
        ];

        // Get subscription statistics
        $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->get();
        $mrr = $activeSubscriptions->where('billing_cycle', 'monthly')->sum('amount');
        $yearlyRevenue = $activeSubscriptions->where('billing_cycle', 'yearly')->sum('amount');
        $arr = ($mrr * 12) + $yearlyRevenue;

        $subscriptionStats = [
            'active_subscriptions' => $activeSubscriptions->count(),
            'mrr' => $mrr,
            'arr' => $arr,
            'total_revenue' => \App\Models\Subscription::whereIn('status', ['active', 'cancelled', 'expired'])->sum('amount'),
        ];

        // Get recent entries
        $recentEntries = Entry::with(['visitor', 'guardUser'])
            ->latest('in_time')
            ->limit(10)
            ->get();

        // Get recent visitors
        $recentVisitors = Visitor::latest('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('user', 'stats', 'subscriptionStats', 'recentEntries', 'recentVisitors'));
    }

    /**
     * Show all visitors.
     */
    public function visitors()
    {
        $visitors = Visitor::latest()->paginate(20);
        return view('admin.visitors', compact('visitors'));
    }

    /**
     * Show all customers.
     */
    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->with([
                'subscriptions' => function ($query) {
                    $query->with('plan')->latest();
                },
                'guards'
            ])
            ->latest()
            ->paginate(20);

        $subscriptionPlans = \App\Models\SubscriptionPlan::where('is_active', true)->get();

        return view('admin.customers', compact('customers', 'subscriptionPlans'));
    }

    /**
     * Show all guards.
     */
    public function guards()
    {
        $guards = User::where('role', 'guard')->latest()->paginate(20);
        return view('admin.guards', compact('guards'));
    }

    /**
     * Show all entries.
     */
    public function entries()
    {
        $entries = Entry::with(['visitor', 'guardUser', 'carryItems'])
            ->latest('in_time')
            ->paginate(20);
        return view('admin.entries', compact('entries'));
    }

    /**
     * Delete a visitor.
     */
    public function deleteVisitor(Visitor $visitor)
    {
        try {
            // Delete associated entries and carry items
            foreach ($visitor->entries as $entry) {
                $entry->carryItems()->delete();
            }
            $visitor->entries()->delete();

            // Delete visitor photo if exists
            if ($visitor->photo_path && \Storage::disk('public')->exists($visitor->photo_path)) {
                \Storage::disk('public')->delete($visitor->photo_path);
            }

            $visitor->delete();

            return redirect()->route('admin.visitors.index')
                ->with('success', 'Visitor deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.visitors.index')
                ->with('error', 'Failed to delete visitor: ' . $e->getMessage());
        }
    }

    /**
     * Delete a customer.
     */
    public function deleteCustomer(User $user)
    {
        try {
            if ($user->role !== 'customer') {
                return redirect()->route('admin.customers')
                    ->with('error', 'Invalid user type.');
            }

            $user->delete();

            return redirect()->route('admin.customers')
                ->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers')
                ->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }

    /**
     * Delete a guard.
     */
    public function deleteGuard(User $user)
    {
        try {
            if ($user->role !== 'guard') {
                return redirect()->route('admin.guards')
                    ->with('error', 'Invalid user type.');
            }

            // Check if guard has any entries
            $entriesCount = Entry::where('guard_id', $user->id)->count();
            if ($entriesCount > 0) {
                return redirect()->route('admin.guards')
                    ->with('error', "Cannot delete guard. They have {$entriesCount} associated entries.");
            }

            $user->delete();

            return redirect()->route('admin.guards')
                ->with('success', 'Guard deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.guards')
                ->with('error', 'Failed to delete guard: ' . $e->getMessage());
        }
    }
}

