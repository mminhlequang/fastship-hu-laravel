<?php

namespace Database\Seeders;

use DB;
use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->delete();
        DB::statement('ALTER TABLE languages AUTO_INCREMENT = 1;');
        Language::insert(
            array(
                [
                    "name" => "Tiếng Việt",
                    "prefix" => "vi",
                ],
                [
                    "name" => "English",
                    "prefix" => "en",
                ],
                [
                    "name" => "한국어",
                    "prefix" => "kr",
                ]
            )
        );
    }
}
