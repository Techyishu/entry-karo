<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Entry Karo')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 h-full">
    <div class="min-h-full flex flex-col">
        <!-- Navigation -->
        @auth
            <nav class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <span class="text-xl font-bold text-gray-900">Entry Karo</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isSuperAdmin())
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    Super Admin
                                </span>
                            @elseif(Auth::user()->isGuard())
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    Guard
                                </span>
                            @elseif(Auth::user()->isCustomer())
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Customer
                                </span>
                            @endif
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm">&copy; {{ date('Y') }} Entry Karo. All rights reserved.</p>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>