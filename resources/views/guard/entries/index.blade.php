@extends('layouts.app')

@section('title', 'Visitor Entry - Entry Karo')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Visitor Check-In</h1>
            <p class="text-gray-500 mt-2">Search by mobile number or add new visitor</p>
        </div>

        <!-- Initial Search State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6">
                <form id="searchForm" class="space-y-4">
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Enter Mobile Number
                        </label>
                        <div class="relative">
                            <input type="tel" id="mobile_number" name="mobile_number"
                                class="w-full pl-4 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-lg placeholder-gray-400"
                                placeholder="98765 43210" required autofocus autocomplete="off">
                            <button type="submit"
                                class="absolute right-2 top-2 bottom-2 bg-blue-600 text-white rounded-lg px-4 font-medium hover:bg-blue-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-4 flex items-center gap-3">
                    <div class="h-px bg-gray-100 flex-1"></div>
                    <span class="text-xs text-gray-400 font-medium">OR</span>
                    <div class="h-px bg-gray-100 flex-1"></div>
                </div>

                <button type="button" id="addNewVisitorBtn"
                    class="w-full mt-4 py-3 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition font-medium border border-gray-200 dashed flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Register New Visitor manually
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden mb-6 text-center py-8">
            <div class="inline-flex flex-col items-center">
                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-gray-500 mt-3 font-medium">Searching database...</p>
            </div>
        </div>

        <!-- Registration Form (Hidden by default) -->
        <div id="visitorRegistration"
            class="hidden bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900">New Visitor Details</h2>
                <button type="button" id="cancelRegistration" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <form id="registrationForm" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <!-- Form Grid -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" id="regMobileNumber" name="mobile_number"
                                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-500"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" id="regName" name="name"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <input type="text" id="regAddress" name="address"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Purpose *</label>
                                <select id="regPurpose" name="purpose"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500"
                                    required onchange="toggleCustomPurpose(this)">
                                    <option value="">Select...</option>
                                    <option value="Meeting">Meeting</option>
                                    <option value="Interview">Interview</option>
                                    <option value="Delivery">Delivery</option>
                                    <option value="Sales Visit">Sales Visit</option>
                                    <option value="Service/Repair">Service/Repair</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Personal Visit">Personal Visit</option>
                                    <option value="Official Work">Official Work</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle (Optional)</label>
                                <input type="text" id="regVehicleNumber" name="vehicle_number"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g. MH12AB1234">
                            </div>
                        </div>

                        <div id="customPurposeField" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specify Purpose *</label>
                            <input type="text" id="regCustomPurpose" name="custom_purpose"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Take Photo (Optional)</label>
                            <div class="relative">
                                <!-- Photo Preview -->
                                <div id="photoPreview" class="hidden mb-3">
                                    <img id="photoPreviewImage" src="" alt="Photo preview"
                                        class="w-full h-48 object-cover rounded-xl border-2 border-gray-200">
                                    <button type="button" onclick="clearPhotoPreview()"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Upload Area -->
                                <div id="photoUploadArea"
                                    class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                                    <input type="file" id="regPhoto" name="photo" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        onchange="previewPhoto(event)">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Tap to upload or take photo</p>
                                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, or any image (Max 10MB)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full mt-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold shadow-lg shadow-green-200">
                        Check In Visitor
                    </button>
                </form>
            </div>
        </div>

        <!-- Visitor Found / Actions (Hidden by default) -->
        <div id="visitorActions" class="hidden bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-green-900">Visitor Found</h2>
            </div>

            <div class="p-6">
                <!-- Visitor Profile Card -->
                <div class="flex items-start gap-4 mb-6">
                    <img id="visitorPhoto" src="" class="w-20 h-20 rounded-xl object-cover bg-gray-100 mb-2 hidden">
                    <div id="visitorPhotoPlaceholder"
                        class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h3 id="visitorName" class="text-xl font-bold text-gray-900"></h3>
                        <p id="visitorMobile" class="text-gray-500 font-medium"></p>
                        <p id="visitorAddress" class="text-sm text-gray-400 mt-1"></p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 mb-6 text-sm text-gray-600 space-y-1">
                    <p class="flex justify-between"><span>Last Purpose:</span> <span id="visitorPurpose"
                            class="font-medium text-gray-900"></span></p>
                    <p class="flex justify-between hidden" id="vehicleInfo"><span>Vehicle:</span> <span id="visitorVehicle"
                            class="font-medium text-gray-900"></span></p>
                </div>

                <!-- Active Entry Alert -->
                <div id="activeEntryDetails" class="hidden mb-6 bg-red-50 border border-red-100 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-red-700 font-bold mb-2">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                        Currently Checked In
                    </div>
                    <div class="flex justify-between text-sm text-red-600">
                        <span>Check-in:</span>
                        <span id="activeInTime" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between text-sm text-red-600">
                        <span>Duration:</span>
                        <span id="activeDuration" class="font-medium"></span>
                    </div>

                    <button type="button" id="checkOutBtn"
                        class="w-full mt-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-bold shadow-lg shadow-red-200">
                        Check Out Now
                    </button>
                    <button type="button" id="viewEntryDetails"
                        class="w-full mt-2 py-2 text-red-600 font-medium text-sm hover:underline">
                        View Details
                    </button>
                </div>

                <!-- Check In Form (Only if no active entry) -->
                <form id="checkInForm" class="hidden">
                    @csrf
                    <input type="hidden" id="checkInVisitorId" name="visitor_id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Visit Purpose</label>
                        <select id="visitPurpose" name="purpose"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 bg-white"
                            required>
                            <option value="">Select purpose...</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Interview">Interview</option>
                            <option value="Delivery">Delivery</option>
                            <option value="Sales Visit">Sales Visit</option>
                            <option value="Service">Service</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <button type="submit" id="checkInBtn"
                        class="w-full py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold shadow-lg shadow-green-200 text-lg">
                        Check In
                    </button>
                </form>
            </div>
        </div>

        <!-- Toast Message (Re-using the one from layout/dashboard if possible, or defining simple one here) -->
        <div id="messageContainer"
            class="hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-full px-4 max-w-sm">
            <div id="messageBox"
                class="bg-gray-800 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center justify-center text-center">
                <p id="messageText" class="text-sm font-medium"></p>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{-- Copying existing script logic exactly, just updating IDs references if I changed any, but I kept them strictly same
    --}}
    <script>
        // ... (The script logic is large, I will omit re-writing it entirely in thought, but I MUST include it in the file write) ...
        // I will copy the script block from Step 211 output exactly to ensure no breakage.
    </script>
    <script>
        // Search visitor functionality
        document.getElementById('searchForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const mobileNumber = document.getElementById('mobile_number').value.trim();
            if (!mobileNumber) { showMessage('Please enter a mobile number.', 'error'); return; }
            showLoading(true);
            hideSections();
            try {
                const response = await fetch('{{ route('guard.entries.search') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mobile_number: mobileNumber })
                });
                const data = await response.json();
                if (data.found) {
                    showVisitorFound(data.visitor, data.active_entry);
                } else {
                    showVisitorRegistration(mobileNumber);
                }
            } catch (error) {
                showMessage('Error searching visitor.', 'error');
                console.error(error);
            } finally {
                showLoading(false);
            }
        });

        document.getElementById('addNewVisitorBtn').addEventListener('click', function () {
            const mobileNumber = document.getElementById('mobile_number').value.trim();
            hideSections();
            if (mobileNumber) {
                showVisitorRegistration(mobileNumber);
            } else {
                showVisitorRegistration('');
                document.getElementById('regMobileNumber').removeAttribute('readonly');
                document.getElementById('regMobileNumber').focus();
            }
        });

        function toggleCustomPurpose(selectElement) {
            const field = document.getElementById('customPurposeField');
            const input = document.getElementById('regCustomPurpose');
            if (selectElement.value === 'Other') { field.classList.remove('hidden'); input.required = true; }
            else { field.classList.add('hidden'); input.required = false; input.value = ''; }
        }

        document.getElementById('registrationForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Client-side file size check
            const photoInput = document.getElementById('regPhoto');
            if (photoInput.files.length > 0 && photoInput.files[0].size > 10 * 1024 * 1024) {
                showMessage('Photo too large. Max 10MB allowed.', 'error');
                return;
            }

            showLoading(true);
            try {
                const formData = new FormData(e.target);
                const purposeSelect = document.getElementById('regPurpose').value;
                const customPurpose = document.getElementById('regCustomPurpose').value;
                if (purposeSelect === 'Other' && customPurpose) {
                    formData.set('purpose', customPurpose);
                    formData.delete('custom_purpose');
                }
                formData.append('_token', '{{ csrf_token() }}');
                const response = await fetch('{{ route('guard.entries.visitor.register') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    showMessage('Visitor registered!', 'success');
                    setTimeout(() => {
                        if (data.entry && data.entry.id) window.location.href = `/guard/entries/${data.entry.id}`;
                        else window.location.reload();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        showMessage(errorMessages, 'error');
                    } else {
                        showMessage(data.message || 'Registration failed.', 'error');
                    }
                }
            } catch (error) {
                console.error('Registration error:', error);
                showMessage('Error registering visitor.', 'error');
            }
            finally { showLoading(false); }
        });

        document.getElementById('checkInForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const visitorId = document.getElementById('checkInVisitorId').value;
            const purpose = document.getElementById('visitPurpose').value;
            showLoading(true);
            try {
                const response = await fetch('{{ route('guard.entries.check-in') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ visitor_id: visitorId, purpose: purpose })
                });
                const data = await response.json();
                if (data.success) {
                    showMessage('Visitor checked in!', 'success');
                    setTimeout(() => {
                        if (data.entry && data.entry.id) window.location.href = `/guard/entries/${data.entry.id}`;
                        else window.location.reload();
                    }, 1500);
                } else { showMessage(data.message || 'Check-in failed.', 'error'); }
            } catch (error) { showMessage('Error checking in.', 'error'); }
            finally { showLoading(false); }
        });

        document.getElementById('checkOutBtn').addEventListener('click', async function () {
            // Need entry ID. It's not in a static var here like dashboard. Use data attr or fetch?
            // The activeEntry object is passed dynamically. 
            // Better: When showing active entry, store the ID in a hidden field or variable.
            // I'll add a hidden input for entry ID or just use global var updated by JS.
            if (!confirm('Check out visitor?')) return;
            const entryId = window.activeEntryId; // Defined in showVisitorFound
            if (!entryId) { showMessage('No active entry.', 'error'); return; }
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
                    showMessage(`Checked out! Duration: ${data.entry.duration_minutes}m`, 'success');
                    setTimeout(() => window.location.reload(), 2000);
                } else { showMessage(data.message || 'Failed.', 'error'); }
            } catch (error) { showMessage('Error checking out.', 'error'); }
            finally { showLoading(false); }
        });

        document.getElementById('cancelRegistration').addEventListener('click', function () {
            hideSections();
            document.getElementById('searchForm').reset();
            document.getElementById('mobile_number').focus();
        });

        document.getElementById('viewEntryDetails').addEventListener('click', function () {
            if (window.activeEntryId) window.location.href = `/guard/entries/${window.activeEntryId}`;
        });

        // --- UI Helpers ---
        function showVisitorFound(visitor, activeEntry) {
            document.getElementById('visitorName').textContent = visitor.name;
            document.getElementById('visitorMobile').textContent = visitor.mobile_number;
            document.getElementById('visitorAddress').textContent = visitor.address;
            document.getElementById('visitorPurpose').textContent = visitor.purpose;

            if (visitor.vehicle_number) {
                document.getElementById('visitorVehicle').textContent = visitor.vehicle_number;
                document.getElementById('vehicleInfo').classList.remove('hidden');
            } else {
                document.getElementById('vehicleInfo').classList.add('hidden');
            }

            if (visitor.photo_path) {
                document.getElementById('visitorPhoto').src = `/storage/${visitor.photo_path}`;
                document.getElementById('visitorPhoto').classList.remove('hidden');
                document.getElementById('visitorPhotoPlaceholder').classList.add('hidden');
            } else {
                document.getElementById('visitorPhoto').classList.add('hidden');
                document.getElementById('visitorPhotoPlaceholder').classList.remove('hidden');
            }

            if (activeEntry) {
                window.activeEntryId = activeEntry.id; // STORE ID
                document.getElementById('activeInTime').textContent = formatDateTime(activeEntry.in_time);
                // Approx duration for display
                const now = new Date();
                const inTime = new Date(activeEntry.in_time);
                const duration = Math.floor((now - inTime) / 60000);
                document.getElementById('activeDuration').textContent = duration + ' mins';

                document.getElementById('activeEntryDetails').classList.remove('hidden');
                document.getElementById('checkInForm').classList.add('hidden');
            } else {
                window.activeEntryId = null;
                document.getElementById('checkInVisitorId').value = visitor.id;
                document.getElementById('activeEntryDetails').classList.add('hidden');
                document.getElementById('checkInForm').classList.remove('hidden');
            }
            document.getElementById('visitorActions').classList.remove('hidden');
        }

        function showVisitorRegistration(mobileNumber) {
            document.getElementById('regMobileNumber').value = mobileNumber;
            document.getElementById('visitorRegistration').classList.remove('hidden');
            document.getElementById('regName').focus();
        }

        function showLoading(show) { document.getElementById('loadingIndicator').classList.toggle('hidden', !show); }

        function hideSections() {
            document.getElementById('visitorRegistration').classList.add('hidden');
            document.getElementById('visitorActions').classList.add('hidden');
        }

        function showMessage(text, type) {
            const container = document.getElementById('messageContainer');
            const textEl = document.getElementById('messageText');
            textEl.textContent = text;
            container.classList.remove('hidden');
            setTimeout(() => { container.classList.add('hidden'); }, 4000);
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        // Photo preview functions
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('photoPreviewImage').src = e.target.result;
                    document.getElementById('photoPreview').classList.remove('hidden');
                    document.getElementById('photoUploadArea').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function clearPhotoPreview() {
            document.getElementById('regPhoto').value = '';
            document.getElementById('photoPreviewImage').src = '';
            document.getElementById('photoPreview').classList.add('hidden');
            document.getElementById('photoUploadArea').classList.remove('hidden');
        }

        window.addEventListener('DOMContentLoaded', function () {
            document.getElementById('mobile_number').focus();
        });
    </script>
@endpush