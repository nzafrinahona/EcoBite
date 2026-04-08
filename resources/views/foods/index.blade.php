<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Food Feed</h1>
                <p class="text-sm text-slate-500 mt-0.5">Discounted surplus food from campus cafeterias</p>
            </div>
            @auth
                @if(auth()->user()->isStaff())
                    <a href="{{ route('staff.food-items.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Food Item
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($foodItems as $foodItem)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition group">
                {{-- Image --}}
                <div class="relative">
                    @if($foodItem->image)
                        <img src="{{ Storage::url($foodItem->image) }}" alt="{{ $foodItem->title }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                            <span class="text-5xl">🍱</span>
                        </div>
                    @endif
                    @if($foodItem->original_price)
                        @php $discount = round((1 - $foodItem->price / $foodItem->original_price) * 100); @endphp
                        <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                            {{ $discount }}% OFF
                        </span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-800 truncate">{{ $foodItem->title }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $foodItem->cafeteria->name }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-lg font-bold text-green-600">${{ number_format($foodItem->price, 2) }}</div>
                            @if($foodItem->original_price)
                                <div class="text-xs text-slate-400 line-through">${{ number_format($foodItem->original_price, 2) }}</div>
                            @endif
                        </div>
                    </div>

                    @if($foodItem->description)
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ $foodItem->description }}</p>
                    @endif

                    <div class="flex items-center justify-between mt-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                            {{ $foodItem->quantity }} left
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $foodItem->expiry_time->format('M d, g:i A') }}
                        </span>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('food.show', $foodItem) }}"
                           class="flex-1 text-center py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                            View
                        </a>
                        @auth
                            @if(auth()->user()->isStudent())
                                <form action="{{ route('student.cart.store') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                            class="w-full py-2 text-sm font-semibold text-white bg-green-600 rounded-xl hover:bg-green-700 transition">
                                        Add to Cart
                                    </button>
                                </form>
                            @endif
                            @if(auth()->user()->isStaff())
                                <a href="{{ route('staff.food-items.edit', $foodItem) }}"
                                   class="px-3 py-2 text-sm font-medium text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition">
                                    Edit
                                </a>
                                <form action="{{ route('staff.food-items.destroy', $foodItem) }}" method="POST"
                                      onsubmit="return confirm('Delete this item?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-2 text-sm font-medium text-white bg-red-500 rounded-xl hover:bg-red-600 transition">
                                        Del
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center">
                <div class="text-6xl mb-4">🍽️</div>
                <h3 class="text-lg font-semibold text-slate-700">No food available right now</h3>
                <p class="text-slate-500 text-sm mt-1">Check back soon for fresh surplus deals!</p>
            </div>
        @endforelse
    </div>

    @if($foodItems->hasPages())
        <div class="mt-8">{{ $foodItems->links() }}</div>
    @endif
</x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            @auth
                @if(auth()->user()->isStaff())
                    <div class="mb-6">
                        <a href="{{ route('staff.food-items.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            + Add Food Item
                        </a>
                    </div>
                @endif
            @endauth

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($foodItems as $foodItem)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        @if($foodItem->image)
                            <img src="{{ Storage::url($foodItem->image) }}" alt="{{ $foodItem->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $foodItem->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $foodItem->cafeteria->name }}</p>
                            @if($foodItem->description)
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-2">{{ Str::limit($foodItem->description, 80) }}</p>
                            @endif

                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <span class="text-xl font-bold text-green-600">${{ number_format($foodItem->price, 2) }}</span>
                                    @if($foodItem->original_price)
                                        <span class="text-sm text-gray-400 line-through ml-2">${{ number_format($foodItem->original_price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">{{ $foodItem->quantity }} left</span>
                            </div>

                            <p class="text-xs text-gray-400 mt-2">Expires: {{ $foodItem->expiry_time->format('M d, g:i A') }}</p>

                            <div class="mt-4 flex space-x-2">
                                <a href="{{ route('food.show', $foodItem) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">View</a>

                                @auth
                                    @if(auth()->user()->isStudent())
                                        <form action="{{ route('student.cart.store') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">Add to Cart</button>
                                        </form>
                                    @endif

                                    @if(auth()->user()->isStaff())
                                        <a href="{{ route('staff.food-items.edit', $foodItem) }}" class="px-3 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition">Edit</a>
                                        <form action="{{ route('staff.food-items.destroy', $foodItem) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">Delete</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        No food items available right now. Check back soon!
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $foodItems->links() }}
            </div>
        </div>
    </div>
</x-app-layout>