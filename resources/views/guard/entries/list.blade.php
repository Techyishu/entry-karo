@extends('layouts.app')

@section('title', 'Today\'s Entries - Entry Karo')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Today's Entries</h1>
                    <p class="text-gray-600">{{ date('F j, Y') }}</p>
                </div>
                <a href="{{ route('guard.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 Go to Dashboard
                </a>
            </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-blue-600 font-medium">Total Entries</p>
                <p class="text-3xl font-bold text-blue-900">{{ $todayEntries->count() }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm text-green-600 font-medium">Check-Ins</p>
                <p class="text-3xl font-bold text-green-900">{{ $checkInCount }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <p class="text-sm text-red-600 font-medium">Check-Outs</p>
                <p class="text-3xl font-bold text-red-900">{{ $checkOutCount }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <p class="text-sm text-purple-600 font-medium">Avg. Duration</p>
                <p class="text-3xl font-bold text-purple-900">{{ $avgDuration }}</p>
                <p class="text-xs text-purple-500">minutes</p>
            </div>
        </div>

        <!-- Entries Table -->
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                            Visitor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Mobile Number
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Purpose
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            Vehicle
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            Items
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            In Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Out Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            Duration
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($todayEntries as $entry)
                        <tr class="hover:bg-gray-50 @if (!$entry->out_time) bg-yellow-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    @if ($entry->visitor->photo_path)
                                        <div class="w-10 h-10 rounded-full overflow-hidden">
                                            <img
                                                src="{{ Storage::url($entry->visitor->photo_path) }}"
                                                alt="{{ $entry->visitor->name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $entry->visitor->mobile_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ $entry->visitor->mobile_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="max-w-xs truncate" title="{{ $entry->visitor->purpose }}">
                                    {{ Str::limit($entry->visitor->purpose, 25) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $entry->visitor->vehicle_number ? $entry->visitor->vehicle_number : '--' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @if ($entry->carryItems->count() > 0)
                                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                            {{ $entry->carryItems->count() }}
                                        </span>
                                        <span class="text-xs text-gray-500">items</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ $entry->in_time->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($entry->out_time)
                                        <span class="text-green-600 font-medium">
                                            {{ $entry->out_time->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="text-blue-600 font-medium">
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($entry->out_time)
                                        <span class="text-green-600 font-medium">
                                            {{ $entry->out_time->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="text-blue-600 font-medium">
                                            --
                                        </span>
                                    @endif
                                    @if ($entry->duration_minutes)
                                        <span class="text-xs text-gray-500">({{ $entry->duration_minutes }} min)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($entry->out_time)
                                    <a href="{{ route('guard.entries.show', $entry->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        View Details
                                    </a>
                                @else
                                    <a href="{{ route('guard.entry-details', $entry->visitor->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        Check Out
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination or Load More -->
        @if ($todayEntries->count() > 20)
            <div class="mt-6 text-center">
                <button class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Load All Entries
                </button>
            </div>
        @endif

        <!-- Back to Dashboard -->
        <div class="mt-6 border-t pt-6">
            <a href="{{ route('guard.dashboard') }}" class="block w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                Back to Dashboard
            </a>
        </div>
        </div>
    </div>
</div>
@endsection

