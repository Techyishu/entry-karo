@extends('layouts.app')

@section('title', 'Visitor Details - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <!-- Header with Back Button -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('guard.entries.index') }}"
                            class="flex items-center text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7 7m0 0l-7-7 7 7" />
                            </svg>
                            Back to Entry Screen
                        </a>
                        <a href="{{ route('guard.dashboard') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            📊 Dashboard
                        </a>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Visitor Details</h1>
                </div>

                <!-- Visitor Information -->
                <div class="border-b pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Visitor Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-500">Mobile Number (Permanent ID)</span>
                                <p class="text-gray-900 font-medium mt-1">{{ $visitor->mobile_number }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Full Name</span>
                                <p class="text-gray-900 mt-1">{{ $visitor->name }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-500">Address</span>
                                <p class="text-gray-900 mt-1">{{ $visitor->address }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Purpose</span>
                                <p class="text-gray-900 mt-1">{{ $visitor->purpose }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @if ($visitor->vehicle_number)
                            <div>
                                <span class="text-gray-500">Vehicle Number</span>
                                <p class="text-gray-900 mt-1">{{ $visitor->vehicle_number }}</p>
                            </div>
                        @endif

                        @if ($visitor->photo_path)
                            <div>
                                <span class="text-gray-500">Photo</span>
                                <div class="mt-2">
                                    <img src="{{ Storage::url($visitor->photo_path) }}" alt="{{ $visitor->name }}"
                                        class="w-48 h-48 object-cover rounded-lg border-2 border-gray-200">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Active Entry Information (if visitor is currently checked in) -->
                @if ($activeEntry)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-blue-900 mb-4">
                            <span class="text-blue-600">🔵</span> Active Entry
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-blue-700">Check-in Time</span>
                                <p class="text-blue-900 font-medium mt-1">{{ $activeEntry->in_time->format('h:i A') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-blue-700">Duration So Far</span>
                                <p class="text-blue-900 font-medium mt-1">{{ $activeEntry->in_time->diffInMinutes(now()) }}
                                    minutes</p>
                            </div>
                            <div>
                                <span class="text-sm text-blue-700">Items Brought In</span>
                                <p class="text-blue-900 font-medium mt-1">
                                    {{ $activeEntry->carryItems->where('in_status', true)->count() }} items
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 pt-6 border-t border-blue-200">
                            <a href="{{ route('guard.entries.show', $activeEntry->id) }}"
                                class="block w-full px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center font-medium">
                                Manage Items & View Full Entry
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">
                            <span class="text-gray-500">⚠</span> No Active Entry
                        </h2>
                        <p class="text-gray-600 mb-4">This visitor is not currently checked in.</p>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('guard.entries.index') }}"
                                class="block w-full px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-center font-medium">
                                🟢 Check In This Visitor Now
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection