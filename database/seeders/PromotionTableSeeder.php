<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PromotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        try {
            \DB::table('promotions')->insert([
                ['title' => 'Khuyến mãi 8/3','slug'=>'khuyen-mai-8-3','date_start'=>'2018-03-07','date_end'=>'2018-03-09','positive' => 1],
                ['title' => 'Khuyến mãi 20/10','slug' => 'khuyen-mai-20-10','date_start'=>'2018-10-19','date_end'=>'2018-10-21','positive' => 2]
            ]);
        }catch (\Illuminate\Database\QueryException $exception){
            echo $exception->getMessage();
        }
    }
}
