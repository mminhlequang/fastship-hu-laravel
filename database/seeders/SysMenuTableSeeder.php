<?php

use App\Models\SysMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SysMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        SysMenu::create([
            "title" => "Trang chủ",
            "slug" => "trang-chu",
            "position" => 1,
            "arrange" => 1
        ]);
        SysMenu::create([
            "title" => "Giới thiệu",
            "slug" => "gioi-thieu",
            "position" => 1,
            "arrange" => 2
        ]);
    }
}
