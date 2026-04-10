<?php

namespace Tests\Feature\Auth;

use App\Models\Cafeteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    public function test_new_users_can_register(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('role', 'student')
            ->set('student_id', 'STU99999')
            ->set('department', 'Computer Science')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'student',
        ]);
        $this->assertDatabaseHas('students', [
            'student_id' => 'STU99999',
            'department' => 'Computer Science',
        ]);
    }

    public function test_staff_users_can_register(): void
    {
        $cafeteria = Cafeteria::factory()->create();

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Staff User')
            ->set('email', 'staff-test@example.com')
            ->set('role', 'staff')
            ->set('cafeteria_id', (string) $cafeteria->id)
            ->set('position', 'Manager')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'staff-test@example.com',
            'role' => 'staff',
        ]);
        $this->assertDatabaseHas('cafeteria_staff', [
            'cafeteria_id' => $cafeteria->id,
            'position' => 'Manager',
        ]);
    }
}
