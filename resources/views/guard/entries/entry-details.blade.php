@extends('layouts.app')

@section('title', 'Entry Details - Entry Karo')

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
                                    d="M15 19l-7-7 7 7m0 0l-7 7-7-7m0 0l-7 7 7 7m0 0l-7 7-7-7" />
                            </svg>
                            Back to Entry Screen
                        </a>
                        <a href="{{ route('guard.dashboard') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            📊 Dashboard
                        </a>
                    </div>
                    @if (!$entry->out_time)
                        <button onclick="checkOutVisitor({{ $entry->id }})"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            🔴 Check Out
                        </button>
                    @endif
                </div>

                <!-- Visitor Information -->
                <div class="border-b pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Visitor Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Visitor Name</p>
                            <p class="text-gray-900 font-medium">{{ $entry->visitor->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Mobile Number</p>
                            <p class="text-gray-900 font-medium">{{ $entry->visitor->mobile_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Address</p>
                            <p class="text-gray-900">{{ $entry->visitor->address }}</p>
                        </div>
                        @if ($entry->visitor->vehicle_number)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Vehicle Number</p>
                                <p class="text-gray-900">{{ $entry->visitor->vehicle_number }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($entry->visitor->photo_path)
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Visitor Photo</h3>
                            <img src="{{ Storage::url($entry->visitor->photo_path) }}" alt="{{ $entry->visitor->name }}"
                                class="w-40 h-40 object-cover rounded-lg border-2 border-gray-200">
                        </div>
                    @endif
                </div>

                <!-- Entry Information -->
                <div class="border-b pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Entry Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Check-in Time</p>
                            <p class="text-gray-900 font-medium">{{ $entry->in_time->format('h:i A') }}</p>
                        </div>
                        @if ($entry->out_time)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Check-out Time</p>
                                <p class="text-gray-900 font-medium">{{ $entry->out_time->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Duration</p>
                                <p class="text-gray-900 font-medium">{{ $entry->duration_minutes }} minutes</p>
                            </div>
                        @endif
                    </div>

                    @if ($entry->out_time)
                        <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-green-600 font-medium">✓ Visitor Checked Out</p>
                            <p class="text-xs text-gray-500 mt-1">Entry is closed. Duration: {{ $entry->duration_minutes }}
                                minutes
                            </p>
                        </div>
                    @else
                        <div class="mt-4 bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-600 font-medium">🔵 Visitor Currently Checked In</p>
                            <p class="text-xs text-gray-500 mt-1">Visitor is on premises. Duration:
                                {{ $entry->in_time->diffInMinutes(now()) }} minutes ago
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Carry Items Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Carry Items ({{ $entry->carryItems->count() }})
                        </h2>
                        <button onclick="showAddItemModal()"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm font-medium">
                            + Add Item
                        </button>
                    </div>

                    <!-- Items with in_status = true -->
                    @if ($entry->carryItems->where('in_status', true)->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Items Brought In</h3>
                            <div class="space-y-3">
                                @foreach ($entry->carryItems->where('in_status', true)->sortBy('created_at') as $item)
                                    <div
                                        class="flex items-center justify-between bg-gray-50 p-4 rounded-lg {{ $item->out_status ? 'border-2 border-yellow-400' : '' }}">
                                        <div class="flex-1">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->item_name }}</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ ucfirst($item->item_type) }} &middot;
                                                    Qty: {{ $item->quantity }}
                                                </p>
                                            </div>
                                            @if ($item->item_photo_path)
                                                <img src="{{ Storage::url($item->item_photo_path) }}" alt="{{ $item->item_name }}"
                                                    class="w-16 h-16 object-cover rounded-md border border-gray-200 ml-3">
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $item->out_status ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $item->out_status ? 'Taken Out' : 'Inside' }}
                                            </span>
                                            @if (!$item->out_status && $entry->out_time)
                                                <span class="text-xs text-red-600 font-medium">⚠ Not Out</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-gray-500">No items brought in</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Add Item Modal -->
            <div id="addItemModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Add Carry Item</h3>
                        <button type="button" onclick="hideAddItemModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="addItemForm" class="space-y-4">
                        @csrf
                        <input type="hidden" name="entry_id" value="{{ $entry->id }}">

                        <div>
                            <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Name *
                            </label>
                            <input type="text" id="item_name" name="item_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Laptop, Bag, Documents, etc." required>
                        </div>

                        <div>
                            <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Type *
                            </label>
                            <select id="item_type" name="item_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="">Select type...</option>
                                <option value="personal">Personal (Bag, Laptop, Phone)</option>
                                <option value="office">Office Equipment (Computer, Projector)</option>
                                <option value="delivery">Delivery (Package, Box)</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity *
                            </label>
                            <input type="number" id="quantity" name="quantity" min="1" value="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="flex items-center mb-2">
                                <input type="checkbox" id="in_status" name="in_status" checked
                                    class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <label for="in_status" class="ml-2 text-sm text-gray-700">
                                    Item brought in (IN)
                                </label>
                            </label>
                            <p class="text-xs text-gray-500">Always checked when adding new item</p>
                        </div>

                        <div>
                            <label for="item_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Photo
                            </label>
                            <input type="file" id="item_photo" name="item_photo" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="submit" id="submitItemBtn"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Add Item
                        </button>
                    </form>
                </div>
            </div>

            <!-- Message Container -->
            <div id="messageContainer" class="hidden fixed top-4 right-4 z-50 max-w-sm">
                <div id="messageBox" class="rounded-lg shadow-xl p-4">
                    <p id="messageText" class="font-medium"></p>
                </div>
            </div>

            <!-- Message Container -->
            <div id="loadingOverlay"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl p-8 flex flex-col items-center">
                    <svg class="animate-spin h-10 w-10 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12c0 4.313-2.673 8.291-7.829 8.291-1.36 0-2.618-1.041-3.37-2.836a4.009 4.009 0 011-2.865 4.009 4.009 0 013.37-2.836A6.98 6.98 0 018 12h-2z">
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
            if (!confirm('Are you sure you want to check out this visitor?')) {
                return;
            }

            showLoading(true);

            try {
                const response = await fetch('{{ route('guard.entries.check-out') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ entry_id: entryId })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('Visitor checked out successfully! All items marked as taken out.', 'success');

                    // Reload page after 2 seconds to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showMessage(data.message || 'Check-out failed.', 'error');
                }
            } catch (error) {
                showMessage('Error checking out visitor. Please try again.', 'error');
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    showMessage('Server error. Please check the console for details.', 'error');
                    return;
                }

                const data = await response.json();

                if (response.ok && data.success) {
                    showMessage('Item added successfully!', 'success');
                    hideAddItemModal();

                    // Reload page to show new item
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        showMessage(errorMessages, 'error');
                    } else {
                        showMessage(data.message || 'Failed to add item.', 'error');
                    }
                }
            } catch (error) {
                showMessage('Error adding item. Please try again.', 'error');
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
            container.classList.remove('hidden');

            box.classList.remove('bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700', 'border', 'border-green-200', 'border-red-200');

            if (type === 'success') {
                box.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else if (type === 'error') {
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

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                hideAddItemModal();
            }
        });

        // Close modal on outside click
        document.getElementById('addItemModal').addEventListener('click', function (e) {
            if (e.target === this) {
                hideAddItemModal();
            }
        });
    </script>
@endpush