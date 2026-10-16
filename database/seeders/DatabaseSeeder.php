<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::insert([
            ['id' => 1, 'name' => 'Administrador General', 'email' => 'admin@asagroya.com', 'password' => bcrypt('admin123'), 'role' => 'Admin', 'status' => 'Active', 'phone' => '3001234567', 'doc_id' => 'CC-10001'],
            ['id' => 2, 'name' => 'Socio Asasagroyá', 'email' => 'partner@asagroya.com', 'password' => bcrypt('partner123'), 'role' => 'Partner', 'status' => 'Active', 'phone' => '3007654321', 'doc_id' => 'CC-10002'],
            ['id' => 3, 'name' => 'Vendedor Asasagroyá', 'email' => 'seller@asagroya.com', 'password' => bcrypt('seller123'), 'role' => 'Seller', 'status' => 'Active', 'phone' => '3001112222', 'doc_id' => 'CC-10003'],
            ['id' => 4, 'name' => 'Socio en Espera', 'email' => 'pending@asagroya.com', 'password' => bcrypt('seller123'), 'role' => 'Seller', 'status' => 'Pending', 'phone' => '3004445555', 'doc_id' => 'CC-10004'],
        ]);

        \App\Models\Customer::insert([
            ['id' => 1, 'name' => 'Hacienda El Porvenir (Carlos Gómez)', 'email' => 'carlos.gomez@elporvenir.com', 'phone' => '3152223344', 'tax_id' => 'NIT-800192834-1', 'address' => 'Vereda Vilaco, Yaguará, Huila'],
            ['id' => 2, 'name' => 'Finca La Esperanza (Sofía Rojas)', 'email' => 'sofia.rojas@laesperanza.com', 'phone' => '3104445566', 'tax_id' => 'CC-36555123', 'address' => 'Km 5 Vía Teruel, Yaguará'],
            ['id' => 3, 'name' => 'Asociación Ganadera del Huila', 'email' => 'contacto@asoganhuila.org', 'phone' => '3208889900', 'tax_id' => 'NIT-900888777-2', 'address' => 'Calle 4 # 5-12, Neiva'],
        ]);

        \App\Models\Product::insert([
            ['id' => 1, 'sku' => 'HID-TOM-001', 'name' => 'Tomate Hidropónico Larga Vida (Canastilla)', 'description' => 'Canastilla de 10kg de tomate hidropónico maduro de primera calidad.', 'category' => 'Hortalizas', 'purchase_price' => 18000.00, 'sell_price' => 28000.00, 'stock' => 20, 'min_stock' => 5],
            ['id' => 2, 'sku' => 'HID-LEC-002', 'name' => 'Lechuga Crespa Hidropónica Lola Rosa (Uds)', 'description' => 'Cabeza de lechuga crespa fresca cosechada en sistema NFT.', 'category' => 'Verduras', 'purchase_price' => 1200.00, 'sell_price' => 2500.00, 'stock' => 5, 'min_stock' => 10],
            ['id' => 3, 'sku' => 'HID-CIL-003', 'name' => 'Cilantro Hidropónico Fresco (Manojo)', 'description' => 'Manojo de cilantro hidropónico aromático de alta calidad.', 'category' => 'Hierbas', 'purchase_price' => 800.00, 'sell_price' => 1800.00, 'stock' => 8, 'min_stock' => 15],
            ['id' => 4, 'sku' => 'HID-API-004', 'name' => 'Apio Hidropónico Crujiente (Manojo)', 'description' => 'Tallo de apio cultivado hidropónicamente, libre de pesticidas.', 'category' => 'Verduras', 'purchase_price' => 1500.00, 'sell_price' => 3200.00, 'stock' => 0, 'min_stock' => 5],
            ['id' => 5, 'sku' => 'HID-ACE-005', 'name' => 'Acelga Hidropónica Verde (Atado)', 'description' => 'Atado de acelga fresca hidropónica cosechada en agua recirculante.', 'category' => 'Verduras', 'purchase_price' => 1000.00, 'sell_price' => 2200.00, 'stock' => 150, 'min_stock' => 30],
        ]);

        \App\Models\StockIntake::insert([
            ['id' => 1, 'product_id' => 1, 'quantity' => 20, 'purchase_cost' => 18000.00, 'intake_date' => '2026-08-01', 'created_by' => 1],
            ['id' => 2, 'product_id' => 2, 'quantity' => 5, 'purchase_cost' => 1200.00, 'intake_date' => '2026-08-02', 'created_by' => 1],
            ['id' => 3, 'product_id' => 3, 'quantity' => 8, 'purchase_cost' => 800.00, 'intake_date' => '2026-08-03', 'created_by' => 1],
            ['id' => 4, 'product_id' => 5, 'quantity' => 150, 'purchase_cost' => 1000.00, 'intake_date' => '2026-08-04', 'created_by' => 1],
        ]);

        \App\Models\Sale::insert([
            ['id' => 1, 'customer_id' => 1, 'seller_id' => 3, 'total_amount' => 85800.00, 'sale_date' => '2026-08-15 14:30:00'],
            ['id' => 2, 'customer_id' => 2, 'seller_id' => 3, 'total_amount' => 22000.00, 'sale_date' => '2026-08-20 10:15:00'],
        ]);

        \App\Models\SaleItem::insert([
            ['id' => 1, 'sale_id' => 1, 'product_id' => 1, 'quantity' => 3, 'unit_price' => 28000.00, 'subtotal' => 84000.00],
            ['id' => 2, 'sale_id' => 1, 'product_id' => 3, 'quantity' => 1, 'unit_price' => 1800.00, 'subtotal' => 1800.00],
            ['id' => 3, 'sale_id' => 2, 'product_id' => 5, 'quantity' => 10, 'unit_price' => 2200.00, 'subtotal' => 22000.00],
        ]);
    }
}
