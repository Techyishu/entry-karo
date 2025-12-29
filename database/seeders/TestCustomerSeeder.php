<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if test customer already exists
        if (User::where('email', 'customer@test.com')->exists()) {
            $this->command->info('Test customer already exists!');
            return;
        }

        // Create test customer user
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'mobile_number' => '9876543210',
            'password' => Hash::make('Customer@123'),
            'role' => 'customer',
        ]);

        $this->command->info('✅ Test customer created successfully!');
        $this->command->info('Email: customer@test.com');
        $this->command->info('Password: Customer@123');
        $this->command->warn('⚠️  Please change the password after first login!');
    }
}
