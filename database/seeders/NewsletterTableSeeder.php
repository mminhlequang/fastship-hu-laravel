<?php

use App\Newsletter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class NewsletterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        for ($id = 0; $id <= 5; $id++){
            Newsletter::create([
                'id' => $id + 1,
                'email' => "demo.it$id@gmail.com"
            ]);
        }
    }
}
