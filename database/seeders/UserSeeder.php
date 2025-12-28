<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@entrykaro.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Create Customer users (for guards to be assigned to)
        $customer1 = \App\Models\User::create([
            'name' => 'Acme Corporation',
            'email' => 'acme@entrykaro.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $customer2 = \App\Models\User::create([
            'name' => 'Tech Solutions Inc',
            'email' => 'tech@entrykaro.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Create Guard users assigned to customers
        \App\Models\User::create([
            'name' => 'John Guard',
            'password' => Hash::make('password'),
            'role' => 'guard',
            'customer_id' => $customer1->id,
        ]);

        \App\Models\User::create([
            'name' => 'Jane Guard',
            'password' => Hash::make('password'),
            'role' => 'guard',
            'customer_id' => $customer2->id,
        ]);
    }
}

