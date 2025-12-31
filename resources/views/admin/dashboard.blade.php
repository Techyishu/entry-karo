@extends('layouts.app')

@section('title', 'Admin Dashboard - Entry Karo')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-500 mt-1">Welcome back, {{ $user->name }}!</p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700">
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Visitors -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Visitors</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_visitors']) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <a href="{{ route('admin.visitors.index') }}" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                    View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Total Customers -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Customers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_customers']) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                <a href="{{ route('admin.customers') }}" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                    View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Total Guards -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Guards</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_guards']) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <a href="{{ route('admin.guards') }}" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                    View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

             <!-- Today's Entries -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today's Entries</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['today_entries']) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <a href="{{ route('admin.entries.index') }}" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                    View Live <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Subscription Revenue Section (New) -->
        @if(isset($subscriptionStats))
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 px-1">Financial Overview</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Active Subs -->
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                        <p class="text-green-100 text-sm font-medium mb-1">Active Subscriptions</p>
                        <p class="text-3xl font-bold">{{ number_format($subscriptionStats['active_subscriptions']) }}</p>
                    </div>
                    
                    <!-- MRR -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <p class="text-gray-500 text-sm font-medium">Monthly Recurring (MRR)</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $subscriptionStats['mrr'] }}</p>
                    </div>

                    <!-- ARR -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                         <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <p class="text-gray-500 text-sm font-medium">Annual Recurring (ARR)</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $subscriptionStats['arr'] }}</p>
                    </div>

                    <!-- Total Revenue -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                         <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $subscriptionStats['total_revenue'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Entries -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Recent Entries</h2>
                    <a href="{{ route('admin.entries.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">View All</a>
                </div>
                <!-- Desktop Table -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visitor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentEntries as $entry)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                                {{ substr($entry->visitor->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $entry->visitor->purpose }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $entry->in_time->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->out_time)
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Out</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 animate-pulse">Active</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Mobile List -->
                <div class="sm:hidden divide-y divide-gray-100">
                    @foreach ($recentEntries as $entry)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                 <div class="h-10 w-10 rounded-full bg-gray-50 flex items-center justify-center text-sm font-bold text-gray-500">
                                    {{ substr($entry->visitor->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $entry->visitor->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $entry->in_time->format('h:i A') }} • {{ $entry->visitor->purpose }}</p>
                                </div>
                            </div>
                            <div>
                                 @if($entry->out_time)
                                    <span class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-500 font-medium">Out</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-lg bg-green-50 text-green-700 font-medium border border-green-100">Active</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Visitors -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">New Visitors</h2>
                    <a href="{{ route('admin.visitors.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">View All</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($recentVisitors as $visitor)
                        <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition">
                            <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 font-bold">
                                {{ substr($visitor->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $visitor->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $visitor->mobile_number }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $visitor->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection