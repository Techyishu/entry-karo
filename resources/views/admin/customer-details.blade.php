@extends('layouts.app')

@section('title', 'Customer Details - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Customer Details</h1>
                    <p class="text-gray-600 mt-2">{{ $customer->name }} ({{ $customer->email }})</p>
                </div>
                <a href="{{ route('admin.customers') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    ← Back to Customers
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600 font-medium">Total Guards</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalGuards }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600 font-medium">Total Entries</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($totalEntries) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600 font-medium">Member Since</p>
                    <p class="text-xl font-bold text-gray-900 mt-2">{{ $customer->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Subscriptions Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">💳 Subscriptions</h2>
                    <button onclick="document.getElementById('assignSubscriptionModal').classList.remove('hidden')"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        + Assign Subscription
                    </button>
                </div>

                @if($customer->subscriptions->count() > 0)
                    <div class="space-y-4">
                        @foreach($customer->subscriptions as $subscription)
                            <div
                                class="border border-gray-200 rounded-lg p-4 {{ $subscription->status === 'active' ? 'bg-green-50' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $subscription->plan->name }}</h3>
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-medium
                                                            {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $subscription->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                            {{ $subscription->status === 'expired' ? 'bg-gray-100 text-gray-800' : '' }}
                                                            {{ $subscription->status === 'trial' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </div>
                                        <div class="mt-2 grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-600">Amount</p>
                                                <p class="font-medium text-gray-900">₹{{ number_format($subscription->amount, 2) }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Billing Cycle</p>
                                                <p class="font-medium text-gray-900">{{ ucfirst($subscription->billing_cycle) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Start Date</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $subscription->start_date->format('M d, Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Next Billing</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('M d, Y') : 'N/A' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Plan Limits</p>
                                                <p class="font-medium text-gray-900 text-xs">
                                                    {{ $subscription->plan->entries_display }} entries •
                                                    {{ $subscription->plan->guards_display }} guards •
                                                    {{ $subscription->plan->gate_logins_display }} logins
                                                </p>
                                            </div>
                                        </div>
                                        @if($subscription->notes)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-600">Notes: {{ $subscription->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex gap-2">
                                        <!-- Status Update Form -->
                                        @if($subscription->status === 'active')
                                            <form action="{{ route('admin.subscription.update-status', $subscription) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to cancel this subscription?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="text-orange-600 hover:text-orange-900 text-sm font-medium">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.subscription.destroy', $subscription) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this subscription?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No subscriptions assigned yet</p>
                        <button onclick="document.getElementById('assignSubscriptionModal').classList.remove('hidden')"
                            class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Assign First Subscription
                        </button>
                    </div>
                @endif
            </div>

            <!-- Guards List -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">👮 Guards ({{ $totalGuards }})</h2>
                @if($customer->guards->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($customer->guards as $guard)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="font-medium text-gray-900">{{ $guard->name }}</p>
                                <p class="text-sm text-gray-600">{{ $guard->email ?? 'No email' }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $guard->entries->count() }} entries</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No guards assigned</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Assign Subscription Modal -->
    <div id="assignSubscriptionModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border max-w-4xl shadow-2xl rounded-xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Assign Subscription Plan</h3>
                <button type="button"
                    onclick="document.getElementById('assignSubscriptionModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            <form action="{{ route('admin.subscription.assign', $customer) }}" method="POST">
                @csrf

                <!-- Plan Cards -->
                <label class="block text-sm font-medium text-gray-700 mb-3">Choose a Plan</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    @foreach($subscriptionPlans as $index => $plan)
                        <label class="cursor-pointer">
                            <input type="radio" name="subscription_plan_id" value="{{ $plan->id }}"
                                class="hidden peer" {{ $index === 0 ? 'checked' : '' }} required>
                            <div class="rounded-xl p-5 border-2 transition-all
                                {{ $plan->slug === 'enterprise' ? 'bg-gradient-to-br from-slate-900 to-slate-800 text-white border-slate-700' : 'bg-white border-gray-200' }}
                                peer-checked:border-green-500 peer-checked:shadow-lg peer-checked:ring-2 peer-checked:ring-green-200
                                hover:shadow-md">

                                {{-- Plan badge --}}
                                @if($plan->slug === 'pro')
                                    <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full mb-2">POPULAR</span>
                                @elseif($plan->slug === 'enterprise')
                                    <span class="inline-block bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">BEST VALUE</span>
                                @endif

                                <h4 class="text-lg font-bold {{ $plan->slug === 'enterprise' ? 'text-white' : 'text-gray-900' }}">{{ $plan->name }}</h4>

                                <div class="mt-2 mb-4">
                                    <span class="text-3xl font-extrabold {{ $plan->slug === 'enterprise' ? 'text-amber-400' : ($plan->slug === 'pro' ? 'text-green-600' : 'text-gray-900') }}">
                                        ₹{{ number_format($plan->price) }}
                                    </span>
                                    <span class="{{ $plan->slug === 'enterprise' ? 'text-slate-400' : 'text-gray-500' }} text-sm">/month</span>
                                </div>

                                <ul class="space-y-2 text-sm {{ $plan->slug === 'enterprise' ? 'text-slate-300' : 'text-gray-600' }}">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 {{ $plan->slug === 'enterprise' ? 'text-amber-400' : 'text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $plan->entries_display }} Entries/Month
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 {{ $plan->slug === 'enterprise' ? 'text-amber-400' : 'text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $plan->guards_display }} Guards
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 {{ $plan->slug === 'enterprise' ? 'text-amber-400' : 'text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $plan->gate_logins_display }} Gate Logins
                                    </li>
                                    @if($plan->slug === 'pro' || $plan->slug === 'enterprise')
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $plan->slug === 'enterprise' ? 'text-amber-400' : 'text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Priority Support
                                        </li>
                                    @endif
                                </ul>

                                {{-- Selection indicator --}}
                                <div class="mt-4 text-center">
                                    <span class="hidden peer-checked:inline-block text-green-600 font-semibold text-sm">✓ Selected</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Other fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" required value="{{ date('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select name="billing_cycle" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly (10% discount)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <input type="text" name="notes" placeholder="Any notes..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button"
                        onclick="document.getElementById('assignSubscriptionModal').classList.add('hidden')"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition shadow-sm">
                        Assign Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection