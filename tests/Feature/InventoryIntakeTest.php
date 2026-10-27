<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockIntake;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryIntakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_correcting_an_intake_applies_only_the_quantity_difference(): void
    {
        $admin = $this->createAdmin();
        $product = Product::create([
            'sku' => 'SKU-INTAKE-1',
            'name' => 'Producto de inventario',
            'stock' => 10,
        ]);
        $intake = StockIntake::create([
            'product_id' => $product->id,
            'quantity' => 6,
            'purchase_cost' => 10,
            'intake_date' => '2026-10-01',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->put(route('inventory.updateIntake', $intake->id), [
            'quantity' => 4,
            'purchase_cost' => 10,
            'intake_date' => '2026-10-01',
        ]);

        $response->assertRedirect(route('inventory.intake'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
        $this->assertDatabaseHas('stock_intakes', [
            'id' => $intake->id,
            'quantity' => 4,
        ]);
    }

    public function test_intake_correction_cannot_reduce_stock_below_zero(): void
    {
        $admin = $this->createAdmin();
        $product = Product::create([
            'sku' => 'SKU-INTAKE-2',
            'name' => 'Producto de inventario',
            'stock' => 1,
        ]);
        $intake = StockIntake::create([
            'product_id' => $product->id,
            'quantity' => 5,
            'purchase_cost' => 10,
            'intake_date' => '2026-10-01',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->put(route('inventory.updateIntake', $intake->id), [
            'quantity' => 3,
            'purchase_cost' => 10,
            'intake_date' => '2026-10-01',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
        $this->assertDatabaseHas('stock_intakes', [
            'id' => $intake->id,
            'quantity' => 5,
        ]);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Administrador de prueba',
            'email' => 'admin-inventory-test@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Active',
            'doc_id' => 'ADMIN-INVENTORY-TEST',
        ]);
    }
}
