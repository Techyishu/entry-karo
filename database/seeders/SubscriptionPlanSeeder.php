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
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'price' => 99.00,
                'description' => 'Perfect for small businesses with limited guards',
                'max_guards' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Plan',
                'slug' => 'premium',
                'price' => 199.00,
                'description' => 'Ideal for growing businesses with more guards',
                'max_guards' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
