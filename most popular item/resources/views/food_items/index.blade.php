@extends('layouts.app')

@section('content')
<div class="mb-10 flex justify-between items-end border-b-4 border-black pb-6">
    <div>
        <h1 class="text-5xl font-black text-black tracking-tighter mb-2 uppercase">Active Listings Dashboard</h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-widest">Manage your daily surplus food items and inventory here.</p>
    </div>
    <a href="{{ route('food-items.create') }}" class="bg-black text-white px-8 py-4 rounded-none font-black text-xs hover:bg-white hover:text-black border-4 border-black transition-all shadow-[6px_6px_0_#999] active:shadow-none uppercase tracking-widest">
        + ADD NEW LISTING
    </a>
</div>

<!-- Stats Bar (3 Cards) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="border-4 border-black p-8 bg-white shadow-[8px_8px_0_#000]">
        <div class="text-5xl font-black text-black">{{ $stats['total'] }}</div>
        <div class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mt-2">Total Listings</div>
    </div>
    <div class="border-4 border-black p-8 bg-white shadow-[8px_8px_0_#000]">
        <div class="text-5xl font-black text-black">{{ $stats['in_stock'] }}</div>
        <div class="text-xs font-black text-gray-600 uppercase tracking-[0.2em] mt-2">Currently In Stock</div>
    </div>
    <div class="border-4 border-black p-8 bg-white shadow-[8px_8px_0_#000]">
        <div class="text-5xl font-black text-black">{{ $stats['out_of_stock'] }}</div>
        <div class="text-xs font-black text-red-500 uppercase tracking-[0.2em] mt-2">Out of Stock</div>
    </div>
</div>

@if($mostPopularItem && $mostPopularItem->reservations_count > 0)
<!-- Most Popular Item Highlighted Section -->
<div class="mb-12 border-4 border-black bg-yellow-300 p-8 shadow-[12px_12px_0_#000] flex flex-col md:flex-row justify-between items-center group hover:-translate-y-1 transition-transform">
    <div class="flex-1">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-black text-white px-3 py-1 text-[10px] font-black uppercase tracking-[0.3em]">Most Popular</span>
            <span class="text-black font-black text-xs uppercase tracking-widest">{{ $mostPopularItem->reservations_count }} Reservations</span>
        </div>
        <h2 class="text-6xl font-black text-black tracking-tighter uppercase mb-2">{{ $mostPopularItem->title }}</h2>
        <p class="text-black font-medium text-sm max-w-2xl">{{ \Illuminate\Support\Str::limit($mostPopularItem->description, 150) }}</p>
    </div>
    <div class="mt-8 md:mt-0 md:ml-10 text-right">
        <div class="text-gray-900 line-through text-sm font-black mb-1">৳{{ number_format($mostPopularItem->standard_price, 2) }}</div>
        <div class="text-5xl font-black text-black tracking-tighter mb-4">৳{{ number_format($mostPopularItem->discounted_price, 2) }}</div>
        <a href="{{ route('food-items.edit', $mostPopularItem->id) }}" class="inline-block bg-black text-white px-10 py-4 font-black text-xs uppercase tracking-widest hover:bg-white hover:text-black border-4 border-black transition-all">
            UPDATE STOCK
        </a>
    </div>
</div>
@endif

<!-- All Listings Table -->
<div class="border-4 border-black bg-white shadow-[10px_10px_0_#000] overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-black text-white uppercase text-[10px] font-black tracking-[0.3em]">
            <tr>
                <th class="px-8 py-5">Title</th>
                <th class="px-8 py-5">Price (৳)</th>
                <th class="px-8 py-5">Stock Status</th>
                <th class="px-8 py-5">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y-4 divide-black">
            @forelse($foodItems as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-8 py-6 font-black text-black uppercase text-sm tracking-tight">{{ $item->title }}</td>
                <td class="px-8 py-6">
                    <span class="text-gray-400 line-through text-[10px] mr-2">৳{{ number_format($item->standard_price, 2) }}</span>
                    <span class="font-black text-xl text-black tracking-tighter">৳{{ number_format($item->discounted_price, 2) }}</span>
                </td>
                <td class="px-8 py-6">
                    @if($item->stock > 0)
                        <span class="bg-black text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest border border-black">{{ $item->stock }} Left</span>
                    @else
                        <span class="bg-red-100 text-red-600 px-3 py-1 text-[10px] font-black uppercase tracking-widest border border-red-600">Out of Stock</span>
                    @endif
                </td>
                <td class="px-8 py-6 flex gap-4">
                    <a href="{{ route('food-items.edit', $item->id) }}" class="text-[10px] font-black underline hover:text-gray-500 uppercase tracking-widest">Edit</a>
                    <form action="{{ route('food-items.destroy', $item->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-[10px] font-black underline text-red-600 hover:text-red-400 uppercase tracking-widest" onclick="return confirm('Permanently delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-8 py-16 text-center text-gray-400 font-black italic uppercase tracking-widest">Your inventory is empty. Start by adding a food listing.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
