<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_is_persisted_with_valid_schema_lengths(): void
    {
        $user = User::create([
            'name' => 'Administrador de prueba',
            'email' => 'admin-customer-1@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Active',
            'doc_id' => 'ADMIN-100',
        ]);

        $response = $this->actingAs($user)->post(route('customers.store'), [
            'document_id' => 'TAX-200',
            'name' => 'Cliente de prueba',
            'email' => 'cliente@example.com',
            'phone' => '3001234567',
            'address' => 'Yaguará, Huila',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'tax_id' => 'TAX-200',
            'name' => 'Cliente de prueba',
            'phone' => '3001234567',
        ]);
    }

    public function test_customer_fields_exceeding_schema_limits_are_rejected(): void
    {
        $user = User::create([
            'name' => 'Administrador de prueba',
            'email' => 'admin-customer-2@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Active',
            'doc_id' => 'ADMIN-101',
        ]);

        $response = $this->actingAs($user)->post(route('customers.store'), [
            'document_id' => 'TAX-201',
            'name' => str_repeat('N', 101),
            'email' => str_repeat('a', 90).'@example.com',
            'phone' => str_repeat('1', 21),
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone']);
        $this->assertDatabaseMissing('customers', ['tax_id' => 'TAX-201']);
    }

    public function test_customer_updates_reject_fields_exceeding_schema_limits(): void
    {
        $user = User::create([
            'name' => 'Administrador de prueba',
            'email' => 'admin-customer-3@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Active',
            'doc_id' => 'ADMIN-102',
        ]);
        $customer = Customer::create([
            'tax_id' => 'TAX-202',
            'name' => 'Cliente existente',
        ]);

        $response = $this->actingAs($user)->put(route('customers.update', $customer), [
            'document_id' => 'TAX-202',
            'name' => str_repeat('N', 101),
            'email' => null,
            'phone' => null,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Cliente existente',
        ]);
    }
}
