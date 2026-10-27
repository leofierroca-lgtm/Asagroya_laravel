<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalePersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_uses_catalog_price_and_decrements_stock(): void
    {
        $seller = $this->createSeller();
        $customer = Customer::create([
            'name' => 'Cliente de prueba',
            'tax_id' => 'TAX-SALE-1',
        ]);
        $product = Product::create([
            'sku' => 'SKU-SALE-1',
            'name' => 'Producto de prueba',
            'sell_price' => 28000,
            'stock' => 5,
        ]);

        $response = $this->actingAs($seller)->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'payment_method' => 'Efectivo',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 1,
                ],
            ],
        ]);

        $response->assertRedirect(route('sales.history'));
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => '28000.00',
            'subtotal' => '56000.00',
        ]);
        $this->assertDatabaseHas('sales', [
            'customer_id' => $customer->id,
            'total_amount' => '56000.00',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
    }

    public function test_sale_with_insufficient_stock_changes_no_records(): void
    {
        $seller = $this->createSeller();
        $customer = Customer::create([
            'name' => 'Cliente de prueba',
            'tax_id' => 'TAX-SALE-2',
        ]);
        $product = Product::create([
            'sku' => 'SKU-SALE-2',
            'name' => 'Producto de prueba',
            'sell_price' => 28000,
            'stock' => 1,
        ]);

        $response = $this->actingAs($seller)->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'payment_method' => 'Efectivo',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 28000,
                ],
            ],
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('sales', ['customer_id' => $customer->id]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
    }

    private function createSeller(): User
    {
        return User::create([
            'name' => 'Vendedor de prueba',
            'email' => 'seller-sale-test@example.com',
            'password' => 'password',
            'role' => 'Seller',
            'status' => 'Active',
            'doc_id' => 'SELLER-SALE-TEST',
        ]);
    }
}
