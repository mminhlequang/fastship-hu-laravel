<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('users')->delete();
            DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
            User::insert(
                array(
                    [
                        "name" => "Huesoft",
                        "email" => "huesoft.it@gmail.com",
                        "username" => "huesoft",
                        "password" => bcrypt('hs@12345'),
                        "active" => 1

                    ],
                    [
                        "name" => "Admin",
                        "email" => "admin@gmail.com",
                        "username" => "admin",
                        "password" => bcrypt('hs@12345'),
                        "active" => 1

                    ],
                    [
                        "name" => "BeSoul",
                        "email" => "besoul@gmail.com",
                        "username" => "besoul",
                        "password" => bcrypt('abc@12345'),
                        "active" => 1

                    ],
                    [
                        "name" => "Customer",
                        "email" => "customer@gmail.com",
                        "username" => "customer",
                        "password" => bcrypt('hs@12345'),
                        "active" => 1

                    ]
                )
            );
        } catch (\Illuminate\Database\QueryException $exception) {
        }
    }
}
