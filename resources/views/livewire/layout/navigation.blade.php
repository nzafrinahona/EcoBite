<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo + Desktop Links --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 mr-8 group">
                    <div class="w-8 h-8 bg-green-600 rounded-xl flex items-center justify-center group-hover:bg-green-700 transition">
                        <span class="text-base">🌿</span>
                    </div>
                    <span class="font-bold text-green-700 text-lg tracking-tight">EcoBite</span>
                </a>

                <div class="hidden sm:flex items-center space-x-1">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') || request()->routeIs('student.dashboard') || request()->routeIs('staff.dashboard') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Dashboard
                        </a>
                    @endauth

                    <a href="{{ route('food.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('food.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Food Feed
                    </a>

                    @auth
                        @if(auth()->user()->isStudent())
                            <a href="{{ route('student.cart.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.cart.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Cart
                            </a>
                            <a href="{{ route('student.reservations.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.reservations.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Reservations
                            </a>
                        @endif

                        @if(auth()->user()->isStaff())
                            <a href="{{ route('staff.food-items.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('staff.food-items.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Manage Food
                            </a>
                            <a href="{{ route('staff.reservations.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('staff.reservations.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Reservations
                            </a>
                            <a href="{{ route('staff.verify-pickup') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('staff.verify-pickup') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Verify Pickup
                            </a>
                            <a href="{{ route('staff.analytics.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('staff.analytics.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Analytics
                            </a>
                        @endif

                        <a href="{{ route('notifications.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('notifications.*') ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Notifications
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Right side --}}
            <div class="hidden sm:flex items-center space-x-3">
                @auth
                    <div class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown"
                                class="flex items-center space-x-2 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}"
                                  x-text="name"
                                  x-on:profile-updated.window="name = $event.detail.name"
                                  class="max-w-[120px] truncate"></span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>

                        <div x-show="dropdown" @click.away="dropdown = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                            <a href="{{ route('profile') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                Profile Settings
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <button wire:click="logout"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                Log Out
                            </button>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition px-3 py-2">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">Sign up</a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-white border-t border-slate-100">
        <div class="px-4 pt-3 pb-4 space-y-1">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Dashboard</a>
            @endauth
            <a href="{{ route('food.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Food Feed</a>

            @auth
                @if(auth()->user()->isStudent())
                    <a href="{{ route('student.cart.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Cart</a>
                    <a href="{{ route('student.reservations.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Reservations</a>
                @endif

                @if(auth()->user()->isStaff())
                    <a href="{{ route('staff.food-items.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Manage Food</a>
                    <a href="{{ route('staff.reservations.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Reservations</a>
                    <a href="{{ route('staff.verify-pickup') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Verify Pickup</a>
                @endif

                <a href="{{ route('notifications.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Notifications</a>

                <div class="border-t border-slate-100 pt-3 mt-3">
                    <div class="px-3 mb-2">
                        <div class="font-semibold text-sm text-slate-800">{{ auth()->user()?->name }}</div>
                        <div class="text-xs text-slate-500">{{ auth()->user()?->email }}</div>
                    </div>
                    <a href="{{ route('profile') }}" wire:navigate class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Profile Settings</a>
                    <button wire:click="logout" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">Log Out</button>
                </div>
            @else
                <div class="border-t border-slate-100 pt-3 mt-3 space-y-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Log in</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700">Sign up</a>
                </div>
            @endauth
        </div>
    </div>
</nav>