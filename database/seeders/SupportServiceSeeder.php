<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SupportServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('support_service')->insert([
            [
                'name' => 'Food delivery',
                'description' => 'For restaurant, cafes, etc.. The store offers prepared specialty dishes.',
                'is_store_register' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Supermarket and grocery',
                'description' => 'Grocery shopping: grocery delivery and convenience service',
                'is_store_register' => 1,
                'is_active' => 0,
            ],
            [
                'name' => 'Gobike',
                'description' => 'Transport passengers by motorbike',
                'is_store_register' => 0,
                'is_active' => 0,
            ],
        ]);

        \DB::table('business_type')->insert([
            [
                'name' => 'Nhà hàng',
                'support_service_id' => 1,
                'description' => 'Nhà hàng',
                'is_active' => 1,
            ],
            [
                'name' => 'Cafe/Dessert',
                'support_service_id' => 1,
                'description' => 'Cafe/Dessert',
                'is_active' => 1,
            ],
            [
                'name' => 'Quán ăn',
                'support_service_id' => 1,
                'description' => 'Quán ăn',
                'is_active' => 1,
            ],
            [
                'name' => 'Bar/Pub',
                'support_service_id' => 1,
                'description' => 'Bar/Pub',
                'is_active' => 1,
            ]
        ]);
    }
}
