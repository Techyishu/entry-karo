<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'price' => 99.00,
                'description' => 'Perfect for small premises with basic visitor management needs',
                'max_guards' => 1,
                'max_entries' => 300,
                'max_gate_logins' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 299.00,
                'description' => 'Ideal for growing businesses with multiple entry points',
                'max_guards' => 2,
                'max_entries' => 1000,
                'max_gate_logins' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 499.00,
                'description' => 'Unlimited everything for large organizations',
                'max_guards' => null,
                'max_entries' => null,
                'max_gate_logins' => null,
                'is_active' => true,
            ],
        ];

        // Deactivate old plans that no longer exist
        SubscriptionPlan::whereNotIn('slug', ['basic', 'pro', 'enterprise'])
            ->update(['is_active' => false]);

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
