<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_rejects_invalid_data(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => '',
            'email' => 'correo-invalido',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_creates_a_fan_by_default(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Usuario de prueba',
            'email' => 'usuario@example.com',
            'password' => 'LigaHub123!',
            'password_confirmation' => 'LigaHub123!',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Usuario de prueba',
            'email' => 'usuario@example.com',
            'role' => 'fan',
        ]);
    }

    public function test_registration_cannot_assign_the_admin_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Usuario malicioso',
            'email' => 'malicioso@example.com',
            'password' => 'LigaHub123!',
            'password_confirmation' => 'LigaHub123!',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'malicioso@example.com',
            'role' => 'fan',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'malicioso@example.com',
            'role' => 'admin',
        ]);
    }
}
