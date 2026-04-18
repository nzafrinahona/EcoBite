<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input wire:model="form.email" id="email" type="email" name="email"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
            <input wire:model="form.password" id="password" type="password" name="password"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required autocomplete="current-password">
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input wire:model="form.remember" id="remember" type="checkbox"
                       class="rounded border-slate-300 text-green-600 focus:ring-green-500" name="remember">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate
                   class="text-sm text-green-600 hover:text-green-700">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
            Log in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate class="font-semibold text-green-600 hover:text-green-700">Sign up</a>
    </p>
</div>
