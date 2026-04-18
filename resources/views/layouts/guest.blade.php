<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoBite') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
            <!-- Logo -->
            <a href="/" wire:navigate class="mb-8 flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:bg-green-700 transition">
                    <span class="text-2xl">🌿</span>
                </div>
                <div>
                    <span class="text-2xl font-bold text-green-700">EcoBite</span>
                    <p class="text-xs text-gray-500 leading-none">Campus Surplus Food Saver</p>
                </div>
            </a>

            <!-- Card -->
            <div class="w-full max-w-md">
                <div class="bg-white rounded-2xl shadow-xl shadow-green-100/50 border border-slate-100 px-8 py-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
