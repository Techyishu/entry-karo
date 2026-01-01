@extends('layouts.app')

@section('title', 'Entry Details - Entry Karo')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('guard.entries.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Entry Details</h1>
                    <p class="text-sm text-gray-500 mt-1">Entry #{{ $entry->id }}</p>
                </div>
            </div>
            @if (!$entry->out_time)
                <button onclick="checkOutVisitor({{ $entry->id }})" class="w-full sm:w-auto px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-bold shadow-lg shadow-red-200">
                    Check Out
                </button>
            @endif
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
                            <p class="text-sm text-green-700">{{ $entry->in_time->diffInMinutes(now()) }} minutes ago</p>
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
                    @if ($entry->visitor->photo_path)
                        <div class="flex-shrink-0">
                            <img src="{{ Storage::url($entry->visitor->photo_path) }}" alt="{{ $entry->visitor->name }}" class="w-32 h-32 rounded-2xl object-cover border-2 border-gray-200">
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
                            @if($entry->visitor->company)
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Company</p>
                                    <p class="text-sm text-gray-900">{{ $entry->visitor->company }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Purpose</p>
                                <p class="text-sm text-gray-900">{{ $entry->visitor->purpose }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Address</p>
                            <p class="text-sm text-gray-900">{{ $entry->visitor->address }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Visited Location</p>
                            <p class="text-sm text-gray-900">
                                <span class="font-semibold">{{ Auth::user()->customer->name ?? Auth::user()->name }}</span>
                                @if(Auth::user()->customer && Auth::user()->customer->organization_type)
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                        {{ ucfirst(str_replace('_', ' ', Auth::user()->customer->organization_type)) }}
                                    </span>
                                @elseif(Auth::user()->organization_type)
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                        {{ ucfirst(str_replace('_', ' ', Auth::user()->organization_type)) }}
                                    </span>
                                @endif
                            </p>
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

        <!-- Entry Timing Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Entry Timing</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-{{ $entry->out_time ? '3' : '1' }} gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Check-In Time</p>
                        <p class="text-lg font-bold text-gray-900">{{ $entry->in_time->format('h:i A') }}</p>
                        <p class="text-xs text-gray-500">{{ $entry->in_time->format('d M Y') }}</p>
                    </div>
                    @if ($entry->out_time)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Check-Out Time</p>
                            <p class="text-lg font-bold text-gray-900">{{ $entry->out_time->format('h:i A') }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->out_time->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide mb-1">Duration</p>
                            <p class="text-lg font-bold text-gray-900">{{ $entry->duration_minutes }} min</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Carry Items Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Carry Items ({{ $entry->carryItems->count() }})</h2>
                <button onclick="showAddItemModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm">
                    + Add Item
                </button>
            </div>
            <div class="p-6">
                @if ($entry->carryItems->where('in_status', true)->count() > 0)
                    <div class="space-y-3">
                        @foreach ($entry->carryItems->where('in_status', true)->sortBy('created_at') as $item)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-start gap-3 flex-1">
                                        @if ($item->item_photo_path)
                                            <img src="{{ Storage::url($item->item_photo_path) }}" alt="{{ $item->item_name }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900">{{ $item->item_name }}</p>
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">{{ ucfirst($item->item_type) }}</span>
                                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">Qty: {{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $item->out_status ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                            {{ $item->out_status ? '✓ Taken Out' : '→ Inside' }}
                                        </span>
                                        @if (!$item->out_status && $entry->out_time)
                                            <span class="text-xs text-red-600 font-medium">⚠</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">No items brought in</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div id="addItemModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" onclick="if(event.target === this) hideAddItemModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-900">Add Carry Item</h3>
                <button type="button" onclick="hideAddItemModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="addItemForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="entry_id" value="{{ $entry->id }}">

                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                    <input type="text" id="item_name" name="item_name" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500" placeholder="Laptop, Bag, Documents..." required>
                </div>

                <div>
                    <label for="item_type" class="block text-sm font-medium text-gray-700 mb-1">Item Type *</label>
                    <select id="item_type" name="item_type" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Select type...</option>
                        <option value="personal">Personal (Bag, Laptop, Phone)</option>
                        <option value="office">Office Equipment</option>
                        <option value="delivery">Delivery</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500" required>
                </div>

                <div>
                    <label for="item_photo" class="block text-sm font-medium text-gray-700 mb-1">Item Photo (Optional)</label>
                    <input type="file" id="item_photo" name="item_photo" accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500">
                </div>

                <button type="submit" class="w-full py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold shadow-lg shadow-green-200">
                    Add Item
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Message -->
    <div id="messageContainer" class="hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-full px-4 max-w-sm">
        <div id="messageBox" class="rounded-xl shadow-2xl p-4">
            <p id="messageText" class="font-medium text-center"></p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 border-4 border-green-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-gray-600 font-medium mt-3">Processing...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const entryId = {{ $entry->id }};

        function showAddItemModal() {
            document.getElementById('addItemModal').classList.remove('hidden');
            document.getElementById('item_name').focus();
        }

        function hideAddItemModal() {
            document.getElementById('addItemModal').classList.add('hidden');
            document.getElementById('addItemForm').reset();
        }

        async function checkOutVisitor(entryId) {
            if (!confirm('Are you sure you want to check out this visitor?')) return;
            showLoading(true);

            try {
                const response = await fetch('{{ route('guard.entries.check-out') }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ entry_id: entryId })
                });

                const data = await response.json();
                if (data.success) {
                    showMessage('Visitor checked out successfully!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showMessage(data.message || 'Check-out failed.', 'error');
                }
            } catch (error) {
                showMessage('Error checking out visitor.', 'error');
                console.error(error);
            } finally {
                showLoading(false);
            }
        }

        document.getElementById('addItemForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            showLoading(true);

            const formData = new FormData(e.target);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('entry_id', entryId);

            try {
                const response = await fetch('{{ route('guard.carry-items.store') }}', {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    showMessage('Item added successfully!', 'success');
                    hideAddItemModal();
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        showMessage(errorMessages, 'error');
                    } else {
                        showMessage(data.message || 'Failed to add item.', 'error');
                    }
                }
            } catch (error) {
                showMessage('Error adding item.', 'error');
                console.error(error);
            } finally {
                showLoading(false);
            }
        });

        function showMessage(text, type) {
            const container = document.getElementById('messageContainer');
            const box = document.getElementById('messageBox');
            const messageText = document.getElementById('messageText');

            messageText.textContent = text;
            box.className = 'rounded-xl shadow-2xl p-4 ' + (type === 'success' ? 'bg-green-50 text-green-700 border-2 border-green-200' : 'bg-red-50 text-red-700 border-2 border-red-200');
            container.classList.remove('hidden');
            setTimeout(() => container.classList.add('hidden'), 4000);
        }

        function showLoading(show) {
            document.getElementById('loadingOverlay').classList.toggle('hidden', !show);
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') hideAddItemModal();
        });
    </script>
@endpush