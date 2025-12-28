@extends('layouts.app')

@section('title', 'Guard Dashboard - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Guard Dashboard</h1>
                <p class="text-gray-600 mb-6">Welcome, {{ $guard->name }}!</p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-green-600 font-medium">Currently Checked In</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $activeEntry ? 1 : 0 }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-600 font-medium">Today's Check-ins</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $checkInCount }}</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Today's Check-outs</p>
                        <p class="text-2xl font-bold text-red-900 mt-1">{{ $checkOutCount }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm text-purple-600 font-medium">Avg. Duration (mins)</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ $avgDuration }} <span
                                class="text-sm text-purple-500">minutes</span></p>
                    </div>
                </div>
            </div>

            <!-- Active Entry (if any) -->
            @if ($activeEntry)
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4">
                        <span class="text-blue-600">🔵</span> Active Entry
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-blue-700 mb-2">Visitor Information</p>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    @if ($activeEntry->visitor->photo_path)
                                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-blue-200">
                                            <img src="{{ Storage::url($activeEntry->visitor->photo_path) }}"
                                                alt="{{ $activeEntry->visitor->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-blue-900">{{ $activeEntry->visitor->name }}</p>
                                        <p class="text-sm text-blue-700">{{ $activeEntry->visitor->mobile_number }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-700">Purpose:</p>
                                    <p class="text-blue-900">{{ $activeEntry->visitor->purpose }}</p>
                                </div>
                                @if ($activeEntry->visitor->vehicle_number)
                                    <div>
                                        <p class="text-sm text-blue-700">Vehicle:</p>
                                        <p class="text-blue-900">{{ $activeEntry->visitor->vehicle_number }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700 mb-2">Entry Information</p>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-blue-700">Check-in Time:</p>
                                    <p class="text-blue-900 font-medium">{{ $activeEntry->in_time->format('h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-700">Duration:</p>
                                    <p class="text-blue-900 font-medium">{{ $activeEntry->in_time->diffInMinutes(now()) }}
                                        minutes (ongoing)</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-700">Carry Items:</p>
                                    <p class="text-blue-900 font-medium">{{ $activeEntry->carryItems->count() }} items</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 flex gap-4 mt-4 pt-6 border-t border-blue-200">
                        <a href="{{ route('guard.entries.show', $activeEntry->id) }}"
                            class="flex-1 px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-50 border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center font-medium">
                            Manage Items &rarr;
                        </a>
                        <button onclick="checkOutActiveVisitor({{ $activeEntry->id }})"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-center font-medium">
                            🔴 Check Out
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-8 text-center">
                    <p class="text-gray-500 text-lg">No visitors currently checked in</p>
                    <a href="{{ route('guard.entries.index') }}"
                        class="inline-block mt-4 text-blue-600 hover:text-blue-900 font-medium">
                        Go to Entry Screen to check in visitors
                    </a>
                </div>
            @endif

            <!-- Today's Entries Table -->
            @if ($todayEntries->count() > 0)
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Today's Entries ({{ $todayEntries->count() }})</h2>
                        <p class="text-sm text-gray-500">{{ date('F j, Y') }}</p>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                        Photo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mobile
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                        Purpose
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        Vehicle
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                        Items
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        In Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        Out Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        Duration
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($todayEntries->sortBy('in_time') as $entry)
                                    <tr class="hover:bg-gray-50 {{ !$entry->out_time ? 'bg-yellow-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                                @if ($entry->visitor->photo_path)
                                                    <img src="{{ Storage::url($entry->visitor->photo_path) }}"
                                                        alt="{{ $entry->visitor->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012-2.828 0-4.414 0-4.414L12 16.828V20a2 2 0 01-2.828 2.828 0-4.414-4.414L12 7.172V12a2 2 0 01-2.828-2.828 0-4.414-4.414z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-gray-900">{{ $entry->visitor->mobile_number }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="font-medium text-gray-900">{{ $entry->visitor->name }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900 truncate" title="{{ $entry->visitor->purpose }}">
                                                {{ \Illuminate\Support\Str::limit($entry->visitor->purpose, 25) }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $entry->visitor->vehicle_number ? $entry->visitor->vehicle_number : '--' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-1">
                                                @if ($entry->carryItems->count() > 0)
                                                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                                        {{ $entry->carryItems->count() }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">items</span>
                                                @else
                                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                                        0
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-gray-900">{{ $entry->in_time->format('h:i A') }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if ($entry->out_time)
                                                    <span class="text-green-600 font-medium">
                                                        {{ $entry->out_time->format('h:i A') }}
                                                    </span>
                                                @else
                                                    <span class="text-yellow-600 font-medium">
                                                        Active
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if ($entry->out_time)
                                                    <span class="text-gray-900">
                                                        {{ $entry->duration_minutes }} min
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">
                                                        --
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if (!$entry->out_time)
                                                <button onclick="checkOutActiveVisitor({{ $entry->id }})"
                                                    class="text-green-600 hover:text-green-900 font-medium text-left">
                                                    Check Out
                                                </button>
                                            @else
                                                <a href="{{ route('guard.entries.show', $entry->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-medium">
                                                    View Details
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($todayEntries->count() > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('guard.entries.list') }}"
                                class="text-sm text-blue-600 hover:text-blue-900 font-medium">
                                View all {{ $todayEntries->count() }} entries in full table &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="border-t pt-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                        <p class="text-gray-500 text-lg">No entries recorded today</p>
                        <a href="{{ route('guard.entries.index') }}"
                            class="inline-block mt-4 text-blue-600 hover:text-blue-900 font-medium">
                            Go to Entry Screen to check in visitors
                        </a>
                    </div>
                </div>
            @endif

            <!-- Quick Action -->
            <div class="border-t pt-6 mt-6">
                <a href="{{ route('guard.entries.index') }}"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    🟢 New Visitor Check-In
                </a>
            </div>

            <div class="border-t pt-6 mt-6">
                <p class="text-sm text-gray-500">
                    View today's entry history with visitor photos, carry items, and visit duration.
                    Highlighted entries indicate visitors who have checked in but not yet checked out.
                </p>
            </div>
        </div>

        <!-- Message Container -->
        <div id="messageContainer" class="hidden fixed top-4 right-4 z-50 max-w-sm">
            <div id="messageBox" class="rounded-lg shadow-xl p-4">
                <p id="messageText" class="font-medium"></p>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-8 flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12c0 4.313-2.673 8.291-7.829 8.291 1.36 0 2.618-1.041 3.37-2.836a4.009 4.009 0 011-2.865 4.009 4.009 0 013.37-2.836A6.98 6.98 0 018 12h2z">
                    </path>
                </svg>
                <p class="text-gray-700">Processing...</p>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        const activeEntryId = {{ $activeEntry ? $activeEntry->id : 'null' }};

        function checkOutActiveVisitor(entryId) {
            if (!activeEntryId) {
                showMessage('No active entry to check out.', 'error');
                return;
            }

            if (!confirm('Are you sure you want to check out this visitor?')) {
                return;
            }

            showLoading(true);

            fetch('{{ route('guard.entries.check-out') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ entry_id: entryId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const duration = data.entry.duration_minutes;
                        showMessage('Success', `Visitor checked out successfully! Duration: ${duration} minutes.`);

                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showMessage('Error', data.message || 'Check-out failed.');
                    }
                })
                .catch(error => {
                    showMessage('Error', 'Error checking out visitor. Please try again.');
                    console.error(error);
                })
                .finally(() => {
                    showLoading(false);
                });
        }

        function showMessage(type, message) {
            const container = document.getElementById('messageContainer');
            const box = document.getElementById('messageBox');
            const messageText = document.getElementById('messageText');

            messageText.textContent = message;
            container.classList.remove('hidden');

            box.classList.remove('bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700', 'border', 'border-green-200', 'border-red-200');

            if (type === 'Success') {
                box.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else if (type === 'Error') {
                box.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }

            // Auto-hide after 5 seconds
            setTimeout(() => {
                container.classList.add('hidden');
            }, 5000);
        }

        function showLoading(show) {
            document.getElementById('loadingOverlay').classList.toggle('hidden', !show);
        }
    </script>
@endpush