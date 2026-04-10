<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Staff Dashboard</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $cafeteria->name }}</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Items</p>
            <p class="text-4xl font-bold text-slate-800 mt-2">{{ $totalItems }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Active Items</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ $activeItems }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pending Pickups</p>
            <p class="text-4xl font-bold text-amber-500 mt-2">{{ $pendingReservations }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Completed Today</p>
            <p class="text-4xl font-bold text-blue-600 mt-2">{{ $completedToday }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('staff.food-items.create') }}" class="group flex items-center gap-3 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-green-200 transition">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-xl group-hover:bg-green-100 transition">➕</div>
            <p class="font-semibold text-slate-800 text-sm">Add Food Item</p>
        </a>
        <a href="{{ route('staff.food-items.index') }}" class="group flex items-center gap-3 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xl group-hover:bg-blue-100 transition">📋</div>
            <p class="font-semibold text-slate-800 text-sm">Manage Items</p>
        </a>
        <a href="{{ route('staff.verify-pickup') }}" class="group flex items-center gap-3 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-amber-200 transition">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-xl group-hover:bg-amber-100 transition">✅</div>
            <p class="font-semibold text-slate-800 text-sm">Verify Pickup</p>
        </a>
        <a href="{{ route('staff.analytics.index') }}" class="group flex items-center gap-3 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-violet-200 transition">
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-xl group-hover:bg-violet-100 transition">📊</div>
            <p class="font-semibold text-slate-800 text-sm">Analytics</p>
        </a>
    </div>

    {{-- Weekly Summary --}}
    @if($analytics)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
        <h2 class="font-semibold text-slate-800 mb-4">This Week's Summary</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <p class="text-xs text-slate-400">Revenue</p>
                <p class="text-xl font-bold text-green-600 mt-1">${{ number_format($analytics->total_revenue, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Reservations</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $analytics->total_reservations }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Pickups</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $analytics->completed_pickups }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Popular Item</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $analytics->most_popular_item ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Reservations --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Recent Reservations</h2>
            <a href="{{ route('staff.reservations.index') }}" class="text-xs font-medium text-green-600 hover:text-green-700">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-50">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Student</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentReservations as $reservation)
                        @php $s = $reservation->status; @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3 text-sm text-slate-800">{{ $reservation->student->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $reservation->foodItem->title }}</td>
                            <td class="px-4 py-3"><code class="bg-slate-100 px-2 py-0.5 rounded-lg text-xs font-mono font-bold text-slate-700 tracking-widest">{{ $reservation->pickup_code }}</code></td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                                    {{ $s==='pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $s==='completed' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                    {{ $s==='cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}">
                                    {{ ucfirst($s) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $reservation->created_at->format('M d, g:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400 text-sm">No reservations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
