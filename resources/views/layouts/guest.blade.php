<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-lime-50 via-green-50 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md px-6 py-8 bg-white/80 dark:bg-gray-900/80 shadow-2xl rounded-2xl backdrop-blur-md border border-gray-200 dark:border-gray-800">
            
            <!-- Logo & Title -->
            <div class="flex flex-col items-center gap-4 mb-6">
                <a href="/">
                    <img src="{{ asset('images/3.png') }}" alt="Sagari Logo" class="w-32 drop-shadow-lg" />
                </a>
                <h2 class="text-2xl font-bold text-green-700 dark:text-lime-300">Welcome to Sagari Portal</h2>
                <p class="text-gray-500 dark:text-gray-400 text-center">Sign in to access your dashboard.</p>
            </div>
            
            <!-- Messages -->
            @if(session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-lime-100 dark:bg-green-900 text-green-700 dark:text-lime-300 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Content -->
            {{ $slot }}
            
            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {{ date('Y') }} Sagari Portal. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>