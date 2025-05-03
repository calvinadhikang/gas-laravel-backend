<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->createProduct();
        $this->createVendor();
        $this->createCustomer();
    }

    public function createProduct()
    {
        Product::create([
            'name' => 'Product A',
            'price' => 2000,
            'stock' => 10,
        ]);
        Product::create([
            'name' => 'Product B',
            'price' => 5000,
            'stock' => 5,
        ]);
        Product::create([
            'name' => 'Product C',
            'price' => 10000,
            'stock' => 3,
        ]);

        Inventory::create([
            'product_id' => 1,
            'stock' => 10,
            'base_price' => 2000,
            'type' => 'manual_input',
        ]);
        Inventory::create([
            'product_id' => 2,
            'stock' => 5,
            'base_price' => 5000,
            'type' => 'manual_input',
        ]);
        Inventory::create([
            'product_id' => 3,
            'stock' => 3,
            'base_price' => 10000,
            'type' => 'manual_input',
        ]);
    }

    public function createVendor()
    {
        Vendor::create([
            'name' => 'Vendor A',
            'phone' => '081234567890',
            'address' => 'Jl. Vendor A',
            'npwp' => '123456789012345',
            'email' => 'vendor@example.com',
        ]);
    }

    public function createCustomer()
    {
        Customer::create([
            'name' => 'Customer A',
            'phone' => '081234567890',
            'address' => 'Jl. Customer A',
            'npwp' => '123456789012345',
            'email' => 'customer@example.com',
        ]);
    }
}
