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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Photo (Optional)</label>
                    
                    <!-- Photo Preview -->
                    <div id="itemPhotoPreview" class="hidden mb-3 relative">
                        <img id="itemPhotoPreviewImg" src="" alt="Item Photo Preview"
                            class="w-32 h-32 object-cover rounded-xl border-2 border-gray-200">
                        <button type="button" onclick="removeItemPhoto()"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Camera Container -->
                    <div id="itemCameraContainer" class="hidden mb-3">
                        <video id="itemCameraVideo" autoplay playsinline class="w-full h-48 object-cover rounded-xl border-2 border-gray-200 bg-black"></video>
                        <canvas id="itemCameraCanvas" class="hidden"></canvas>
                        <div class="flex gap-2 mt-3">
                            <button type="button" onclick="captureItemPhoto()"
                                class="flex-1 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <circle cx="12" cy="13" r="3"></circle>
                                </svg>
                                Capture Photo
                            </button>
                            <button type="button" onclick="stopItemCamera()"
                                class="px-4 py-2 bg-gray-500 text-white rounded-xl hover:bg-gray-600 font-medium">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Photo Upload/Camera Options -->
                    <div id="itemPhotoUploadArea" class="space-y-3">
                        <!-- Take Photo Button -->
                        <button type="button" onclick="startItemCamera()"
                            class="w-full border-2 border-dashed border-blue-300 bg-blue-50 rounded-xl p-4 text-center hover:bg-blue-100 transition cursor-pointer flex items-center justify-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <circle cx="12" cy="13" r="3"></circle>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-blue-700">📷 Take Photo with Camera</p>
                                <p class="text-xs text-blue-500">Use device camera to capture item photo</p>
                            </div>
                        </button>

                        <!-- Upload from Gallery -->
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition cursor-pointer relative">
                            <input type="file" id="item_photo" name="item_photo" accept="image/*" capture="environment"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                onchange="previewItemPhoto(event)">
                            <div class="flex items-center justify-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-700">📁 Upload from Gallery</p>
                                    <p class="text-xs text-gray-400">JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
        let itemCameraStream = null;

        function showAddItemModal() {
            document.getElementById('addItemModal').classList.remove('hidden');
            document.getElementById('item_name').focus();
        }

        function hideAddItemModal() {
            // Stop camera if running
            if (itemCameraStream) {
                itemCameraStream.getTracks().forEach(track => track.stop());
                itemCameraStream = null;
            }
            document.getElementById('itemCameraVideo').srcObject = null;
            document.getElementById('itemCameraContainer').classList.add('hidden');
            
            // Reset photo preview
            document.getElementById('itemPhotoPreview').classList.add('hidden');
            document.getElementById('itemPhotoUploadArea').classList.remove('hidden');
            
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

        // Camera capture for item photo

        async function startItemCamera() {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    showMessage('Camera not supported in this browser. Please use file upload.', 'error');
                    return;
                }

                const constraints = {
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                };

                itemCameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                document.getElementById('itemCameraVideo').srcObject = itemCameraStream;

                document.getElementById('itemCameraContainer').classList.remove('hidden');
                document.getElementById('itemPhotoUploadArea').classList.add('hidden');
                document.getElementById('itemPhotoPreview').classList.add('hidden');

            } catch (error) {
                console.error('Camera access error:', error);
                if (error.name === 'NotAllowedError') {
                    showMessage('Camera access denied. Please allow camera permission.', 'error');
                } else if (error.name === 'NotFoundError') {
                    showMessage('No camera found. Please use file upload.', 'error');
                } else {
                    showMessage('Could not access camera: ' + error.message, 'error');
                }
            }
        }

        function captureItemPhoto() {
            const video = document.getElementById('itemCameraVideo');
            const canvas = document.getElementById('itemCameraCanvas');
            const ctx = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            canvas.toBlob(function(blob) {
                const file = new File([blob], 'item-photo.jpg', { type: 'image/jpeg' });
                
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('item_photo').files = dataTransfer.files;

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('itemPhotoPreviewImg').src = e.target.result;
                    document.getElementById('itemPhotoPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(blob);

                stopItemCamera();
                showMessage('Photo captured successfully!', 'success');
            }, 'image/jpeg', 0.85);
        }

        function stopItemCamera() {
            if (itemCameraStream) {
                itemCameraStream.getTracks().forEach(track => track.stop());
                itemCameraStream = null;
            }

            document.getElementById('itemCameraVideo').srcObject = null;
            document.getElementById('itemCameraContainer').classList.add('hidden');
            document.getElementById('itemPhotoUploadArea').classList.remove('hidden');
        }

        function previewItemPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('itemPhotoPreviewImg').src = e.target.result;
                    document.getElementById('itemPhotoPreview').classList.remove('hidden');
                    document.getElementById('itemPhotoUploadArea').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeItemPhoto() {
            document.getElementById('item_photo').value = '';
            document.getElementById('itemPhotoPreviewImg').src = '';
            document.getElementById('itemPhotoPreview').classList.add('hidden');
            document.getElementById('itemPhotoUploadArea').classList.remove('hidden');
        }

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