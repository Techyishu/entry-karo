@extends('layouts.app')

@section('title', 'Guard Entry Screen - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Visitor Entry Screen</h1>
                    <p class="text-gray-600 mt-2">Check-in and check-out visitors</p>
                </div>
                <a href="{{ route('guard.dashboard') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    📊 Go to Dashboard
                </a>
            </div>

            <!-- Search Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Search Visitor</h2>
                    <button type="button" id="addNewVisitorBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm font-medium">
                        + Add New Visitor
                    </button>
                </div>

                <form id="searchForm" class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Mobile Number *
                            </label>
                            <input type="text" id="mobile_number" name="mobile_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="+91 XXXXX XXXXX" required autofocus>
                            <p id="searchError" class="mt-2 text-sm text-red-600 hidden"></p>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" id="searchBtn"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-blue-700">
                    <div class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12c0 4.313-2.673 8.291-7 8.291-1.36 0-2.618-1.041-3.37-2.836a4.009 4.009 0 011-2.865 4.009 4.009 0 013.37-2.836A6.98 6.98 0 018 12h-2c0 1.924-1.285 3.617-3.37 4.836A4.009 4.009 0 0012 8c0-4.313 2.673-8.291 7-8.291 1.36 0 2.618 1.041 3.37 2.836a4.009 4.009 0 01-2.865 4.009 4.009 0 01-3.37 2.836A6.98 6.98 0 01-8 12h2z">
                            </path>
                        </svg>
                        <span>Searching...</span>
                    </div>
                </div>
            </div>

            <!-- Visitor Not Found - Registration Form -->
            <div id="visitorRegistration" class="hidden bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <span class="text-yellow-600">⚠</span> New Visitor Registration
                    </h2>
                    <button type="button" id="cancelRegistration" class="text-sm text-gray-600 hover:text-gray-900">
                        Cancel
                    </button>
                </div>

                <form id="registrationForm" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="regName" class="block text-sm font-medium text-gray-700 mb-2">
                                Name *
                            </label>
                            <input type="text" id="regName" name="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label for="regMobileNumber" class="block text-sm font-medium text-gray-700 mb-2">
                                Mobile Number *
                            </label>
                            <input type="text" id="regMobileNumber" name="mobile_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>
                        </div>
                    </div>

                    <div>
                        <label for="regAddress" class="block text-sm font-medium text-gray-700 mb-2">
                            Address *
                        </label>
                        <input type="text" id="regAddress" name="address"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <div>
                        <label for="regPurpose" class="block text-sm font-medium text-gray-700 mb-2">
                            Purpose of Visit *
                        </label>
                        <select id="regPurpose" name="purpose"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required onchange="toggleCustomPurpose(this)">
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

                    <div id="customPurposeField" class="hidden">
                        <label for="regCustomPurpose" class="block text-sm font-medium text-gray-700 mb-2">
                            Please specify purpose *
                        </label>
                        <input type="text" id="regCustomPurpose" name="custom_purpose"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter custom purpose">
                    </div>

                    <div>
                        <label for="regVehicleNumber" class="block text-sm font-medium text-gray-700 mb-2">
                            Vehicle Number
                        </label>
                        <input type="text" id="regVehicleNumber" name="vehicle_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="MH XX XX XXXX">
                    </div>

                    <div>
                        <label for="regPhoto" class="block text-sm font-medium text-gray-700 mb-2">
                            Visitor Photo
                        </label>
                        <input type="file" id="regPhoto" name="photo" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Register & Check-In
                    </button>
                </form>
            </div>

            <!-- Visitor Found - Check-in/Check-out Section -->
            <div id="visitorActions" class="hidden">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <span class="text-green-600">✓</span> Visitor Found
                    </h2>

                    <!-- Visitor Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Visitor Details</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-500">Name:</span>
                                    <span id="visitorName" class="text-gray-900 font-medium ml-2">--</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Mobile:</span>
                                    <span id="visitorMobile" class="text-gray-900 font-medium ml-2">--</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Address:</span>
                                    <span id="visitorAddress" class="text-gray-900 ml-2">--</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Visit Information
                            </h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-500">Purpose:</span>
                                    <span id="visitorPurpose" class="text-gray-900 ml-2">--</span>
                                </div>
                                <div id="vehicleInfo" class="hidden">
                                    <span class="text-gray-500">Vehicle:</span>
                                    <span id="visitorVehicle" class="text-gray-900 ml-2">--</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visitor Photo -->
                    <div id="visitorPhotoContainer" class="hidden mb-6">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Visitor Photo</h3>
                        <img id="visitorPhoto" src="" alt="Visitor Photo"
                            class="w-48 h-48 object-cover rounded-lg border-2 border-gray-200">
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t pt-6">
                        @if ($activeEntry)
                            <!-- Check-out Button (only if active entry exists) -->
                            <div class="flex gap-4">
                                <button type="button" id="checkOutBtn"
                                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-lg font-medium">
                                    🔴 Check OUT
                                </button>
                            </div>

                            <!-- Active Entry Details -->
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Active Entry Details
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-gray-500">Check-in Time:</span>
                                        <p id="activeInTime" class="text-gray-900 font-medium mt-1">--</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Duration:</span>
                                        <p id="activeDuration" class="text-gray-900 font-medium mt-1">--</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" id="viewEntryDetails"
                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        View Full Details &rarr;
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Check-in Button (if no active entry) -->
                            <form id="checkInForm" class="space-y-4">
                                @csrf
                                <input type="hidden" id="checkInVisitorId" name="visitor_id">

                                <div>
                                    <label for="visitPurpose" class="block text-sm font-medium text-gray-700 mb-2">
                                        Visit Purpose *
                                    </label>
                                    <select id="visitPurpose" name="purpose"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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

                                <div class="flex gap-4">
                                    <button type="submit" id="checkInBtn"
                                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-lg font-medium">
                                        🟢 Check IN
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messageContainer" class="hidden mb-6">
                <div id="messageBox" class="rounded-lg p-4">
                    <p id="messageText" class="font-medium"></p>
                </div>
            </div>

            <!-- Recent Entries -->
            @if ($activeEntry)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Today's Entries</h2>
                    <div class="text-gray-600 text-sm">
                        <p>View full entry history in your dashboard.</p>
                        <a href="{{ route('guard.dashboard') }}" class="text-blue-600 hover:text-blue-900 mt-2 inline-block">
                            Go to Dashboard &rarr;
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search visitor functionality
        document.getElementById('searchForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const mobileNumber = document.getElementById('mobile_number').value.trim();

            if (!mobileNumber) {
                showMessage('Please enter a mobile number.', 'error');
                return;
            }

            showLoading(true);
            hideSections();

            try {
                const response = await fetch('{{ route('guard.entries.search') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mobile_number: mobileNumber })
                });

                const data = await response.json();

                if (data.found) {
                    // Visitor exists, show their details and active entry
                    showVisitorFound(data.visitor, data.active_entry);
                } else {
                    // Visitor doesn't exist, show registration form inline
                    showVisitorRegistration(mobileNumber);
                }
            } catch (error) {
                showMessage('Error searching visitor. Please try again.', 'error');
                console.error(error);
            } finally {
                showLoading(false);
            }
        });

        // Add new visitor button handler
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

        // Toggle custom purpose field
        function toggleCustomPurpose(selectElement) {
            const customPurposeField = document.getElementById('customPurposeField');
            const customPurposeInput = document.getElementById('regCustomPurpose');
            
            if (selectElement.value === 'Other') {
                customPurposeField.classList.remove('hidden');
                customPurposeInput.required = true;
            } else {
                customPurposeField.classList.add('hidden');
                customPurposeInput.required = false;
                customPurposeInput.value = '';
            }
        }

        // Register visitor functionality
        document.getElementById('registrationForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            showLoading(true);

            try {
                const formData = new FormData(e.target);
                
                // Handle custom purpose: if "Other" is selected, use custom_purpose value
                const purposeSelect = document.getElementById('regPurpose').value;
                const customPurpose = document.getElementById('regCustomPurpose').value;
                
                if (purposeSelect === 'Other' && customPurpose) {
                    formData.set('purpose', customPurpose);
                    formData.delete('custom_purpose');
                }
                
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route('guard.entries.visitor.register') }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('Visitor registered and checked in! Redirecting to add items...', 'success');

                    // Redirect to entry details page to add items
                    setTimeout(() => {
                        if (data.entry && data.entry.id) {
                            window.location.href = `/guard/entries/${data.entry.id}`;
                        } else {
                            // Fallback: reload to show the visitor
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    showMessage(data.message || 'Registration failed.', 'error');
                }
            } catch (error) {
                showMessage('Error registering visitor. Please try again.', 'error');
                console.error(error);
            } finally {
                showLoading(false);
            }
        });

        // Check-in functionality
        document.getElementById('checkInForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const visitorId = document.getElementById('checkInVisitorId').value;
            const purpose = document.getElementById('visitPurpose').value;

            if (!visitorId || !purpose) {
                showMessage('Please fill all required fields.', 'error');
                return;
            }

            showLoading(true);

            try {
                const response = await fetch('{{ route('guard.entries.check-in') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        visitor_id: visitorId,
                        purpose: purpose
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('Visitor checked in successfully! Redirecting to add items...', 'success');

                    // Redirect to entry details page to add items
                    setTimeout(() => {
                        if (data.entry && data.entry.id) {
                            window.location.href = `/guard/entries/${data.entry.id}`;
                        } else {
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    showMessage(data.message || 'Check-in failed.', 'error');
                }
            } catch (error) {
                showMessage('Error checking in visitor. Please try again.', 'error');
                console.error(error);
            } finally {
                showLoading(false);
            }
        });

        // Check-out functionality
        document.getElementById('checkOutBtn').addEventListener('click', async function () {
            if (!confirm('Are you sure you want to check out this visitor?')) {
                return;
            }

            const entryId = {{ $activeEntry ? $activeEntry->id : 'null' }};

            if (!entryId) {
                showMessage('No active entry to check out.', 'error');
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
                    const duration = data.entry.duration_minutes;
                    showMessage(`Visitor checked out successfully! Duration: ${duration} minutes.`, 'success');

                    // Reload page after 2 seconds
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
        });

        // Cancel registration
        document.getElementById('cancelRegistration').addEventListener('click', function () {
            hideSections();
            document.getElementById('searchForm').reset();
            document.getElementById('mobile_number').focus();
        });

        // View entry details
        document.getElementById('viewEntryDetails').addEventListener('click', function () {
            window.location.href = "{{ $activeEntry ? route('guard.entries.show', $activeEntry->id) : '#' }}";
        });

        // Helper functions
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
                document.getElementById('visitorPhotoContainer').classList.remove('hidden');
            } else {
                document.getElementById('visitorPhotoContainer').classList.add('hidden');
            }

            if (activeEntry) {
                document.getElementById('checkInVisitorId').value = visitor.id;
                document.getElementById('activeInTime').textContent = formatDateTime(activeEntry.in_time);

                // Calculate and display duration
                const now = new Date();
                const inTime = new Date(activeEntry.in_time);
                const duration = Math.floor((now - inTime) / 60000); // Convert to minutes
                document.getElementById('activeDuration').textContent = duration + ' minutes (ongoing)';
            } else {
                document.getElementById('checkInVisitorId').value = visitor.id;
            }

            document.getElementById('visitorActions').classList.remove('hidden');
        }

        function showVisitorRegistration(mobileNumber) {
            document.getElementById('regMobileNumber').value = mobileNumber;
            document.getElementById('visitorRegistration').classList.remove('hidden');
            document.getElementById('regName').focus();
        }

        function showLoading(show) {
            document.getElementById('loadingIndicator').classList.toggle('hidden', !show);
        }

        function hideSections() {
            document.getElementById('visitorRegistration').classList.add('hidden');
            document.getElementById('visitorActions').classList.add('hidden');
            document.getElementById('messageContainer').classList.add('hidden');
        }

        function showMessage(text, type) {
            const container = document.getElementById('messageContainer');
            const box = document.getElementById('messageBox');
            const messageText = document.getElementById('messageText');

            messageText.textContent = text;
            container.classList.remove('hidden');

            box.classList.remove('bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700');

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

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        async function searchVisitor(mobileNumber) {
            showLoading(true);
            hideSections();

            try {
                const response = await fetch('{{ route('guard.entries.search') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
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
                console.error(error);
            } finally {
                showLoading(false);
            }
        }

        // Auto-focus mobile number on page load
        window.addEventListener('DOMContentLoaded', function () {
            document.getElementById('mobile_number').focus();
        });
    </script>
@endpush