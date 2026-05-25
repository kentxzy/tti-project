<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // TTI Davao Branch - Product 1
        Inventory::create([
            'branch_id'  => 1,
            'product_id' => 1,
            'stock'      => 10,
        ]);

        // TTI Cebu Branch - Product 1
        Inventory::create([
            'branch_id'  => 2,
            'product_id' => 1,
            'stock'      => 5,
        ]);

        // TTI Davao Branch - Product 2
        Inventory::create([
            'branch_id'  => 1,
            'product_id' => 2,
            'stock'      => 8,
        ]);

        // TTI Cagayan Branch - Product 2
        Inventory::create([
            'branch_id'  => 3,
            'product_id' => 2,
            'stock'      => 3,
        ]);
    }
}