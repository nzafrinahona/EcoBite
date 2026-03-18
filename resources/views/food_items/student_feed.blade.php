@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end border-b-4 border-black pb-8 gap-4">
    <div>
        <h1 class="text-6xl font-black text-black tracking-tighter mb-2 uppercase">SURPLUS FEED</h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-[0.2em]">
            Scoop up local cafeteria leftovers before they're gone!
        </p>
    </div>

    {{-- FR-10: Sort by price --}}
    <div class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[6px_6px_0_#999]">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Sort by:</span>
        <form action="{{ route('student-feed') }}" method="GET" class="flex gap-2">
            <select
                name="sort"
                onchange="this.form.submit()"
                class="text-[10px] font-black uppercase tracking-widest bg-transparent cursor-pointer focus:outline-none focus:ring-2 focus:ring-black"
            >
                <option value="asc"  {{ ($sort ?? 'asc') === 'asc'  ? 'selected' : '' }}>
                    Price: Low to High
                </option>
                <option value="desc" {{ ($sort ?? 'asc') === 'desc' ? 'selected' : '' }}>
                    Price: High to Low
                </option>
            </select>
        </form>
    </div>
</div>

{{-- Empty state --}}
@if($foodItems->isEmpty())
    <div class="border-8 border-dashed border-black p-24 text-center bg-gray-50">
        <div class="text-8xl mb-6">🏜️</div>
        <h3 class="text-2xl font-black text-black uppercase tracking-widest italic">Inventory Bone Dry!</h3>
        <p class="mt-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
            Check back in a bit — more food coming soon.
        </p>
    </div>

@else
    {{-- Active sort indicator --}}
    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">
        Showing {{ $foodItems->count() }} item(s) —
        sorted by price
        <span class="text-black">{{ ($sort ?? 'asc') === 'asc' ? '↑ Low to High' : '↓ High to Low' }}</span>
    </p>

    {{-- Food grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @foreach($foodItems as $item)
            <div class="border-4 border-black bg-white group shadow-[8px_8px_0_#000] hover:-translate-y-2 transition-all duration-300">

                {{-- Image --}}
                <div class="h-56 bg-gray-100 flex items-center justify-center border-b-4 border-black overflow-hidden relative">
                    @if($item->photo)
                        <img
                            src="{{ asset('storage/' . $item->photo) }}"
                            alt="{{ $item->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        >
                    @else
                        <span class="text-7xl group-hover:rotate-12 transition-transform duration-300">🍱</span>
                    @endif

                    {{-- Stock badge --}}
                    <div class="absolute bottom-4 right-4 bg-black text-white px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] animate-pulse">
                        {{ $item->stock }} Left
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-8">
                    <h3 class="text-xl font-black text-black mb-1 uppercase tracking-tighter">
                        {{ $item->title }}
                    </h3>
                    <p class="text-gray-400 text-xs font-medium mb-6 line-clamp-2 h-8 uppercase tracking-widest">
                        {{ $item->description }}
                    </p>

                    <div class="pt-6 border-t-2 border-black flex items-center justify-between">
                        <div>
                            <span class="text-gray-400 line-through text-[10px] block font-bold mb-1 italic">
                                ৳{{ number_format($item->standard_price, 2) }}
                            </span>
                            <span class="font-black text-3xl text-black tracking-tighter">
                                ৳{{ number_format($item->discounted_price, 2) }}
                            </span>

                            {{-- Discount % badge --}}
                            @php
                                $discount = $item->standard_price > 0
                                    ? round((1 - $item->discounted_price / $item->standard_price) * 100)
                                    : 0;
                            @endphp
                            @if($discount > 0)
                                <span class="inline-block mt-1 bg-black text-white text-[9px] font-black uppercase px-2 py-0.5 tracking-widest">
                                    -{{ $discount }}% OFF
                                </span>
                            @endif
                        </div>

                        <button
                            class="bg-black text-white px-6 py-4 rounded-none font-black text-[10px] uppercase tracking-[0.2em] shadow-[4px_4px_0_#999] hover:bg-white hover:text-black border-4 border-black transition-all active:translate-x-1 active:translate-y-1 active:shadow-none"
                        >
                            RESERVE
                        </button>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endif

@endsection
