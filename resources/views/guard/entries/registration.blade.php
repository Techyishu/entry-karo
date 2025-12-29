@extends('layouts.app')

@section('title', 'Visitor Registration & Check-In - Entry Karo')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Visitor Registration & Check-In</h1>
                    <p class="text-gray-600">Register new visitor and automatically check them in</p>
                </div>
                <a href="{{ route('guard.dashboard') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                    📊 Dashboard
                </a>
            </div>

            <!-- Alert Messages -->
            <div id="alertContainer" class="hidden mb-6">
                <div id="alertBox" class="rounded-lg p-4">
                    <p id="alertMessage" class="font-medium"></p>
                </div>
            </div>

            <!-- Registration Form -->
            <form id="registrationForm" class="space-y-8">
                @csrf

                <!-- Visitor Information Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Visitor Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mobile Number -->
                        <div>
                            <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Mobile Number *
                            </label>
                            <input type="text" id="mobile_number" name="mobile_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="+91 XXXXX XXXXX" maxlength="15" required autofocus>
                            <p class="mt-1 text-xs text-gray-500">This will be the permanent visitor ID</p>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name *
                            </label>
                            <input type="text" id="name" name="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="John Doe" maxlength="255" required>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address *
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="123 Main Street, City, State" maxlength="500" required></textarea>
                        </div>

                        <!-- Purpose -->
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Purpose of Visit *
                            </label>
                            <select id="purpose" name="purpose"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required onchange="toggleCustomPurposeReg(this)">
                                <option value="">Select purpose...</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Interview">Interview</option>
                                <option value="Delivery">Delivery</option>
                                <option value="Sales Visit">Sales Visit</option>
                                <option value="Service/Repair">Service/Repair</option>
                                <option value="Consultation">Consultation</option>
                                <option value="Personal Visit">Personal Visit</option>
                                <option value="Official Work">Official Work</option>
                                <option value="Other">Other (specify below)</option>
                            </select>
                        </div>

                        <!-- Custom Purpose (shown when "Other" is selected) -->
                        <div id="customPurposeFieldReg" class="hidden">
                            <label for="customPurpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Please specify purpose *
                            </label>
                            <input type="text" id="customPurpose" name="custom_purpose"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter custom purpose">
                        </div>

                        <!-- Vehicle Number -->
                        <div>
                            <label for="vehicle_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Vehicle Number <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <input type="text" id="vehicle_number" name="vehicle_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="MH 12 AB 1234" maxlength="50">
                        </div>

                        <!-- Photo Upload -->
                        <div class="md:col-span-2">
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                                Visitor Photo * <span class="text-xs text-gray-500">(Required - Max 2MB, JPG/PNG)</span>
                            </label>
                            <div class="mt-2">
                                <div id="photoPreviewContainer" class="hidden mb-4">
                                    <img id="photoPreview" src="" alt="Photo Preview"
                                        class="w-48 h-48 object-cover rounded-lg border-2 border-gray-200">
                                    <button type="button" id="removePhoto"
                                        class="mt-2 text-sm text-red-600 hover:text-red-900 font-medium">
                                        Remove Photo
                                    </button>
                                </div>
                                <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <p id="photoError" class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carry Items Section -->
                <div class="border-b pb-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Carry Items</h2>
                            <p class="text-sm text-gray-600">Add items the visitor is bringing in (optional)</p>
                        </div>
                        <button type="button" id="addItemBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm font-medium">
                            + Add Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4">
                        <!-- Items will be added here dynamically -->
                        <p id="noItemsMessage" class="text-gray-500 text-center py-4">No items added yet</p>
                    </div>
                </div>

                <!-- Auto Check-in Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Automatic Check-In</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                The visitor will be automatically checked in after registration. All items will be
                                marked as brought in.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" id="submitBtn"
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-lg font-medium flex items-center justify-center">
                        <span id="submitText">✅ Register & Check In</span>
                        <span id="submitLoader" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12c0 4.313-2.673 8.291-7.829 8.291 1.36 0 2.618-1.041 3.37-2.836a4.009 4.009 0 0012 8c0-4.313 2.673-8.291 7.829-8.291 1.36 0 2.618 1.041 3.37 2.836a4.009 4.009 0 01-2.865 4.009 4.009 0 01-3.37 2.836A6.98 6.98 0 01-8 12h2z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <a href="{{ route('guard.entries.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let itemIndex = 0;

        // Photo upload handling
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        const photoPreviewContainer = document.getElementById('photoPreviewContainer');
        const removePhotoBtn = document.getElementById('removePhoto');
        const photoError = document.getElementById('photoError');

        photoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handlePhotoFile(e.target.files[0]);
            }
        });

        removePhotoBtn.addEventListener('click', () => {
            photoInput.value = '';
            photoPreviewContainer.classList.add('hidden');
            hidePhotoError();
        });

        function handlePhotoFile(file) {
            hidePhotoError();

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                showPhotoError('Invalid file type. Please upload a JPG or PNG image.');
                return;
            }

            // Validate file size (2MB)
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                showPhotoError('File size exceeds 2MB limit. Please choose a smaller file.');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                photoPreview.src = e.target.result;
                photoPreviewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function showPhotoError(message) {
            photoError.textContent = message;
            photoError.classList.remove('hidden');
        }

        function hidePhotoError() {
            photoError.classList.add('hidden');
        }

        // Add Item functionality
        document.getElementById('addItemBtn').addEventListener('click', () => {
            addItemField();
        });

        function addItemField() {
            const container = document.getElementById('itemsContainer');
            const noItemsMsg = document.getElementById('noItemsMessage');

            if (noItemsMsg) {
                noItemsMsg.remove();
            }

            const itemDiv = document.createElement('div');
            itemDiv.className = 'border border-gray-200 rounded-lg p-4 bg-gray-50';
            itemDiv.dataset.itemIndex = itemIndex;

            itemDiv.innerHTML = `
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-sm font-medium text-gray-900">Item ${itemIndex + 1}</h3>
                            <button type="button" onclick="removeItem(${itemIndex})" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                Remove
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                                <input type="text" name="items[${itemIndex}][item_name]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Laptop, Bag, etc." required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item Type *</label>
                                <select name="items[${itemIndex}][item_type]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="">Select type...</option>
                                    <option value="personal">Personal (Bag, Laptop, Phone)</option>
                                    <option value="office">Office Equipment</option>
                                    <option value="delivery">Delivery (Package, Box)</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item Photo (Optional)</label>
                                <input type="file" name="items[${itemIndex}][item_photo]" accept=".jpg,.jpeg,.png"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    `;

            container.appendChild(itemDiv);
            itemIndex++;
        }

        window.removeItem = function (index) {
            const itemDiv = document.querySelector(`[data-item-index="${index}"]`);
            if (itemDiv) {
                itemDiv.remove();
            }

            // Show "no items" message if no items left
            const container = document.getElementById('itemsContainer');
            if (container.children.length === 0) {
                container.innerHTML = '<p id="noItemsMessage" class="text-gray-500 text-center py-4">No items added yet</p>';
            }
        };

        // Toggle custom purpose field
        function toggleCustomPurposeReg(selectElement) {
            const customPurposeField = document.getElementById('customPurposeFieldReg');
            const customPurposeInput = document.getElementById('customPurpose');
            
            if (selectElement.value === 'Other') {
                customPurposeField.classList.remove('hidden');
                customPurposeInput.required = true;
            } else {
                customPurposeField.classList.add('hidden');
                customPurposeInput.required = false;
                customPurposeInput.value = '';
            }
        }

        // Form submission
        document.getElementById('registrationForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Validate photo
            if (!photoInput.files || photoInput.files.length === 0) {
                showPhotoError('Visitor photo is required. Please upload a photo.');
                return;
            }

            showLoading(true);

            const formData = new FormData(e.target);
            
            // Handle custom purpose: if "Other" is selected, use custom_purpose value
            const purposeSelect = document.getElementById('purpose').value;
            const customPurpose = document.getElementById('customPurpose').value;
            
            if (purposeSelect === 'Other' && customPurpose) {
                formData.set('purpose', customPurpose);
                formData.delete('custom_purpose');
            }
            
            formData.append('auto_checkin', '1');

            try {
                const response = await fetch('{{ route('guard.entries.visitor.register') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const message = data.message + (data.items_count > 0 ? ` ${data.items_count} items added.` : '');
                    showAlert('Success', message);

                    // Redirect to entry details page to add/manage items
                    setTimeout(() => {
                        if (data.entry && data.entry.id) {
                            window.location.href = `/guard/entries/${data.entry.id}`;
                        } else {
                            window.location.href = "{{ route('guard.dashboard') }}";
                        }
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        showAlert('Error', errorMessages);
                    } else {
                        showAlert('Error', data.message || 'Registration failed. Please try again.');
                    }
                    hideLoading();
                }
            } catch (error) {
                console.error(error);
                showAlert('Error', 'Registration failed. Please check your connection and try again.');
                hideLoading();
            }
        });

        // Helper functions
        function showLoading(show) {
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');
            const submitBtn = document.getElementById('submitBtn');

            if (show) {
                submitText.classList.add('hidden');
                submitLoader.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            } else {
                submitText.classList.remove('hidden');
                submitLoader.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }

        function showAlert(type, message) {
            const container = document.getElementById('alertContainer');
            const box = document.getElementById('alertBox');
            const messageText = document.getElementById('alertMessage');

            messageText.textContent = message;
            container.classList.remove('hidden');

            // Remove all color classes
            box.classList.remove('bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700', 'border', 'border-green-200', 'border-red-200');

            if (type === 'Success') {
                box.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                box.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }

            // Scroll to alert
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    </script>
@endpush