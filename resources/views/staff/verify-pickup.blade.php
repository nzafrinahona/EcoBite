<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800">Verify Pickup</h1>
        <p class="text-sm text-slate-500 mt-0.5">Enter a student's pickup code to confirm their order</p>
    </x-slot>

    <div class="max-w-lg">
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
            <form action="{{ route('staff.verify-pickup') }}" method="POST">
                @csrf
                <label for="pickup_code" class="block text-sm font-medium text-slate-700 mb-2">Enter 5-digit Pickup Code</label>
                <div class="flex gap-3">
                    <input id="pickup_code" name="pickup_code" type="text" maxlength="5" placeholder="A3B2C"
                           class="flex-1 rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-3xl font-mono font-bold text-center tracking-[0.4em] uppercase py-3 shadow-sm"
                           required autofocus>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
                        Verify
                    </button>
                </div>
                <x-input-error :messages="$errors->get('pickup_code')" class="mt-2" />
            </form>
        </div>

        @if(isset($reservation))
            <div class="mt-5 bg-white rounded-2xl border border-green-200 shadow-sm p-8">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <h2 class="font-semibold text-green-700">Reservation Found</h2>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs text-slate-400 mb-1">Student</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $reservation->student->user->name }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs text-slate-400 mb-1">Food Item</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $reservation->foodItem->title }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs text-slate-400 mb-1">Quantity</p>
                        <p class="font-semibold text-slate-800">{{ $reservation->quantity }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs text-slate-400 mb-1">Total Price</p>
                        <p class="font-semibold text-slate-800">${{ number_format($reservation->total_price, 2) }}</p>
                    </div>
                </div>
                <form action="{{ route('staff.reservations.confirm-pickup', $reservation) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
                        Confirm Pickup &mdash; Complete Reservation
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
