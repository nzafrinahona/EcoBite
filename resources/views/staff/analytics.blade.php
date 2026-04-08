<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Analytics</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $cafeteria->name }}</p>
        </div>
    </x-slot>

    {{-- Current Week Summary --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
        <h2 class="font-semibold text-slate-800 mb-5">This Week's Summary</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-5">
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-400">Total Listings</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $currentWeek->total_listings }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4">
                <p class="text-xs text-blue-400">Reservations</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $currentWeek->total_reservations }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4">
                <p class="text-xs text-green-400">Completed Pickups</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ $currentWeek->completed_pickups }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4">
                <p class="text-xs text-green-400">Revenue</p>
                <p class="text-2xl font-bold text-green-700 mt-1">${{ number_format($currentWeek->total_revenue, 2) }}</p>
            </div>
            <div class="bg-violet-50 rounded-xl p-4">
                <p class="text-xs text-violet-400">Most Popular</p>
                <p class="text-sm font-bold text-violet-700 mt-1 leading-tight">{{ $currentWeek->most_popular_item ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Historical Data --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Weekly History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Week</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Listings</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Reservations</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Pickups</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Revenue</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Top Item</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($analytics as $record)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                                {{ $record->week_start->format('M d') }} &ndash; {{ $record->week_end->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $record->total_listings }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $record->total_reservations }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $record->completed_pickups }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-green-700">${{ number_format($record->total_revenue, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $record->most_popular_item ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center text-slate-400 text-sm">No historical data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($analytics->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $analytics->links() }}</div>
        @endif
    </div>
</x-app-layout>
