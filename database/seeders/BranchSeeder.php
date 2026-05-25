<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create([
            'name'      => 'TTI Davao Branch',
            'city'      => 'Davao City',
            'address'   => 'Illustre St, Davao City',
            'is_active' => 1,
        ]);

        Branch::create([
            'name'      => 'TTI Cebu Branch',
            'city'      => 'Cebu City',
            'address'   => 'Ayala Center, Cebu City',
            'is_active' => 1,
        ]);

        Branch::create([
            'name'      => 'TTI Cagayan Branch',
            'city'      => 'Cagayan de Oro',
            'address'   => 'Divisoria, Cagayan de Oro',
            'is_active' => 1,
        ]);
    }
}