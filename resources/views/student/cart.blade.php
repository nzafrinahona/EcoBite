<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800">My Cart</h1>
    </x-slot>

    <div class="max-w-3xl">
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            @forelse($cart->items as $item)
                <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-50 last:border-0">
                    @if($item->foodItem->image)
                        <img src="{{ Storage::url($item->foodItem->image) }}" alt="{{ $item->foodItem->title }}" class="w-16 h-16 object-cover rounded-xl shrink-0">
                    @else
                        <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center text-2xl shrink-0">🍽️</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 truncate">{{ $item->foodItem->title }}</p>
                        <p class="text-xs text-slate-500">{{ $item->foodItem->cafeteria->name }}</p>
                        <p class="text-sm font-semibold text-green-600 mt-0.5">${{ number_format($item->foodItem->price, 2) }} each</p>
                    </div>
                    <form action="{{ route('student.cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                        @csrf @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->foodItem->quantity }}"
                               class="w-16 rounded-lg border-slate-200 text-sm text-center focus:ring-green-500 focus:border-green-500">
                        <button type="submit" class="text-xs font-medium text-green-600 hover:text-green-700">Update</button>
                    </form>
                    <div class="font-semibold text-slate-800 w-20 text-right text-sm">
                        ${{ number_format($item->subtotal, 2) }}
                    </div>
                    <form action="{{ route('student.cart.destroy', $item) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-slate-300 hover:text-red-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="py-16 text-center">
                    <div class="text-5xl mb-4">🛒</div>
                    <p class="text-slate-400 font-medium">Your cart is empty</p>
                    <a href="{{ route('food.index') }}" class="inline-block mt-4 px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition">
                        Browse Food
                    </a>
                </div>
            @endforelse

            @if($cart->items->count() > 0)
                <div class="px-6 py-5 bg-slate-50 rounded-b-2xl flex items-center justify-between gap-4">
                    <p class="text-lg font-bold text-slate-800">Total: <span class="text-green-600">${{ number_format($cart->total, 2) }}</span></p>
                    <div class="flex items-center gap-3">
                        <form action="{{ route('student.cart.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition">Clear Cart</button>
                        </form>
                        <a href="{{ route('food.index') }}" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
