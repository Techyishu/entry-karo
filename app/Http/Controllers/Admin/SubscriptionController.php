<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Show customer details with subscription management.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers')
                ->with('error', 'Invalid customer');
        }

        $customer->load(['subscriptions.plan', 'guards.entries']);
        $subscriptionPlans = SubscriptionPlan::where('is_active', true)->get();

        // Get guard stats
        $totalGuards = $customer->guards()->count();
        $totalEntries = \App\Models\Entry::whereIn('guard_id', $customer->guards()->pluck('id'))->count();

        return view('admin.customer-details', compact('customer', 'subscriptionPlans', 'totalGuards', 'totalEntries'));
    }

    /**
     * Assign a subscription to a customer.
     */
    public function assign(Request $request, User $customer)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'required|date',
            'billing_cycle' => 'required|in:monthly,yearly',
            'notes' => 'nullable|string',
        ]);

        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers')
                ->with('error', 'Invalid customer');
        }

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        // Calculate amount based on billing cycle
        $amount = $plan->price;
        if ($request->billing_cycle === 'yearly') {
            $amount = $plan->price * 12 * 0.9; // 10% discount for yearly
        }

        // Create subscription
        $subscription = new Subscription();
        $subscription->user_id = $customer->id;
        $subscription->subscription_plan_id = $plan->id;
        $subscription->status = 'active';
        $subscription->start_date = $request->start_date;
        $subscription->amount = $amount;
        $subscription->billing_cycle = $request->billing_cycle;
        $subscription->notes = $request->notes;

        // Calculate next billing date
        $startDate = \Carbon\Carbon::parse($request->start_date);
        if ($request->billing_cycle === 'monthly') {
            $subscription->next_billing_date = $startDate->copy()->addMonth()->toDateString();
        } else {
            $subscription->next_billing_date = $startDate->copy()->addYear()->toDateString();
        }

        $subscription->save();

        return redirect()->route('admin.customer.show', $customer)
            ->with('success', 'Subscription assigned successfully!');
    }

    /**
     * Update subscription status.
     */
    public function updateStatus(Request $request, Subscription $subscription)
    {
        $request->validate([
            'status' => 'required|in:active,cancelled,expired,trial',
        ]);

        $subscription->status = $request->status;

        if ($request->status === 'cancelled' || $request->status === 'expired') {
            $subscription->end_date = now()->toDateString();
        }

        $subscription->save();

        return redirect()->back()
            ->with('success', 'Subscription status updated successfully!');
    }

    /**
     * Delete a subscription.
     */
    public function destroy(Subscription $subscription)
    {
        $customerId = $subscription->user_id;
        $subscription->delete();

        return redirect()->route('admin.customer.show', $customerId)
            ->with('success', 'Subscription deleted successfully!');
    }
}
