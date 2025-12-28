@extends('layouts.app')

@section('title', 'Admin Dashboard - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome back, {{ $user->name }}!</p>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Visitors -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Visitors</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_visitors']) }}
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('admin.visitors.index') }}"
                        class="text-sm text-purple-600 hover:text-purple-900 mt-4 inline-block font-medium">
                        View All →
                    </a>
                </div>

                <!-- Total Customers -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Customers</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_customers']) }}
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('admin.customers') }}"
                        class="text-sm text-green-600 hover:text-green-900 mt-4 inline-block font-medium">
                        View All →
                    </a>
                </div>

                <!-- Total Guards -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Guards</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_guards']) }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('admin.guards') }}"
                        class="text-sm text-blue-600 hover:text-blue-900 mt-4 inline-block font-medium">
                        View All →
                    </a>
                </div>

                <!-- Today's Entries -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Today's Entries</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['today_entries']) }}
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('admin.entries.index') }}"
                        class="text-sm text-yellow-600 hover:text-yellow-900 mt-4 inline-block font-medium">
                        View All →
                    </a>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600 font-medium">Active Entries (Checked In)</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($stats['active_entries']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600 font-medium">Total Entries (All Time)</p>
                    <p class="text-2xl font-bold text-gray-600 mt-2">{{ number_format($stats['total_entries']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600 font-medium">Unique Visitors Today</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">{{ number_format($stats['today_visitors']) }}</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Entries -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Entries</h2>
                        <a href="{{ route('admin.entries.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-900 font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentEntries as $entry)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $entry->visitor->mobile_number }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Guard: {{ $entry->guardUser->name }} •
                                        {{ $entry->in_time->format('M d, h:i A') }}
                                    </p>
                                </div>
                                <div>
                                    @if($entry->out_time)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            Checked Out
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No entries yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Visitors -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Recently Registered Visitors</h2>
                        <a href="{{ route('admin.visitors.index') }}"
                            class="text-sm text-purple-600 hover:text-purple-900 font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentVisitors as $visitor)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($visitor->photo_path)
                                        <img src="{{ Storage::url($visitor->photo_path) }}" alt="{{ $visitor->name }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-600 font-medium">{{ substr($visitor->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $visitor->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $visitor->mobile_number }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">
                                    {{ $visitor->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No visitors yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection