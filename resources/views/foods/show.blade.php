<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('food.index') }}" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $foodItem->title }}</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $foodItem->cafeteria->name }}</p>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="md:flex">
                {{-- Image --}}
                <div class="md:w-2/5 shrink-0">
                    @if($foodItem->image)
                        <img src="{{ Storage::url($foodItem->image) }}" alt="{{ $foodItem->title }}"
                             class="w-full h-64 md:h-full object-cover">
                    @else
                        <div class="w-full h-64 md:h-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                            <span class="text-7xl">🍽️</span>
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="p-8 flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-2xl font-bold text-slate-800">{{ $foodItem->title }}</h2>
                        <span class="shrink-0 px-3 py-1 text-xs font-semibold rounded-full
                            {{ $foodItem->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $foodItem->is_active ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <p class="text-slate-500 mt-1 text-sm">
                        {{ $foodItem->cafeteria->name }}
                        @if($foodItem->cafeteria->location)
                            &bull; {{ $foodItem->cafeteria->location }}
                        @endif
                    </p>

                    @if($foodItem->description)
                        <p class="text-slate-600 mt-4 leading-relaxed">{{ $foodItem->description }}</p>
                    @endif

                    {{-- Stats Grid --}}
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <div class="text-xs text-slate-500 mb-1">Discounted Price</div>
                            <div class="text-2xl font-bold text-green-600">${{ number_format($foodItem->price, 2) }}</div>
                            @if($foodItem->original_price)
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-slate-400 line-through">${{ number_format($foodItem->original_price, 2) }}</span>
                                    <span class="text-xs font-semibold text-red-500 bg-red-50 px-1.5 py-0.5 rounded">
                                        {{ round((1 - $foodItem->price / $foodItem->original_price) * 100) }}% off
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <div class="text-xs text-slate-500 mb-1">Quantity Available</div>
                            <div class="text-2xl font-bold text-slate-800">{{ $foodItem->quantity }}</div>
                        </div>
                        <div class="col-span-2 bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <div class="text-xs text-amber-600 mb-1 font-medium">⏰ Expires</div>
                            <div class="text-slate-800 font-semibold">{{ $foodItem->expiry_time->format('l, M d Y \a\t g:i A') }}</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    @auth
                        @if(auth()->user()->isStudent() && $foodItem->is_active && $foodItem->quantity > 0)
                            <div class="mt-6 flex flex-wrap gap-3">
                                <form action="{{ route('student.cart.store') }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $foodItem->quantity }}"
                                           class="w-20 rounded-xl border-slate-200 text-sm text-center focus:ring-green-500 focus:border-green-500">
                                    <button type="submit"
                                            class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
                                        Add to Cart
                                    </button>
                                </form>
                                <form action="{{ route('student.reservations.store') }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                            class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                                        Reserve Now
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="mt-6 p-4 bg-slate-50 rounded-xl text-center text-sm text-slate-500">
                            <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">Log in</a> to reserve or add to cart.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
