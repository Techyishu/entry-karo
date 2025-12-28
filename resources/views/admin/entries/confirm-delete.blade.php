@extends('layouts.app')

@section('title', 'Delete Entry - Admin Dashboard - Entry Karo')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Delete Entry</h1>
                <a href="{{ route('admin.entries.show', $entry->id) }}" class="text-blue-600 hover:text-blue-900">
                    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7 7m0 0l-7 7 7 7m0 0l-7 7 7" />
                    </svg>
                    Cancel
                </a>
            </div>

            <!-- Warning Message -->
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-red-900 mb-2">
                            ⚠ Confirm Deletion
                        </h2>
                        <p class="text-red-800">
                            Are you sure you want to delete this entry? This action cannot be undone.
                            All related carry items will be permanently deleted.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Entry Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Entry Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Visitor Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Visitor</h4>
                        <div class="space-y-2">
                            @if ($entry->visitor->photo_path)
                                <div class="flex items-center space-x-3">
                                    <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200">
                                        <img src="{{ Storage::url($entry->visitor->photo_path) }}"
                                            alt="{{ $entry->visitor->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $entry->visitor->mobile_number }}</p>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $entry->visitor->mobile_number }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm text-gray-500">Purpose:</p>
                                <p class="text-gray-900">{{ $entry->visitor->purpose }}</p>
                            </div>

                            @if ($entry->visitor->vehicle_number)
                                <div>
                                    <p class="text-sm text-gray-500">Vehicle:</p>
                                    <p class="text-gray-900">{{ $entry->visitor->vehicle_number }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Entry Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Entry</h4>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-500">Guard:</p>
                                <p class="text-gray-900">{{ $entry->guardUser->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Check-in Time:</p>
                                <p class="text-gray-900">{{ $entry->in_time->format('h:i A') }}</p>
                            </div>
                            @if ($entry->out_time)
                                <div>
                                    <p class="text-sm text-gray-500">Check-out Time:</p>
                                    <p class="text-green-600">{{ $entry->out_time->format('h:i A') }}</p>
                                </div>
                            @endif
                            @if ($entry->duration_minutes)
                                <div>
                                    <p class="text-sm text-gray-500">Duration:</p>
                                    <p class="text-gray-900">{{ $entry->duration_minutes }} minutes</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Carry Items Warning -->
                @if ($entry->carryItems->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="h-6 w-6 text-yellow-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <p class="text-yellow-900 font-medium">
                                        {{ $entry->carryItems->count() }} carry items will be deleted
                                    </p>
                                    <p class="text-yellow-800 text-sm mt-1">
                                        These items will be permanently removed and cannot be recovered.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- What Happens on Delete -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-blue-900 mb-4">What happens when you delete?</h4>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <div>
                            <p class="font-medium text-blue-900">Entry will be permanently deleted</p>
                            <p class="text-sm text-blue-700">The entry record will be removed from the database</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <div>
                            <p class="font-medium text-blue-900">All carry items will be deleted</p>
                            <p class="text-sm text-blue-700">{{ $entry->carryItems->count() }} items will be permanently
                                removed</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium text-blue-900">Visitor record will NOT be deleted</p>
                            <p class="text-sm text-blue-700">The visitor profile will remain in the system for future visits
                            </p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-gray-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium text-blue-900">Action will be logged</p>
                            <p class="text-sm text-blue-700">Deletion will be recorded in the system logs</p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Confirmation Form -->
            <form action="{{ route('admin.entries.destroy', $entry->id) }}" method="POST" class="mt-8">
                @csrf
                @method('DELETE')

                <!-- Cancel Button -->
                <div class="mb-4">
                    <a href="{{ route('admin.entries.show', $entry->id) }}"
                        class="block w-full py-3 px-4 border-2 border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center">
                        Cancel - Keep Entry
                    </a>
                </div>

                <!-- Delete Button -->
                <button type="submit"
                    class="block w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    🗑 Yes, Delete This Entry
                </button>

                <p class="mt-4 text-center text-sm text-gray-500">
                    This action cannot be undone. Please confirm you want to proceed.
                </p>
            </form>

            <!-- Additional Info -->
            <div class="mt-8 pt-8 border-t">
                <div class="text-sm text-gray-600">
                    <p class="font-medium text-gray-900">Need help?</p>
                    <p class="mt-2">
                        If you're unsure about deleting this entry, please contact your system administrator for guidance.
                        Deleting entries should only be done in cases of data corruption or testing errors.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection