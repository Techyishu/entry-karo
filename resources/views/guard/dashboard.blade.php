@extends('layouts.guard')

@section('title', 'Guard Dashboard - Entry Karo')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 lg:mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-500 mt-1">Status Overview</p>
            </div>
            <div class="text-right hidden lg:block">
                <p class="text-sm font-medium text-gray-600">{{ now()->format('l, M d, Y') }}</p>
            </div>
        </div>

        <!-- Quick Stats (Scrollable on mobile) -->
        <div class="flex lg:grid lg:grid-cols-4 gap-4 overflow-x-auto pb-4 lg:pb-0 hide-scrollbar -mx-4 px-4 lg:mx-0 lg:px-0">
            <!-- Active Card -->
            <div class="flex-none w-40 lg:w-auto bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeEntry ? 1 : 0 }}</p>
                <div class="mt-2 flex items-center gap-1">
                   <div class="w-2 h-2 rounded-full {{ $activeEntry ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></div>
                   <span class="text-xs text-gray-400">Visitor inside</span>
                </div>
            </div>

            <!-- Check-ins -->
            <div class="flex-none w-40 lg:w-auto bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Check-ins</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $checkInCount }}</p>
                <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Today
                </p>
            </div>

            <!-- Check-outs -->
            <div class="flex-none w-40 lg:w-auto bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Check-outs</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $checkOutCount }}</p>
                <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    Done
                </p>
            </div>

            <!-- Avg Duration -->
            <div class="flex-none w-40 lg:w-auto bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Avg Time</p>
                <div class="flex items-baseline gap-1 mt-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $avgDuration }}</p>
                    <span class="text-xs text-gray-500">min</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">Per visit</p>
            </div>
        </div>

        <!-- Primary Action -->
        <a href="{{ route('guard.entries.index') }}" class="block mt-6 mb-8 transform transition hover:scale-[1.02] active:scale-[0.98]">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-6 shadow-lg text-white relative overflow-hidden">
                <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">New Visitor Entry</h2>
                        <p class="text-green-100 mt-1">Tap here to scan or register a new visitor</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3 backdrop-blur-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Active Entry Section -->
        @if ($activeEntry)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 px-1">Currently Inside</h3>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 z-0"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-start gap-4">
                            <!-- Photo -->
                            <div class="flex-shrink-0">
                                @if ($activeEntry->visitor->photo_path)
                                    <div class="w-16 h-16 rounded-2xl bg-gray-200 overflow-hidden shadow-sm">
                                        <img src="{{ Storage::url($activeEntry->visitor->photo_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center text-green-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 truncate">{{ $activeEntry->visitor->name }}</h4>
                                        <p class="text-green-600 font-medium text-sm">{{ $activeEntry->visitor->mobile_number }}</p>
                                    </div>
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-lg font-medium animate-pulse">
                                        Active
                                    </span>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-500">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $activeEntry->in_time->format('h:i A') }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        {{ $activeEntry->carryItems->count() }} Items
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex gap-3">
                             <a href="{{ route('guard.entries.show', $activeEntry->id) }}" class="flex-1 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl text-center hover:bg-gray-200 transition">
                                Details
                            </a>
                            <button onclick="checkOutActiveVisitor({{ $activeEntry->id }})" class="flex-1 py-2.5 bg-red-50 text-red-600 text-sm font-semibold rounded-xl text-center hover:bg-red-100 transition border border-red-100">
                                Check Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Activity List -->
        <div>
           <div class="flex items-center justify-between mb-4 px-1">
               <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
               <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ $todayEntries->count() }} Today</span>
           </div>

           <div class="space-y-3">
               @forelse ($todayEntries->sortByDesc('in_time')->take(10) as $entry)
                   <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                       <div class="flex items-center gap-3">
                           <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-500 font-bold text-xs overflow-hidden">
                                @if ($entry->visitor->photo_path)
                                    <img src="{{ Storage::url($entry->visitor->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($entry->visitor->name, 0, 1) }}
                                @endif
                           </div>
                           <div class="min-w-0">
                               <p class="text-sm font-semibold text-gray-900 truncate">{{ $entry->visitor->name }}</p>
                               <p class="text-xs text-gray-500 truncate flex items-center gap-1">
                                    {{ $entry->in_time->format('h:i A') }} 
                                    @if($entry->out_time)
                                        → {{ $entry->out_time->format('h:i A') }}
                                    @endif
                               </p>
                           </div>
                       </div>
                       
                       <div class="flex-shrink-0">
                           @if(!$entry->out_time)
                                <button onclick="checkOutActiveVisitor({{ $entry->id }})" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                    Check Out
                                </button>
                           @else
                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-lg text-xs font-medium">
                                    Done
                                </span>
                           @endif
                       </div>
                   </div>
               @empty
                   <div class="text-center py-10">
                       <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                           <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                       </div>
                       <p class="text-gray-500 font-medium">No activity yet</p>
                       <p class="text-gray-400 text-sm mt-1">Check-ins will appear here</p>
                   </div>
               @endforelse
           </div>

           @if ($todayEntries->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('guard.entries.list') }}" class="text-sm text-green-600 font-medium hover:underline">View All Entries</a>
                </div>
           @endif
        </div>
    </div>

    <!-- Message Container (Toast) -->
    <div id="messageContainer" class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-sm">
        <div id="messageBox" class="rounded-xl shadow-2xl p-4 bg-gray-900 text-white flex items-center gap-3">
            <div id="messageIcon"></div>
            <p id="messageText" class="text-sm font-medium"></p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-gray-600 font-medium mt-3">Processing...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function checkOutActiveVisitor(entryId) {
            if (!entryId) return;

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
                    showMessage('Success', 'Visitor checked out successfully');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showMessage('Error', data.message || 'Check-out failed');
                    showLoading(false);
                }
            })
            .catch(error => {
                showMessage('Error', 'Connection error');
                showLoading(false);
            });
        }

        function showMessage(type, message) {
            const container = document.getElementById('messageContainer');
            const text = document.getElementById('messageText');
            
            text.textContent = message;
            container.classList.remove('hidden');
            container.classList.add('animate-bounce'); // Simple animation
            
            setTimeout(() => {
                container.classList.add('hidden');
            }, 3000);
        }

        function showLoading(show) {
            document.getElementById('loadingOverlay').classList.toggle('hidden', !show);
        }
    </script>
@endpush