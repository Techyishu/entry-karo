@extends('layouts.app')

@section('title', 'Entry Details - Admin Dashboard - Entry Karo')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Entry Details</h1>
                    <p class="text-gray-600">View all information about this visitor entry</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.entries.index') }}" class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Back to Entries
                    </a>
                    <!-- Delete Button (Super Admin Only) -->
                    @if (Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.entries.confirm-delete', $entry->id) }}"
                            class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Entry
                        </a>
                    @endif
                </div>
            </div>

            <!-- Entry Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Visitor Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Visitor Information</h2>

                    <div class="flex items-center space-x-4 mb-6">
                        @if ($entry->visitor->photo_path)
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200">
                                <img src="{{ Storage::url($entry->visitor->photo_path) }}" alt="{{ $entry->visitor->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900 text-lg">{{ $entry->visitor->name }}</p>
                            <p class="text-gray-600">{{ $entry->visitor->mobile_number }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Address:</p>
                            <p class="text-gray-900">{{ $entry->visitor->address }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Purpose:</p>
                            <p class="text-gray-900">{{ $entry->visitor->purpose }}</p>
                        </div>
                        @if ($entry->visitor->vehicle_number)
                            <div>
                                <p class="text-sm text-gray-500">Vehicle Number:</p>
                                <p class="text-gray-900">{{ $entry->visitor->vehicle_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Entry Details -->
                <div class="bg-green-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4">Entry Details</h2>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-blue-700">Guard:</p>
                            <p class="text-blue-900 font-medium">{{ $entry->guardUser->name }}</p>
                            <p class="text-sm text-blue-600">{{ $entry->guardUser->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Check-in Time:</p>
                            <p class="text-blue-900 font-medium">{{ $entry->in_time->format('h:i A') }}</p>
                            <p class="text-sm text-blue-600">{{ $entry->in_time->format('F j, Y') }}</p>
                        </div>
                        @if ($entry->out_time)
                            <div>
                                <p class="text-sm text-blue-700">Check-out Time:</p>
                                <p class="text-blue-900 font-medium text-green-600">{{ $entry->out_time->format('h:i A') }}</p>
                                <p class="text-sm text-blue-600">{{ $entry->out_time->format('F j, Y') }}</p>
                            </div>
                        @else
                            <div>
                                <p class="text-sm text-blue-700">Status:</p>
                                <span
                                    class="inline-flex px-2 py-1 rounded-full bg-green-200 text-green-800 text-xs font-medium">
                                    Active
                                </span>
                            </div>
                        @endif
                        @if ($entry->duration_minutes)
                            <div>
                                <p class="text-sm text-blue-700">Duration:</p>
                                <p class="text-blue-900 font-medium">{{ $entry->duration_minutes }} minutes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Carry Items Section -->
            <div class="border-t pt-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Carry Items ({{ $entry->carryItems->count() }})
                    </h2>
                </div>

                @if ($entry->carryItems->count() > 0)
                    <div class="space-y-3">
                        @foreach ($entry->carryItems->sortBy('created_at') as $item)
                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                <div class="flex-1 flex items-center space-x-4">
                                    @if ($item->item_photo_path)
                                        <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ Storage::url($item->item_photo_path) }}" alt="{{ $item->item_name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->item_name }}</p>
                                        <p class="text-sm text-gray-600">
                                            Type: {{ ucfirst($item->item_type) }} &middot;
                                            Quantity: {{ $item->quantity }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $item->in_status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $item->in_status ? 'In' : 'Out' }}
                                    </span>
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $item->out_status ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $item->out_status ? 'Out' : 'Inside' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                        <p class="text-gray-500 text-lg">No carry items recorded</p>
                    </div>
                @endif
            </div>

            <!-- Timestamps -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">System Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Created At:</p>
                        <p class="text-gray-900">{{ $entry->created_at->format('M j, Y - h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Updated At:</p>
                        <p class="text-gray-900">{{ $entry->updated_at->format('M j, Y - h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="border-t pt-6 mt-6">
                <div class="flex gap-4">
                    <a href="{{ route('admin.entries.index') }}"
                        class="flex-1 px-4 py-3 bg-white border-2 border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center">
                        Back to Entries
                    </a>
                    <!-- Delete Button (Super Admin Only) -->
                    @if (Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.entries.confirm-delete', $entry->id) }}"
                            class="flex-1 px-4 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 font-medium text-center">
                            🗑 Delete This Entry
                        </a>
                    @endif
                </div>
            </div>

            <div class="border-t pt-6 mt-6">
                <p class="text-sm text-gray-500">
                    Admin can view and manage all entries. Only Super Admin can delete entries.
                    Deleting an entry will permanently remove the entry and all associated carry items,
                    but the visitor record will remain in the system.
                </p>
            </div>
        </div>
    </div>
@endsection