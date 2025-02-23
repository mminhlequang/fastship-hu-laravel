
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            factory(App\Models\User::class)->create([
                    "name" => "Huesoft",
                    "email" => "huesoft.it@gmail.com",
                    "username" => "admin",
                    "password" => bcrypt(env('ADMIN_PWD', 'hs@12345'))]
            );
        } catch (\Illuminate\Database\QueryException $exception) {

        }
    }
}
