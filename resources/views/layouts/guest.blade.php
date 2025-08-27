<!-- filepath: d:\project\client\Sagari\sagari-portal\resources\views\layouts\guest.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-indigo-200 via-blue-100 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md px-6 py-8 bg-white/80 dark:bg-gray-900/80 shadow-2xl rounded-2xl backdrop-blur-md border border-gray-200 dark:border-gray-800">
            <div class="flex flex-col items-center gap-4 mb-6">
                <a href="/">
                    <img src="{{ asset('images/logo_sagari.svg') }}" alt="Sagari Logo" class="w-20 h-20 drop-shadow-lg" />
                </a>
                <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-200">Welcome to Sagari Portal</h2>
                <p class="text-gray-500 dark:text-gray-400 text-center">Sign in to access your dashboard.</p>
            </div>
            @if(session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded">{{ session('success') }}</div>
            @endif
            {{ $slot }}
            <div class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {{ date('Y') }} Sagari Portal. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>