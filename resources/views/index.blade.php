@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end border-b-4 border-black pb-8 gap-4">
    <div>
        <h1 class="text-6xl font-black text-black tracking-tighter mb-2 uppercase">Your Cart</h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-[0.2em]">
            Review your items before reserving
        </p>
    </div>

    @if($cartItems->isNotEmpty())
        <form action="{{ route('cart.clear') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                onclick="return confirm('Clear your entire cart?')"
                class="bg-white text-black px-5 py-3 border-4 border-black font-black text-[10px] uppercase tracking-[0.2em] shadow-[4px_4px_0_#999] hover:bg-black hover:text-white transition-all">
                CLEAR CART
            </button>
        </form>
    @endif
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="mb-6 border-4 border-black bg-green-100 px-6 py-4 font-bold text-sm uppercase tracking-widest text-green-800">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 border-4 border-black bg-red-100 px-6 py-4 font-bold text-sm uppercase tracking-widest text-red-800">
        {{ session('error') }}
    </div>
@endif

{{-- Empty cart --}}
@if($cartItems->isEmpty())
    <div class="border-8 border-dashed border-black p-24 text-center bg-gray-50">
        <div class="text-8xl mb-6">🛒</div>
        <h3 class="text-2xl font-black text-black uppercase tracking-widest italic">Your cart is empty!</h3>
        <p class="mt-4 text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">
            Go browse the surplus feed and add some items.
        </p>
        <a href="{{ route('student-feed') }}"
            class="bg-black text-white px-8 py-4 font-black text-[10px] uppercase tracking-[0.2em] shadow-[4px_4px_0_#999] hover:bg-white hover:text-black border-4 border-black transition-all inline-block">
            BROWSE FOOD
        </a>
    </div>

@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Cart items list --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
                <div class="border-4 border-black bg-white shadow-[6px_6px_0_#000] flex gap-4 p-4">

                    {{-- Food image --}}
                    <div class="w-24 h-24 bg-gray-100 border-2 border-black flex items-center justify-center overflow-hidden shrink-0">
                        @if($item->food->photo)
                            <img src="{{ asset('storage/' . $item->food->photo) }}"
                                 alt="{{ $item->food->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">🍱</span>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-black text-black uppercase tracking-tighter text-lg leading-tight">
                            {{ $item->food->title }}
                        </h3>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">
                            Stock left: {{ $item->food->stock }}
                        </p>

                        <div class="flex items-center gap-4 mt-3">
                            <div>
                                <span class="text-gray-400 line-through text-[10px] font-bold italic block">
                                    ৳{{ number_format($item->food->standard_price, 2) }}
                                </span>
                                <span class="font-black text-xl text-black">
                                    ৳{{ number_format($item->food->discounted_price, 2) }}
                                </span>
                            </div>
                            <span class="text-gray-400 font-black text-sm">× {{ $item->quantity }}</span>
                            <span class="font-black text-lg text-black border-l-4 border-black pl-4">
                                ৳{{ number_format($item->quantity * $item->food->discounted_price, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Remove button --}}
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-white border-2 border-black text-black font-black text-xs px-3 py-2 hover:bg-black hover:text-white transition-all uppercase tracking-widest">
                            ✕
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-1">
            <div class="border-4 border-black bg-white shadow-[6px_6px_0_#000] p-6 sticky top-4">
                <h2 class="font-black text-black uppercase tracking-tighter text-2xl mb-6 border-b-4 border-black pb-4">
                    Order Summary
                </h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm font-bold uppercase tracking-widest text-gray-500">
                        <span>Items ({{ $cartItems->count() }})</span>
                        <span>৳{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold uppercase tracking-widest text-gray-500">
                        <span>Discount</span>
                        <span class="text-green-600">Included</span>
                    </div>
                </div>

                <div class="border-t-4 border-black pt-4 flex justify-between items-center mb-8">
                    <span class="font-black text-black uppercase tracking-tighter text-xl">Total</span>
                    <span class="font-black text-3xl text-black tracking-tighter">
                        ৳{{ number_format($total, 2) }}
                    </span>
                </div>

                {{-- Confirm Reservation button (FR-12 - Sprint 3) --}}
                <button
                    class="w-full bg-black text-white py-5 font-black text-[10px] uppercase tracking-[0.2em] shadow-[4px_4px_0_#999] hover:bg-white hover:text-black border-4 border-black transition-all active:translate-x-1 active:translate-y-1 active:shadow-none">
                    CONFIRM RESERVATION
                </button>

                <a href="{{ route('student-feed') }}"
                    class="block text-center mt-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-black transition-colors">
                    ← Continue Browsing
                </a>
            </div>
        </div>

    </div>
@endif

@endsection