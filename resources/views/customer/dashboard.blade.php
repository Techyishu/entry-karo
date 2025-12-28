@extends('layouts.app')

@section('title', 'Customer Dashboard - Entry Karo')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Customer Dashboard</h1>
                    <p class="text-gray-600">Welcome, {{ $customer->name }}!</p>
                </div>
                <a href="{{ route('logout') }}" class="text-blue-600 hover:text-blue-900">
                    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4 4m0 0L2 12h20a2 2 0 012-12 2H3m13 4a2 2 0 011-2 12H7a2 2 0 011-2 2m0 0 006 12h8a2 2 0 001-2 12H8" />
                    </svg>
                    Logout
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 font-medium">Total Guards</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $guards->count() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 font-medium">Active Entries Today</p>
                    <p class="text-2xl font-bold text-green-900">
                        {{ \App\Models\Entry::whereIn('guard_id', $guards->pluck('id'))->whereDate('in_time', now())->count() }}
                    </p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-600 font-medium">Total Entries (All Time)</p>
                    <p class="text-2xl font-bold text-purple-900">
                        {{ \App\Models\Entry::whereIn('guard_id', $guards->pluck('id'))->count() }}</p>
                </div>
            </div>

            <!-- Guards Management -->
            <div class="border-t pt-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Your Guards</h2>
                    <a href="{{ route('customer.guards.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm font-medium">
                        + Add Guard
                    </a>
                </div>

                @if ($guards->count() > 0)
                    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mobile</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Entries</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($guards as $guard)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="font-medium text-gray-900">{{ $guard->name }}</p>
                                            <p class="text-sm text-gray-500">Guard ID: {{ $guard->id }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900">{{ $guard->email }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900">{{ $guard->mobile_number ?: '--' }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($guard->password)
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 8l-4 4h2a2 2 0 01-2 12 8.92a2 2 0 01-2 12-6" />
                                                    </svg>
                                                    <span class="text-green-600 ml-1">Active</span>
                                                </span>
                                            @else
                                                <span class="text-gray-400">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            {{ $guard->entries->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('customer.guards.show', $guard->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                    View
                                                </a>
                                                <form action="{{ route('customer.guards.delete', $guard->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-8 text-center">
                        <p class="text-gray-500 text-lg mb-2">No guards added yet</p>
                        <p class="text-sm text-gray-600 mb-4">Start by adding your first guard to manage visitor entries and
                            check-in/out visitors.</p>
                        <a href="{{ route('customer.guards.create') }}"
                            class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm font-medium">
                            Add Your First Guard
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Action -->
            <div class="border-t pt-6 mt-6">
                <a href="{{ route('guard.dashboard') }}"
                    class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                    Go to Guard Entry Screen
                </a>
            </div>

            <div class="border-t pt-6">
                <p class="text-sm text-gray-500">
                    As a customer, you can view your own entries and manage guards assigned to your organization.
                    Guards can check-in/out visitors and track carry items.
                </p>
            </div>
        </div>
@endsection