<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-['Figtree'] antialiased min-h-screen relative flex flex-col items-center justify-center  bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600">
    <!-- Background Glow Elements -->
    <div class="absolute inset-0 -z-10">
        <div class="absolute w-80 h-80 bg-pink-400/30 rounded-full blur-3xl top-10 left-10 animate-pulse"></div>
        <div class="absolute w-96 h-96 bg-indigo-400/30 rounded-full blur-3xl bottom-10 right-10 animate-pulse"></div>
    </div>

    <!-- Texture Overlay -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>

    <!-- Main Content -->
    <div class="relative z-10 flex flex-col items-center w-full px-6 sm:px-0 animate-fadeIn">

        <!-- Auth Card -->
        <div class="w-full text-white">
            <!-- Logo & App Name -->
            <div class="mb-0 text-center">
                <a href="/" class="flex items-center justify-center space-x-3">
                    {{-- <x-application-logo class="w-16 h-16 text-white drop-shadow-xl" /> --}}
                    <img src="{{ asset('assets/images/logo mora.png') }}"
                        class="lg:w-1/4 w-full h-60 text-white drop-shadow-xl object-cover" alt="">
                    {{-- <span class="text-3xl font-semibold text-white tracking-tight drop-shadow-md">
                        {{ config('app.name', 'POS App') }}
                    </span> --}}
                </a>
            </div>
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-8 text-sm text-white/80 tracking-wide">
            © {{ date('Y') }} {{ config('app.name', 'POS App') }} — All rights reserved.
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.7s ease-out forwards;
        }
    </style>
</body>

</html>
