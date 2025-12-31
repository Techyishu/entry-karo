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
                                        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
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
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Subscription</h3>
                <form action="{{ route('admin.subscription.assign', $customer) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subscription Plan</label>
                            <select name="subscription_plan_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($subscriptionPlans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ₹{{ number_format($plan->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date" required value="{{ date('Y-m-d') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Billing Cycle</label>
                            <select name="billing_cycle" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly (10% discount)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button"
                            onclick="document.getElementById('assignSubscriptionModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Assign Subscription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection