<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800">My Reservations</h1>
    </x-slot>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Food Item</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Qty</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Total</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Pickup Code</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reservations as $reservation)
                        @php $s = $reservation->status; @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800 text-sm">{{ $reservation->foodItem->title }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $reservation->foodItem->cafeteria->name }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $reservation->quantity }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-800">${{ number_format($reservation->total_price, 2) }}</td>
                            <td class="px-4 py-4">
                                <code class="px-2.5 py-1 bg-slate-100 rounded-lg text-sm font-mono font-bold text-slate-700 tracking-widest">{{ $reservation->pickup_code }}</code>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                                    {{ $s==='pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $s==='completed' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                    {{ $s==='cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                    {{ $s==='no-show' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                    {{ ucfirst($s) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 space-x-2">
                                @if($reservation->isPending())
                                    <form action="{{ route('student.reservations.cancel', $reservation) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Cancel this reservation?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Cancel</button>
                                    </form>
                                @endif
                                @if($reservation->isCompleted() && !$reservation->review)
                                    <button onclick="document.getElementById('rf-{{ $reservation->id }}').classList.toggle('hidden')"
                                            class="text-xs font-medium text-blue-500 hover:text-blue-700">Review</button>
                                @endif
                                @if($reservation->review)
                                    <span class="text-xs text-amber-500">★ {{ $reservation->review->rating }}/5</span>
                                @endif
                            </td>
                        </tr>
                        @if($reservation->isCompleted() && !$reservation->review)
                            <tr id="rf-{{ $reservation->id }}" class="hidden">
                                <td colspan="6" class="px-6 py-4 bg-blue-50/50">
                                    <form action="{{ route('student.reviews.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
                                        @csrf
                                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-600 mb-1">Rating</label>
                                            <select name="rating" class="rounded-lg border-slate-200 text-sm" required>
                                                <option value="5">⭐⭐⭐⭐⭐</option>
                                                <option value="4">⭐⭐⭐⭐</option>
                                                <option value="3">⭐⭐⭐</option>
                                                <option value="2">⭐⭐</option>
                                                <option value="1">⭐</option>
                                            </select>
                                        </div>
                                        <div class="flex-1 min-w-48">
                                            <label class="block text-xs font-medium text-slate-600 mb-1">Comment (optional)</label>
                                            <input type="text" name="comment" class="w-full rounded-lg border-slate-200 text-sm" placeholder="How was the food?">
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                                            Submit
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <p class="text-slate-400 font-medium">No reservations yet.</p>
                                <a href="{{ route('food.index') }}" class="inline-block mt-3 text-sm font-medium text-green-600 hover:text-green-700">Browse food items →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservations->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $reservations->links() }}</div>
        @endif
    </div>
</x-app-layout>
