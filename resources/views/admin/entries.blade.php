@extends('layouts.app')

@section('title', 'All Entries - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">All Entries</h1>
                    <p class="text-gray-600 mt-2">Total: {{ $entries->total() }} entries</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 Back to Dashboard
                </a>
            </div>

            <!-- Entries Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Visitor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Guard
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-In
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-Out
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Duration
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Items
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($entries as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($entry->visitor->photo_path)
                                                <img src="{{ Storage::url($entry->visitor->photo_path) }}"
                                                    alt="{{ $entry->visitor->name }}"
                                                    class="w-10 h-10 rounded-full object-cover mr-3">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-gray-600 font-medium">{{ substr($entry->visitor->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $entry->visitor->mobile_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-gray-900">{{ $entry->guardUser->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-900">{{ $entry->in_time->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $entry->in_time->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->out_time)
                                            <p class="text-sm text-gray-900">{{ $entry->out_time->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $entry->out_time->format('h:i A') }}</p>
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->duration_minutes)
                                            <p class="text-gray-900">{{ $entry->duration_minutes }} min</p>
                                        @else
                                            <p class="text-blue-600">Ongoing</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                            {{ $entry->carryItems->count() }} items
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->out_time)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                Checked Out
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        No entries found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($entries->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        {{ $entries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection