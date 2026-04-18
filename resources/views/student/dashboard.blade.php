<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-sm text-slate-500 mt-0.5">Here's what's happening with your EcoBite account</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Available Items</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ $availableItems }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Active Reservations</p>
            <p class="text-4xl font-bold text-blue-600 mt-2">{{ $activeReservations }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Completed Pickups</p>
            <p class="text-4xl font-bold text-violet-600 mt-2">{{ $completedReservations }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('food.index') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-green-200 transition">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl group-hover:bg-green-100 transition">🍱</div>
            <div>
                <p class="font-semibold text-slate-800">Browse Food</p>
                <p class="text-xs text-slate-500">Find surplus deals</p>
            </div>
        </a>
        <a href="{{ route('student.cart.index') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl group-hover:bg-blue-100 transition">🛒</div>
            <div>
                <p class="font-semibold text-slate-800">My Cart</p>
                <p class="text-xs text-slate-500">View cart items</p>
            </div>
        </a>
        <a href="{{ route('student.reservations.index') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-violet-200 transition">
            <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center text-2xl group-hover:bg-violet-100 transition">📋</div>
            <div>
                <p class="font-semibold text-slate-800">Reservations</p>
                <p class="text-xs text-slate-500">Track your orders</p>
            </div>
        </a>
    </div>

    {{-- Recent Reservations --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Recent Reservations</h2>
            <a href="{{ route('student.reservations.index') }}" class="text-xs font-medium text-green-600 hover:text-green-700">View all →</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentReservations as $reservation)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="font-medium text-slate-800 text-sm">{{ $reservation->foodItem->title }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $reservation->foodItem->cafeteria->name }} &bull; Qty {{ $reservation->quantity }}</p>
                    </div>
                    <div class="text-right flex flex-col items-end gap-1">
                        @php $s = $reservation->status; @endphp
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                            {{ $s==='pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                            {{ $s==='completed' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                            {{ $s==='cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                            {{ $s==='no-show' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                            {{ ucfirst($s) }}
                        </span>
                        <span class="text-xs text-slate-500">${{ number_format($reservation->total_price, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-slate-400 text-sm">No reservations yet.</p>
                    <a href="{{ route('food.index') }}" class="inline-block mt-3 text-sm font-medium text-green-600 hover:text-green-700">Browse food items →</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
