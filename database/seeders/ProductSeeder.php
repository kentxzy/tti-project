<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name'     => 'Laptop',
            'price'    => 45000.00,
            'category' => 'Electronics',
        ]);

        Product::create([
            'name'     => 'Printer',
            'price'    => 12000.00,
            'category' => 'Electronics',
        ]);

        Product::create([
            'name'     => 'Office Chair',
            'price'    => 8500.00,
            'category' => 'Furniture',
        ]);
    }
}