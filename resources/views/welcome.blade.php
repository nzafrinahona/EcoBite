<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoBite — Campus Surplus Food Saver</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-slate-50">
    {{-- Navigation --}}
    <header class="w-full flex items-center justify-between px-6 lg:px-12 py-4 bg-white border-b border-slate-100">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h14a1 1 0 001-1V5a1 1 0 00-1-1H3zm0 2h14v2H3V6zm0 4h6v4H3v-4zm8 0h6v4h-6v-4z"/></svg>
            </div>
            <span class="text-xl font-bold text-slate-800">EcoBite</span>
        </div>
        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-green-700 hover:text-green-800">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">Sign up</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    {{-- Hero --}}
    <section class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6 py-20">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-green-50 border border-green-100 rounded-full text-xs font-semibold text-green-700 mb-8">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            Reducing campus food waste
        </div>
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-tight max-w-3xl">
            Save Food.<br><span class="text-green-600">Save Money.</span><br>Save the Planet.
        </h1>
        <p class="mt-6 text-lg text-slate-500 max-w-xl">
            EcoBite connects campus cafeterias with students to rescue surplus food at discounted prices. Reduce waste, eat well, and make a difference.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('food.index') }}" class="px-8 py-3.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition text-lg shadow-sm">
                Browse Food
            </a>
            @guest
                <a href="{{ route('register') }}" class="px-8 py-3.5 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:border-slate-300 hover:bg-white transition text-lg">
                    Get Started
                </a>
            @endguest
        </div>
    </section>

    {{-- Features --}}
    <section class="px-6 pb-24">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-7 border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl mb-4">🍱</div>
                <h3 class="font-bold text-slate-800 text-lg">Surplus Food Feed</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Browse real-time listings of discounted surplus food from campus cafeterias before it goes to waste.</p>
            </div>
            <div class="bg-white rounded-2xl p-7 border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-2xl mb-4">🔑</div>
                <h3 class="font-bold text-slate-800 text-lg">Easy Pickup</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Reserve food and get a unique 5-digit pickup code. Show it at the counter to collect your order.</p>
            </div>
            <div class="bg-white rounded-2xl p-7 border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center text-2xl mb-4">📊</div>
                <h3 class="font-bold text-slate-800 text-lg">Analytics</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Cafeteria staff get weekly analytics on food saved, revenue earned, and the most popular items.</p>
            </div>
        </div>
    </section>

    <footer class="py-8 text-center text-sm text-slate-400 border-t border-slate-100">
        EcoBite &copy; {{ date('Y') }} — Campus Surplus Food Saver
    </footer>
</body>
</html>
