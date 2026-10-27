<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginThrottlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_login_attempts_are_limited_per_email_and_ip(): void
    {
        $this->createActiveUser();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $response = $this->post(route('login'), [
                'email' => 'active-user@example.com',
                'password' => 'wrong-password',
            ]);

            $response->assertSessionHasErrors('email');
        }

        $response = $this->post(route('login'), [
            'email' => 'active-user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringStartsWith(
            'Ha realizado demasiados intentos de inicio de sesión.',
            $response->getSession()->get('errors')->first('email')
        );
    }

    public function test_successful_login_clears_failed_attempts(): void
    {
        $this->createActiveUser();

        for ($attempt = 0; $attempt < 4; $attempt++) {
            $this->post(route('login'), [
                'email' => 'active-user@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post(route('login'), [
            'email' => 'active-user@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $response = $this->post(route('login'), [
                'email' => 'active-user@example.com',
                'password' => 'wrong-password',
            ]);

            $response->assertSessionHasErrors('email');
        }
    }

    private function createActiveUser(): User
    {
        return User::create([
            'name' => 'Usuario de prueba',
            'email' => 'active-user@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'Admin',
            'status' => 'Active',
            'doc_id' => 'ACTIVE-LOGIN-TEST',
        ]);
    }
}
