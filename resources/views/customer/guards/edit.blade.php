@extends('layouts.app')

@section('title', 'Edit Guard - Customer Dashboard - Entry Karo')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Guard</h1>
                    <p class="text-gray-600 mt-1">Update guard account information</p>
                </div>
                <a href="{{ route('customer.guards.show', $guard->id) }}" class="text-blue-600 hover:text-blue-900">
                    <svg class="w-5 h-5 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Guard
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('customer.guards.update', $guard->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Guard Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $guard->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Enter guard's full name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $guard->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                        placeholder="guard@example.com (optional)">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Optional. Used for login and notifications.</p>
                </div>

                <!-- Mobile Number Field -->
                <div>
                    <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Mobile Number
                    </label>
                    <input type="tel" name="mobile_number" id="mobile_number"
                        value="{{ old('mobile_number', $guard->mobile_number) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mobile_number') border-red-500 @enderror"
                        placeholder="1234567890 (optional)" pattern="[0-9]{10,15}">
                    @error('mobile_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Optional. 10-15 digits without spaces or special characters.</p>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <input type="password" name="password" id="password" minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="Leave blank to keep current password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Leave blank to keep the current password. Must be at least 8
                        characters if changing.</p>
                </div>

                <!-- Info Box -->
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="h-6 w-6 text-yellow-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-medium text-yellow-900 mb-1">Important</p>
                            <ul class="text-sm text-yellow-800 space-y-1">
                                <li>• Changing email or password will update the guard's login credentials</li>
                                <li>• The guard will need to use the new credentials for their next login</li>
                                <li>• {{ $guard->entries->count() }} existing entries will not be affected</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Error Summary -->
                @if ($errors->any())
                    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="h-6 w-6 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-medium text-red-900 mb-2">Please fix the following errors:</p>
                                <ul class="text-sm text-red-800 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4 border-t">
                    <a href="{{ route('customer.guards.show', $guard->id) }}"
                        class="flex-1 px-4 py-3 bg-white border-2 border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium text-center">
                        Update Guard
                    </button>
                </div>
            </form>

            <!-- Delete Section -->
            <div class="border-t pt-6 mt-8">
                <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-2">Delete Guard</h3>
                    <p class="text-sm text-red-800 mb-4">
                        @if ($guard->entries->count() > 0)
                            This guard cannot be deleted because they have {{ $guard->entries->count() }} existing entries.
                            Please contact system administrator if you need to delete this guard.
                        @else
                            Permanently delete this guard account. This action cannot be undone.
                        @endif
                    </p>
                    <form action="{{ route('customer.guards.delete', $guard->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this guard? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 font-medium {{ $guard->entries->count() > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $guard->entries->count() > 0 ? 'disabled' : '' }}>
                            Delete Guard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection