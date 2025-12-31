@extends('layouts.app')

@section('title', 'Customer Dashboard - Entry Karo')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-500 mt-1">Hello, {{ $customer->name }}</p>
            </div>
            <div>
                 <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700">
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>

        <!-- Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Active Guests -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                 <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Currently Inside</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($activeEntries) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @if($activeEntries > 0)
                            <span class="absolute top-0 right-0 -mr-1 -mt-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Today's Entries -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                 <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today's Visits</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($todayEntries) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

             <!-- Total Entries -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                 <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Visits</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalEntries) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>

             <!-- Your Guards -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                 <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Your Guards</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $guards->count() }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Recent Visits</h2>
            </div>
            
            <!-- Desktop Table -->
             <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visitor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guard</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Out Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($entries as $entry)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                         @if($entry->visitor->photo_path)
                                            <div class="h-8 w-8 rounded-full overflow-hidden mr-3">
                                                 <img src="{{ Storage::url($entry->visitor->photo_path) }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 mr-3">
                                                {{ substr($entry->visitor->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $entry->visitor->purpose }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $entry->guardUser->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $entry->in_time->format('h:i A') }} <br>
                                    <span class="text-xs text-gray-400">{{ $entry->in_time->format('M d') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($entry->out_time)
                                        {{ $entry->out_time->format('h:i A') }}
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $entry->duration_minutes ?? '--' }} min
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No entries found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile List -->
            <div class="sm:hidden divide-y divide-gray-100">
                @forelse($entries as $entry)
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                             <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gray-50 flex items-center justify-center overflow-hidden">
                                     @if($entry->visitor->photo_path)
                                        <img src="{{ Storage::url($entry->visitor->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-sm font-bold text-gray-500">{{ substr($entry->visitor->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $entry->visitor->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $entry->visitor->purpose }}</p>
                                </div>
                            </div>
                            <div>
                                @if(!$entry->out_time)
                                     <span class="px-2 py-1 text-xs rounded-lg bg-green-50 text-green-700 font-medium border border-green-100">Active</span>
                                @else
                                    <span class="text-xs text-gray-400">Done</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2 pl-13">
                            <span>Checked in by {{ $entry->guardUser->name }}</span>
                            <span>{{ $entry->in_time->format('h:i A') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 text-sm">No entries found</div>
                @endforelse
            </div>

            @if($entries->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $entries->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection