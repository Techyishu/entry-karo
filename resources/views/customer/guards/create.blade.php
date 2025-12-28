@extends('layouts.app')

@section('title', 'Add New Guard - Customer Dashboard - Entry Karo')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add New Guard</h1>
                    <p class="text-gray-600 mt-1">Create a new guard account for your organization</p>
                </div>
                <a href="{{ route('customer.dashboard') }}" class="text-blue-600 hover:text-blue-900">
                    <svg class="w-5 h-5 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('customer.guards.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Guard Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
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
                    <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}"
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
                        Password <span class="text-red-600">*</span>
                    </label>
                    <input type="password" name="password" id="password" required minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="Minimum 8 characters">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Must be at least 8 characters long.</p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="h-6 w-6 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium text-blue-900 mb-2">About Guards</p>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Guards can check-in and check-out visitors</li>
                                <li>• They can track carry items for each visitor</li>
                                <li>• Guards can only see entries they created</li>
                                <li>• You can manage all your guards from the dashboard</li>
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
                    <a href="{{ route('customer.dashboard') }}"
                        class="flex-1 px-4 py-3 bg-white border-2 border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium text-center">
                        Create Guard
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection