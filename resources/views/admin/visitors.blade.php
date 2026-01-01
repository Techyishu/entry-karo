@extends('layouts.app')

@section('title', 'All Visitors - Entry Karo')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">All Visitors</h1>
                    <p class="text-gray-600 mt-2">Total: {{ $visitors->total() }} visitors</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    📊 Back to Dashboard
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Visitors Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Visitor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mobile Number
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Address
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Purpose
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vehicle
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Registered
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($visitors as $visitor)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($visitor->photo_path)
                                                <img src="{{ Storage::url($visitor->photo_path) }}" alt="{{ $visitor->name }}"
                                                    class="w-10 h-10 rounded-full object-cover mr-3">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-purple-600 font-medium">{{ substr($visitor->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $visitor->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-gray-900">{{ $visitor->mobile_number }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-900 max-w-xs truncate" title="{{ $visitor->address }}">
                                            {{ Str::limit($visitor->address, 40) }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-900 max-w-xs truncate" title="{{ $visitor->purpose }}">
                                            {{ Str::limit($visitor->purpose, 30) }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-gray-900">{{ $visitor->vehicle_number ?? '--' }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-600">{{ $visitor->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $visitor->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('admin.visitors.delete', $visitor) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this visitor? This will also delete all their entries and carry items.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        No visitors found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($visitors->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        {{ $visitors->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection