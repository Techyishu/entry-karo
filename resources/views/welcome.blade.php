<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ENTRYKARO') }} - Digital Visitor Entry System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-slate-800 antialiased">

    <!-- Navigation -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-28">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="{{asset('images/logo.png')}}" alt="EntryKaro" class="h-20
                    w-auto">
                </div>
                <!-- Nav Links -->
                <div class="hidden md:flex space-x-8">
                    <a href="#problem" class="text-slate-600 hover:text-green-600 font-medium transition">Why
                        EntryKaro</a>
                    <a href="#how-it-works" class="text-slate-600 hover:text-green-600 font-medium transition">How It
                        Works</a>
                    <a href="#pricing" class="text-slate-600 hover:text-green-600 font-medium transition">Pricing</a>
                </div>
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="@if(Auth::user()->isSuperAdmin()) {{ route('admin.dashboard') }} @elseif(Auth::user()->isCustomer()) {{ route('customer.dashboard') }} @else {{ route('guard.dashboard') }} @endif"
                            class="text-slate-600 hover:text-green-600 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-green-600 font-medium px-4 py-2">Log
                            in</a>
                        <a href="{{ route('register') }}"
                            class="hidden sm:inline-flex bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full font-medium transition shadow-lg shadow-green-200">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- 1. HERO SECTION -->
    <section class="pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 border border-green-100 text-green-700 text-sm font-semibold mb-8">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                Digital Visitor Management
            </div>

            <h1
                class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
                Stop Using <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-violet-600">Paper Entry
                    Registers</span>
            </h1>

            <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-600 mb-10">
                ENTRYKARO is a fast, digital visitor entry system for <br class="hidden md:block" /> societies,
                factories, and schools.
            </p>

            <!-- Bullets -->
            <div class="flex flex-wrap justify-center gap-4 text-slate-700 font-medium mb-10">
                <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Mobile number based entry
                </div>
                <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    IN–OUT tracking in one screen
                </div>
                <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Photo & item recording
                </div>
            </div>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-green-600 hover:bg-green-700 transition transform hover:-translate-y-0.5 shadow-xl shadow-green-200">
                    Get Started
                </a>
                <a href="#how-it-works"
                    class="inline-flex items-center justify-center px-8 py-4 border-2 border-slate-200 text-lg font-bold rounded-full text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition">
                    <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    See How It Works
                </a>
            </div>
        </div>

        <!-- Abstract Background -->
        <div class="absolute top-0 left-0 w-full h-full -z-10 bg-white">
            <div
                class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
            </div>
            <div
                class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-[-20%] left-[20%] w-[500px] h-[500px] bg-violet-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000">
            </div>
        </div>
    </section>

    <!-- 2. PROBLEM SECTION -->
    <section id="problem" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-green-600 font-bold tracking-wide uppercase text-sm mb-3">The Problem</h2>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Why Paper Registers Don’t Work Anymore</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Entries missing</h3>
                    <p class="text-slate-600 leading-relaxed">Guards often forget to write down details or handwriting
                        is illegible.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">No security proof</h3>
                    <p class="text-slate-600 leading-relaxed">Anyone can write a fake name. No way to verify who
                        actually entered.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Hard to track</h3>
                    <p class="text-slate-600 leading-relaxed">Finding who came 3 days ago takes hours of flipping pages.
                    </p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Registers get lost</h3>
                    <p class="text-slate-600 leading-relaxed">Paper registers get torn, wet, or lost, destroying all
                        history.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. SOLUTION SECTION -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-green-600 font-bold tracking-wide uppercase text-sm mb-3">The Solution</h2>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">One Simple System. Everything Tracked.</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="flex flex-col items-start p-6 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Mobile number = visitor ID</h3>
                    <p class="text-slate-600">No forms to fill. Just enter mobile number to identify visitors instantly.
                    </p>
                </div>
                <!-- Feature 2 -->
                <div
                    class="flex flex-col items-start p-6 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">One-time registration</h3>
                    <p class="text-slate-600">Regular visitors are saved. Next time, just 1 tap to enter.</p>
                </div>
                <!-- Feature 3 -->
                <div
                    class="flex flex-col items-start p-6 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">IN–OUT in single row</h3>
                    <p class="text-slate-600">See exactly when they came and when they left in one glance.</p>
                </div>
                <!-- Feature 4 -->
                <div
                    class="flex flex-col items-start p-6 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Visitor photo capture</h3>
                    <p class="text-slate-600">Take a photo of the visitor or their vehicle for security proof.</p>
                </div>
                <!-- Feature 5 -->
                <div
                    class="flex flex-col items-start p-6 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Carry items tracking</h3>
                    <p class="text-slate-600">Record laptops, parcels, or tools visitors bring in and take out.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. HOW IT WORKS -->
    <section id="how-it-works" class="py-20 bg-slate-900 text-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-green-600 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-600 rounded-full blur-3xl opacity-20">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-green-400 font-bold tracking-wide uppercase text-sm mb-3">Simple Process</h2>
                <h2 class="text-3xl md:text-4xl font-bold">How It Works</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-24 left-[16%] right-[16%] h-0.5 bg-slate-700 -z-10"></div>

                <!-- Step 1 -->
                <div class="text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-green-600 rounded-full flex items-center justify-center text-2xl font-bold shadow-lg shadow-green-900/50 mb-6 border-4 border-slate-800">
                        1</div>
                    <h3 class="text-xl font-bold mb-3">Guard enters number</h3>
                    <p class="text-slate-400">Security guard asks for mobile number and types it in.</p>
                </div>
                <!-- Step 2 -->
                <div class="text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-green-600 rounded-full flex items-center justify-center text-2xl font-bold shadow-lg shadow-green-900/50 mb-6 border-4 border-slate-800">
                        2</div>
                    <h3 class="text-xl font-bold mb-3">Press IN / OUT</h3>
                    <p class="text-slate-400">Mark entry with one click. First time visitors get registered.</p>
                </div>
                <!-- Step 3 -->
                <div class="text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-green-600 rounded-full flex items-center justify-center text-2xl font-bold shadow-lg shadow-green-900/50 mb-6 border-4 border-slate-800">
                        3</div>
                    <h3 class="text-xl font-bold mb-3">Saved Automatically</h3>
                    <p class="text-slate-400">Data is instantly synced to the cloud admin panel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. WHO IS IT FOR -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Who Is This For?</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Housing Societies -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:shadow-md transition">
                    <div
                        class="w-12 h-12 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800">Housing Societies</span>
                </div>
                <!-- Factories -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:shadow-md transition">
                    <div
                        class="w-12 h-12 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800">Factories</span>
                </div>
                <!-- Schools & Colleges -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:shadow-md transition">
                    <div
                        class="w-12 h-12 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800">Schools & Colleges</span>
                </div>
                <!-- Offices -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:shadow-md transition">
                    <div
                        class="w-12 h-12 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800">Offices</span>
                </div>
                <!-- Warehouses -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 text-center hover:shadow-md transition">
                    <div
                        class="w-12 h-12 mx-auto bg-slate-200 text-slate-600 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800">Warehouses</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. PRICING SECTION -->
    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-green-600 font-bold tracking-wide uppercase text-sm mb-3">Affordable Pricing</h2>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Choose Your Plan</h2>
                <div class="bg-green-50 inline-block px-4 py-1 rounded-full text-green-700 font-semibold text-sm mt-4">
                    No SMS. No QR. No App Required.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- BASIC -->
                <div
                    class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:border-green-300 hover:shadow-lg transition relative">
                    <h3 class="text-xl font-bold text-slate-900">BASIC</h3>
                    <div class="mt-4 mb-6">
                        <span class="text-4xl font-extrabold text-slate-900">₹99</span>
                        <span class="text-slate-500">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-600">
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>Unlimited Visitors</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>10 Days Data History</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>1 Gate/Guard Login</li>
                    </ul>
                    <button onclick="document.getElementById('contact').scrollIntoView()"
                        class="w-full bg-slate-100 text-slate-800 font-bold py-3 rounded-xl hover:bg-slate-200 transition">Contact
                        Us</button>
                </div>

                <!-- PRO -->
                <div
                    class="bg-white rounded-2xl p-8 border-2 border-green-500 shadow-xl relative transform scale-105 md:scale-105 z-10">
                    <div
                        class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg">
                        POPULAR</div>
                    <h3 class="text-xl font-bold text-slate-900">PRO</h3>
                    <div class="mt-4 mb-6">
                        <span class="text-4xl font-extrabold text-green-600">₹199</span>
                        <span class="text-slate-500">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-700 font-medium">
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>Unlimited Visitors</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>Unlimited History</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>Multiple Gates</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>Priority Support</li>
                    </ul>
                    <button onclick="document.getElementById('contact').scrollIntoView()"
                        class="w-full bg-green-600 text-white font-bold py-3 rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200">Contact
                        Us</button>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. TRUST SECTION -->
    <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Built for Indian Security Needs</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center px-4">
                <div>
                    <div
                        class="bg-white p-4 rounded-full w-20 h-20 mx-auto flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Any Mobile Browser</h4>
                    <p class="text-slate-500 text-sm">Chrome, Opera, Safari. No app installation needed.</p>
                </div>
                <div>
                    <div
                        class="bg-white p-4 rounded-full w-20 h-20 mx-auto flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Easy for Guards</h4>
                    <p class="text-slate-500 text-sm">No training required. Simple buttons and Hindi support possible.
                    </p>
                </div>
                <div>
                    <div
                        class="bg-white p-4 rounded-full w-20 h-20 mx-auto flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Fast & Reliable</h4>
                    <p class="text-slate-500 text-sm">Works even on slow 4G networks.</p>
                </div>
                <div>
                    <div
                        class="bg-white p-4 rounded-full w-20 h-20 mx-auto flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Secure Data</h4>
                    <p class="text-slate-500 text-sm">Data stored securely on cloud. No risk of lost registers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FINAL CTA & CONTACT -->
    <section id="contact" class="py-20 bg-green-900 text-white text-center px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Replace Your Paper Register Today</h2>
            <p class="text-green-200 text-xl mb-10 max-w-2xl mx-auto">Join hundreds of societies and factories upgrading
                to digital entry.</p>
            <div class="bg-white rounded-xl p-8 max-w-lg mx-auto shadow-2xl">
                <h3 class="text-slate-800 text-2xl font-bold mb-6">Start Using ENTRYKARO</h3>
                <form class="space-y-4"
                    onsubmit="event.preventDefault(); alert('Thank you! We will contact you shortly.');">
                    <div>
                        <label class="block text-left text-slate-600 text-sm font-bold mb-2">Your Mobile Number</label>
                        <input type="tel" placeholder="98765 43210"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-green-500 focus:outline-none"
                            required>
                    </div>
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-lg">
                        Get Demo
                    </button>
                    <p class="text-xs text-slate-400 mt-4">We'll assume this is for a Demo request.</p>
                </form>
            </div>
        </div>
    </section>

    <!-- 9. FOOTER -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <img src="{{asset('images/logo.png')}}" alt="EntryKaro" class="h-10 w-auto">
                    <p class="mt-2 text-sm">Digital Visitor Management System for India.</p>
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="#" class="hover:text-white transition">Contact</a>
                </div>
            </div>
            <div class="mt-8 text-center text-xs text-slate-600">
                &copy; {{ date('Y') }} ENTRYKARO. All rights reserved.
            </div>
        </div>
    </footer>

</body>

</html>