<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">
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
    <body class="font-sans antialiased bg-gray-100 body-margin-bottom">

        <div class="flex flex-col h-screen">

            <header class="bg-primary shadow">
                @include('layouts.navigation')
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto xl:px-28 px-3 lg:py-5 py-2">
                {{ $slot }}
            </main>

        </div>
        @stack('scripts')
    </body>
</html>
