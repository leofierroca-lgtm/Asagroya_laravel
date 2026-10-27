<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_required_document_is_persisted(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Nuevo vendedor',
            'email' => 'nuevo-vendedor@example.com',
            'doc_id' => 'CC-20001',
            'phone' => '3005556677',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'Seller',
            'status' => 'Active',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'nuevo-vendedor@example.com',
            'doc_id' => 'CC-20001',
            'phone' => '3005556677',
        ]);
        $this->assertTrue(Hash::check('secret123', User::where('email', 'nuevo-vendedor@example.com')->value('password')));
    }

    public function test_user_creation_requires_a_document_id(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Vendedor sin documento',
            'email' => 'sin-documento@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'Seller',
            'status' => 'Active',
        ]);

        $response->assertSessionHasErrors('doc_id');
        $this->assertDatabaseMissing('users', ['email' => 'sin-documento@example.com']);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Administrador de prueba',
            'email' => 'admin-user-test@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Active',
            'doc_id' => 'ADMIN-USER-TEST',
        ]);
    }
}
