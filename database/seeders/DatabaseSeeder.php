<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(LanguageSeeder::class);
        // $this->call(AdminUserSeeder::class);
	    $this->call(PermissionsTableSeeder::class);
        // $this->call(StatusSeeder::class);

    }
}
