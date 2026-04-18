<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">Notifications</h1>
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-medium text-green-600 hover:text-green-700 transition">Mark all as read</button>
            </form>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl space-y-2">
        @forelse($notifications as $notification)
            <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border shadow-sm transition
                        {{ !$notification->is_read ? 'border-blue-200 bg-blue-50/30' : 'border-slate-100' }}">
                <div class="mt-0.5 w-2 h-2 rounded-full shrink-0 mt-2
                            {{ !$notification->is_read ? 'bg-blue-500' : 'bg-slate-200' }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm">{{ $notification->title }}</p>
                    <p class="text-sm text-slate-600 mt-0.5">{{ $notification->message }}</p>
                    <p class="text-xs text-slate-400 mt-1.5">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->is_read)
                    <form action="{{ route('notifications.read', $notification) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs font-medium text-slate-400 hover:text-slate-600 whitespace-nowrap transition">Mark read</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="py-16 text-center bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-4xl mb-3">🔔</div>
                <p class="text-slate-400 font-medium">No notifications yet</p>
            </div>
        @endforelse

        @if($notifications->hasPages())
            <div class="pt-4">{{ $notifications->links() }}</div>
        @endif
    </div>
</x-app-layout>
