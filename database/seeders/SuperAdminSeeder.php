<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        if (User::where('email', 'admin@entrykaro.com')->exists()) {
            $this->command->info('Super admin already exists!');
            return;
        }

        // Create super admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@entrykaro.com',
            'mobile_number' => '9999999999',
            'password' => Hash::make('Admin@123'),
            'role' => 'super_admin',
        ]);

        $this->command->info('✅ Super admin created successfully!');
        $this->command->info('Email: admin@entrykaro.com');
        $this->command->info('Password: Admin@123');
        $this->command->warn('⚠️  Please change the password after first login!');
    }
}
