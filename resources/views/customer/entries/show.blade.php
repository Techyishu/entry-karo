@extends('layouts.app')

@section('title', 'Entry Details - Entry Karo')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.entries.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Entry Details</h1>
                    <p class="text-sm text-gray-500 mt-1">Entry #{{ $entry->id }}</p>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @if ($entry->out_time)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Visitor Checked Out</p>
                            <p class="text-sm text-gray-500">Duration: {{ $entry->duration_minutes }} minutes</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center relative">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-green-900">Currently Inside</p>
                            <p class="text-sm text-green-700">Checked in {{ $entry->in_time->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Visitor Information Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Visitor Information</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-6">
                    <!-- Photo -->
                    @if ($entry->visitor->image_path)
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $entry->visitor->image_path) }}" alt="{{ $entry->visitor->name }}" class="w-32 h-32 rounded-2xl object-cover border-2 border-gray-200">
                        </div>
                    @else
                        <div class="flex-shrink-0 w-32 h-32 rounded-2xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    @endif
                    
                    <!-- Details -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Name</p>
                            <p class="text-lg font-bold text-gray-900">{{ $entry->visitor->name }}</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Mobile</p>
                                <p class="text-sm text-gray-900">{{ $entry->visitor->mobile_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Purpose</p>
                                <p class="text-sm text-gray-900">{{ $entry->visitor->purpose ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Address</p>
                            <p class="text-sm text-gray-900">{{ $entry->visitor->address ?? 'N/A' }}</p>
                        </div>
                        @if ($entry->visitor->vehicle_number)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Vehicle</p>
                                <p class="text-sm text-gray-900">{{ $entry->visitor->vehicle_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Carry Items Card -->
        @if($entry->carryItems && $entry->carryItems->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Carried Items</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($entry->carryItems as $item)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->item_name }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Entry Metadata -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Entry Metadata</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Check In Time</p>
                        <p class="text-sm text-gray-900">{{ $entry->in_time->format('M j, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Check Out Time</p>
                        <p class="text-sm text-gray-900">{{ $entry->out_time ? $entry->out_time->format('M j, Y h:i A') : 'Not checked out' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Processed By Guard</p>
                        <p class="text-sm text-gray-900">{{ $entry->guardUser->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
