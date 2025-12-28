@extends('layouts.app')

@section('title', 'Guard Details - Customer Dashboard - Entry Karo')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Guard Details</h1>
                    <p class="text-gray-600 mt-1">View and manage guard information</p>
                </div>
                <a href="{{ route('customer.dashboard') }}" class="text-blue-600 hover:text-blue-900">
                    <svg class="w-5 h-5 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="h-6 w-6 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Guard Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Basic Info -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="text-gray-900 font-medium">{{ $guard->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-gray-900">{{ $guard->email ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Mobile Number</p>
                            <p class="text-gray-900">{{ $guard->mobile_number ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Role</p>
                            <span class="inline-flex px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                                Guard
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4">Statistics</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-blue-700">Total Entries</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $guard->entries->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Active Entries (Today)</p>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ $guard->entries()->whereDate('in_time', now())->whereNull('out_time')->count() }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Account Created</p>
                            <p class="text-blue-900">{{ $guard->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Entries -->
            <div class="border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Entries (Last 10)</h2>

                @if ($guard->entries->count() > 0)
                    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Visitor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        In Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Out Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($guard->entries()->with('visitor')->latest('in_time')->limit(10)->get() as $entry)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900">{{ $entry->in_time->format('M j, Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900">{{ $entry->in_time->format('h:i A') }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($entry->out_time)
                                                <p class="text-sm text-green-600">{{ $entry->out_time->format('h:i A') }}</p>
                                            @else
                                                <span class="text-sm text-gray-400">--</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($entry->out_time)
                                                <span
                                                    class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">Completed</span>
                                            @else
                                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                        <p class="text-gray-500">No entries recorded yet</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="border-t pt-6 mt-6">
                <div class="flex gap-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="flex-1 px-4 py-3 bg-white border-2 border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center">
                        Back to Dashboard
                    </a>
                    <a href="{{ route('customer.guards.edit', $guard->id) }}"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium text-center">
                        Edit Guard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection