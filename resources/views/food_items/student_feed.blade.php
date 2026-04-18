@extends('layouts.app')

@section('content')

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

{{-- Header --}}
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end border-b-4 border-black pb-8 gap-4">
    <div>
        <h1 class="text-6xl font-black text-black tracking-tighter mb-2 uppercase">SURPLUS FEED</h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-[0.2em]">
            Scoop up local cafeteria leftovers before they're gone!
        </p>
    </div>

    {{-- Cart button --}}
    @auth
    @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity'); @endphp
    <a href="{{ route('cart.index') }}"
        class="flex items-center gap-2 bg-black text-white px-5 py-3 border-4 border-black font-black text-[10px] uppercase tracking-[0.2em] shadow-[4px_4px_0_#999] hover:bg-white hover:text-black transition-all">
        🛒 CART
        @if($cartCount > 0)
            <span class="bg-white text-black text-[10px] font-black px-2 py-0.5 rounded-full">
                {{ $cartCount }}
            </span>
        @endif
    </a>
    @endauth
</div>

{{-- Filter & Sort Bar --}}
<div class="flex flex-wrap gap-4 mb-8 items-center">

    {{-- FR-12: Filter by Category --}}
    <form action="{{ route('student-feed') }}" method="GET" class="flex items-center gap-3 bg-white border-4 border-black p-3 shadow-[4px_4px_0_#999]">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Category:</span>
        <select name="category" onchange="this.form.submit()"
            class="text-[10px] font-black uppercase tracking-widest bg-transparent cursor-pointer focus:outline-none focus:ring-2 focus:ring-black">
            <option value="">ALL ITEMS</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>
                    {{ strtoupper($cat) }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- FR-05: Sort by Price --}}
    <form action="{{ route('student-feed') }}" method="GET" class="flex items-center gap-3 bg-white border-4 border-black p-3 shadow-[4px_4px_0_#999]">
        <input type="hidden" name="category" value="{{ $category }}">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Sort:</span>
        <select name="sort" onchange="this.form.submit()"
            class="text-[10px] font-black uppercase tracking-widest bg-transparent cursor-pointer focus:outline-none focus:ring-2 focus:ring-black">
            <option value="asc"  {{ $sort === 'asc'  ? 'selected' : '' }}>Price: Low to High</option>
            <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Price: High to Low</option>
        </select>
    </form>

    {{-- Clear filters button --}}
    @if(!empty($category))
        <a href="{{ route('student-feed', ['sort' => $sort]) }}"
            class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-black border-4 border-gray-300 hover:border-black px-4 py-3 transition-all">
            ✕ CLEAR FILTER
        </a>
    @endif

</div>

{{-- Active filter tag --}}
@if(!empty($category))
    <div class="mb-6 inline-flex items-center gap-3 bg-black text-white px-5 py-2 text-[10px] font-black uppercase tracking-widest">
        <span>Showing: {{ strtoupper($category) }}</span>
        <a href="{{ route('student-feed', ['sort' => $sort]) }}" class="hover:text-gray-300">✕</a>
    </div>
@endif

{{-- Results count --}}
<p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">
    {{ $foodItems->count() }} item(s) found
    @if(!empty($category)) — <span class="text-black">{{ strtoupper($category) }}</span> @endif
    — sorted by price <span class="text-black">{{ $sort === 'asc' ? '↑ Low to High' : '↓ High to Low' }}</span>
</p>

{{-- Empty state --}}
@if($foodItems->isEmpty())
    <div class="border-8 border-dashed border-black p-24 text-center bg-gray-50">
        <div class="text-8xl mb-6">🏜️</div>
        <h3 class="text-2xl font-black text-black uppercase tracking-widest italic">
            @if(!empty($category))
                No {{ $category }} items available!
            @else
                Inventory Bone Dry!
            @endif
        </h3>
        <p class="mt-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
            @if(!empty($category))
                Try a different category or check back later.
            @else
                Check back in a bit — more food coming soon.
            @endif
        </p>
        @if(!empty($category))
            <a href="{{ route('student-feed') }}"
                class="mt-8 inline-block bg-black text-white px-8 py-4 font-black text-[10px] uppercase tracking-[0.2em] border-4 border-black hover:bg-white hover:text-black transition-all">
                VIEW ALL ITEMS
            </a>
        @endif
    </div>

@else
    {{-- Food grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @foreach($foodItems as $item)
            <div class="border-4 border-black bg-white group shadow-[8px_8px_0_#000] hover:-translate-y-2 transition-all duration-300">

                {{-- Image --}}
                <div class="h-56 bg-gray-100 flex items-center justify-center border-b-4 border-black overflow-hidden relative">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}"
                             alt="{{ $item->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <span class="text-7xl group-hover:rotate-12 transition-transform duration-300">🍱</span>
                    @endif

                    {{-- Stock badge --}}
                    <div class="absolute bottom-4 right-4 bg-black text-white px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] animate-pulse">
                        {{ $item->stock }} Left
                    </div>

                    {{-- Discount badge --}}
                    @php
                        $discount = $item->standard_price > 0
                            ? round((1 - $item->discounted_price / $item->standard_price) * 100)
                            : 0;
                    @endphp
                    @if($discount > 0)
                        <div class="absolute top-4 left-4 bg-white text-black px-2 py-1 text-[10px] font-black uppercase border-2 border-black">
                            -{{ $discount }}% OFF
                        </div>
                    @endif

                    {{-- FR-12: Category tag --}}
                    <div class="absolute top-4 right-4 bg-black text-white px-2 py-1 text-[9px] font-black uppercase tracking-widest">
                        {{ $item->category }}
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-6">
                    <h3 class="text-xl font-black text-black mb-1 uppercase tracking-tighter">
                        {{ $item->title }}
                    </h3>
                    <p class="text-gray-400 text-xs font-medium mb-3 line-clamp-2 uppercase tracking-widest">
                        {{ $item->description }}
                    </p>

                    {{-- FR-13: Total Orders Count --}}
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                            🛒 Reserved
                        </span>
                        <span class="bg-black text-white text-[10px] font-black px-2 py-0.5 uppercase tracking-widest">
                            {{ $item->total_orders ?? 0 }} times
                        </span>
                    </div>

                    <div class="pt-4 border-t-2 border-black">
                        <div class="flex items-end justify-between mb-4">
                            <div>
                                <span class="text-gray-400 line-through text-[10px] block font-bold mb-1 italic">
                                    ৳{{ number_format($item->standard_price, 2) }}
                                </span>
                                <span class="font-black text-3xl text-black tracking-tighter">
                                    ৳{{ number_format($item->discounted_price, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Add to cart --}}
                        @auth
                        <form action="{{ route('cart.add', $item->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $item->stock }}"
                                class="w-16 border-4 border-black text-center font-black text-sm focus:outline-none py-2">
                            <button type="submit"
                                class="flex-1 bg-black text-white py-3 font-black text-[10px] uppercase tracking-[0.2em] shadow-[4px_4px_0_#999] hover:bg-white hover:text-black border-4 border-black transition-all active:translate-x-1 active:translate-y-1 active:shadow-none">
                                ADD TO CART
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}"
                            class="block text-center bg-black text-white py-3 font-black text-[10px] uppercase tracking-[0.2em] border-4 border-black hover:bg-white hover:text-black transition-all">
                            LOGIN TO RESERVE
                        </a>
                        @endauth
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endif

@endsection

