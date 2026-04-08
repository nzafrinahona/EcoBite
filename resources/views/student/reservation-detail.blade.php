<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reservations.index') }}" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Reservation Details</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $reservation->foodItem->cafeteria->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
            <h2 class="text-xl font-bold text-slate-800">{{ $reservation->foodItem->title }}</h2>

            {{-- Pickup code spotlight --}}
            <div class="mt-6 p-6 bg-green-50 border border-green-100 rounded-2xl text-center">
                <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-2">Your Pickup Code</p>
                <p class="font-mono text-5xl font-bold text-green-700 tracking-[0.3em]">{{ $reservation->pickup_code }}</p>
                <p class="text-xs text-green-600 mt-2">Show this code at the cafeteria counter</p>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Quantity</p>
                    <p class="font-semibold text-slate-800">{{ $reservation->quantity }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Total Price</p>
                    <p class="font-semibold text-slate-800">${{ number_format($reservation->total_price, 2) }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 col-span-2">
                    <p class="text-xs text-slate-400 mb-1">Status</p>
                    @php $s = $reservation->status; @endphp
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                        {{ $s==='pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                        {{ $s==='completed' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                        {{ $s==='cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                        {{ $s==='no-show' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                        {{ ucfirst($s) }}
                    </span>
                </div>
            </div>

            @if($reservation->pickup_time)
                <p class="mt-4 text-sm text-slate-500">Picked up: {{ $reservation->pickup_time->format('M d, Y g:i A') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>
