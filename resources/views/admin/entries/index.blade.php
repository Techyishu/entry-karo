@extends('layouts.app')

@section('title', 'All Entries - Admin Dashboard - Entry Karo')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">All Entries</h1>
                    <p class="text-gray-600 mt-1">View and manage all visitor entries</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-900">
                    Back to Dashboard
                </a>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <form action="{{ route('admin.entries.index') }}" method="GET" class="flex gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by visitor name, mobile or guard name..."
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-sm">
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 shadow-sm transition">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.entries.index') }}"
                            class="px-5 py-2.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 shadow-sm transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Entries Table -->
            <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Visitor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Purpose
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Guard
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                In Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Out Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($entries as $entry)
                            <tr class="hover:bg-gray-50">
                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-gray-900">{{ $entry->in_time->format('M j, Y') }}</p>
                                </td>
                                <!-- Visitor -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @if ($entry->visitor->photo_path)
                                            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                                <img src="{{ Storage::url($entry->visitor->photo_path) }}"
                                                    alt="{{ $entry->visitor->name }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $entry->visitor->mobile_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <!-- Purpose -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">
                                        {{ \Illuminate\Support\Str::limit($entry->visitor->purpose, 30) }}
                                    </p>
                                </td>
                                <!-- Guard -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">{{ $entry->guardUser->name }}</p>
                                </td>
                                <!-- In Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-gray-900">{{ $entry->in_time->format('h:i A') }}</p>
                                </td>
                                <!-- Out Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($entry->out_time)
                                        <p class="text-green-600">{{ $entry->out_time->format('h:i A') }}</p>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <!-- Duration -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($entry->duration_minutes)
                                        <p class="text-gray-900">{{ $entry->duration_minutes }} min</p>
                                    @else
                                        <p class="text-gray-400 text-xs">--</p>
                                    @endif
                                </td>
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($entry->out_time)
                                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.entries.show', $entry->id) }}"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            View
                                        </a>

                                        <!-- Delete Button (Super Admin Only) -->
                                        @if (Auth::user()->isSuperAdmin())
                                            <a href="{{ route('admin.entries.confirm-delete', $entry->id) }}"
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Delete
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <p class="text-gray-500 text-lg">No entries found</p>
                                    <a href="{{ route('guard.entries.index') }}"
                                        class="inline-block mt-4 text-blue-600 hover:text-blue-900 font-medium">
                                        Go to Entry Screen
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($entries->hasPages())
                <div class="mt-6">
                    {{ $entries->appends(request()->query())->links() }}
                </div>
            @endif

            <!-- Back to Dashboard -->
            <div class="mt-6 border-t pt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="block w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection