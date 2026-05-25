<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Customer account
        User::create([
            'name'      => 'John Customer',
            'email'     => 'customer@tti.com',
            'password'  => Hash::make('password'),
            'role'      => 'customer',
        ]);

        // Branch Manager account
        User::create([
            'name'      => 'Maria Manager',
            'email'     => 'manager@tti.com',
            'password'  => Hash::make('password'),
            'role'      => 'branch_manager',
            'branch_id' => 1,
        ]);

        // Sales Rep account
        User::create([
            'name'      => 'Jose Sales',
            'email'     => 'sales@tti.com',
            'password'  => Hash::make('password'),
            'role'      => 'sales_rep',
            'branch_id' => 1,
        ]);

        // Dispatcher account
        User::create([
            'name'      => 'Ana Dispatcher',
            'email'     => 'dispatcher@tti.com',
            'password'  => Hash::make('password'),
            'role'      => 'dispatcher',
            'branch_id' => 1,
        ]);

        // Technician account
        User::create([
            'name'      => 'Carlo Tech',
            'email'     => 'tech@tti.com',
            'password'  => Hash::make('password'),
            'role'      => 'technician',
            'branch_id' => 1,
        ]);
    }
}