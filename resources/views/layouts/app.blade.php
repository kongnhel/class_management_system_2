<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <title>{{ config('', 'class management system') }}</title> --}}
        <head>
            <link rel="icon" type="image/png" href="{{ asset('assets\image\nmu_Logo.png') }}">
            <title>{{ config('', 'Class Management System') }}</title>
        </head>
    
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="//unpkg.com/alpinejs" defer></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body ,div{
                font-family: 'Battambang', sans-serif;
                /* overflow-x: hidden; */
            }

            /* The custom-scrollbar styles from the sidebar have been moved here
                for global availability or if you prefer them in a central CSS file.
                If already defined in your app.css, you can remove this section. */
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }
            <style>
    body ,div{
        font-family: 'Battambang', sans-serif;
    }

    /* Hide parts you don't want to print */
    @media print {
        nav,                /* laravel sidebar */
        header,             /* page header */
        footer,             /* footer */
        .lg\:hidden,        /* mobile top bar */
        .no-print {         /* custom hide */
            display: none !important;
        }

        /* Force content full width on print */
        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Schedule table style */
        .schedule-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 14px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid black !important;
            padding: 6px;
            text-align: center;
        }

        /* A4 setup */
        @page {
            margin: 15mm;
            size: A4 portrait;
        }
    }
</style>

        </style>
    </head>
    <body class="font-sans antialiased" x-data="{ open: false }">

    @auth
        {{-- 
            *** START: CONSOLIDATED USER LOGIC FOR MOBILE TOP BAR & SIDEBAR *** This logic is moved here from navigation.blade.php to be available globally.
        --}}
        @php
            // 1. Get user and load profile relation
            $user = Auth::user()->loadMissing('userProfile');
            
            // 2. Determine profile picture URL
            $profilePath = $user->userProfile?->profile_picture_url;
            $profileUrl = $profilePath ? asset('storage/' . $profilePath) : null;

            // 3. Define translated role text using the match expression (PHP 8.0+)
            $roleText = match ($user->role) {
                'admin' => __('អ្នកគ្រប់គ្រង'),
                'professor' => __('សាស្ត្រាចារ្យ'),
                'student' => __('និស្សិត'),
                default => ''
            };
        @endphp
        {{-- *** END: CONSOLIDATED USER LOGIC *** --}}
    @endauth

        <div class="min-h-screen bg-gray-100">

            {{-- Include the modern sidebar --}}
            @include('layouts.navigation')

            {{-- Hamburger menu for mobile (outside the sidebar, typically in a top bar) --}}
        <div class="lg:hidden fixed top-0 left-0 w-full bg-white border-b border-gray-200 shadow-sm z-40 p-3 flex justify-between items-center font-['Battambang']">
            
            {{-- 1. Hamburger Button (Pushed to the left) --}}
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-800 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            @auth
            {{-- 2. User Profile and Role (New addition - positioned on the right) --}}
            <div class="flex items-center space-x-3 ml-auto">
                <div class="flex flex-col items-end leading-tight me-2 hidden sm:block">
                    <span class="text-sm font-semibold text-gray-800 truncate max-w-24">{{ $user->name }}</span>
                    @if($roleText)
                        <span class="text-xs text-gray-500">{{ $roleText }}</span>
                    @endif
                </div>

                <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center text-lg font-bold bg-gradient-to-br from-green-500 to-green-700 ring-2 ring-green-500/50">
                    @if($profileUrl)
                        <img src="{{ $profileUrl }}" 
                            alt="{{ __('Profile Picture') }}" 
                            class="h-full w-full object-cover">
                    @else
                        {{ Str::substr($user->name, 0, 1) }}
                    @endif
                </div>
            </div>
            @else
                {{-- Fallback: Original Logo/Text --}}
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 ms-2 ml-auto">
                    <img src="{{ asset('assets/image/nmu_Logo.png') }}" 
                        alt="NMU Logo" 
                        class="block h-8 w-auto">
                    
                    {{-- Full Name: Hidden on mobile (default) but visible from 'sm' (small) screens up --}}
                    <span class="text-base font-bold text-gray-800 hidden sm:inline-block">
                        {{ __('សកលវិទ្យាល័យជាតិមានជ័យ') }}
                    </span>

                    {{-- Short Name: Visible on mobile (default) but hidden from 'sm' (small) screens up --}}
                    <span class="text-base font-bold text-gray-800 sm:hidden">
                        {{ __('NMU') }}
                    </span>
                </a>
            @endauth
        </div>

            {{-- Overlay for mobile when sidebar is open --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/50 z-40 lg:hidden"
                 @click="open = false"
            ></div>

            {{-- Main Content Wrapper --}}
            <div class="flex flex-col min-h-screen lg:ml-64 pt-16 lg:pt-0" :class="{'ml-0': !open}">
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>

</html>