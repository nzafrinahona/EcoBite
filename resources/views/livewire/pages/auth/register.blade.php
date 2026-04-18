<?php

use App\Models\User;
use App\Models\Student;
use App\Models\CafeteriaStaff;
use App\Models\Cafeteria;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $role = 'student';
    public string $student_id = '';
    public string $department = '';
    public string $phone = '';
    public string $cafeteria_id = '';
    public string $position = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:student,staff'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];

        if ($this->role === 'student') {
            $rules['student_id'] = ['required', 'string', 'max:20', 'unique:students,student_id'];
            $rules['department'] = ['required', 'string', 'max:100'];
        } else {
            $rules['cafeteria_id'] = ['required', 'exists:cafeterias,id'];
            $rules['position'] = ['required', 'string', 'max:100'];
        }

        $validated = $this->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($validated['role'] === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_id' => $validated['student_id'],
                'department' => $validated['department'],
                'phone' => $this->phone ?: null,
            ]);
        } else {
            CafeteriaStaff::create([
                'user_id' => $user->id,
                'cafeteria_id' => $validated['cafeteria_id'],
                'position' => $validated['position'],
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function with(): array
    {
        return [
            'cafeterias' => Cafeteria::orderBy('name')->get(),
        ];
    }
}; ?>

<div>
    <form wire:submit="register" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
            <input wire:model="name" id="name" type="text" name="name"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input wire:model="email" id="email" type="email" name="email"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">I am a...</label>
            <select wire:model.live="role" id="role" name="role"
                    class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
                <option value="student">Student</option>
                <option value="staff">Cafeteria Staff</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
        </div>

        @if($role === 'student')
        <div>
            <label for="student_id" class="block text-sm font-medium text-slate-700 mb-1.5">Student ID</label>
            <input wire:model="student_id" id="student_id" type="text" name="student_id"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   placeholder="e.g. STU00001" required>
            <x-input-error :messages="$errors->get('student_id')" class="mt-1.5" />
        </div>
        <div>
            <label for="department" class="block text-sm font-medium text-slate-700 mb-1.5">Department</label>
            <input wire:model="department" id="department" type="text" name="department"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required>
            <x-input-error :messages="$errors->get('department')" class="mt-1.5" />
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                Phone <span class="text-slate-400 font-normal">(optional)</span>
            </label>
            <input wire:model="phone" id="phone" type="text" name="phone"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
        </div>
        @endif

        @if($role === 'staff')
        <div>
            <label for="cafeteria_id" class="block text-sm font-medium text-slate-700 mb-1.5">Cafeteria</label>
            <select wire:model="cafeteria_id" id="cafeteria_id" name="cafeteria_id"
                    class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                    required>
                <option value="">Select Cafeteria</option>
                @foreach($cafeterias as $cafeteria)
                    <option value="{{ $cafeteria->id }}">{{ $cafeteria->name }} — {{ $cafeteria->location }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('cafeteria_id')" class="mt-1.5" />
        </div>
        <div>
            <label for="position" class="block text-sm font-medium text-slate-700 mb-1.5">Position</label>
            <input wire:model="position" id="position" type="text" name="position"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   placeholder="e.g. Manager, Cashier" required>
            <x-input-error :messages="$errors->get('position')" class="mt-1.5" />
        </div>
        @endif

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
            <input wire:model="password" id="password" type="password" name="password"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation"
                   class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit"
                class="w-full py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
            Create Account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-green-600 hover:text-green-700">Log in</a>
    </p>
</div>
