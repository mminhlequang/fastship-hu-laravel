<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Approve;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('approves')->delete();
            DB::statement('ALTER TABLE approves AUTO_INCREMENT = 1;');
            Approve::insert(
                array(
                    [
                        "name" => "Đơn mới",
                        "number" => "1",
                        "color" => "#ffa726",
    
                    ],
                    [
                        "name" => "Đã tiếp nhận",
                        "number" => "2",
                        "color" => "#ffa726",
    
                    ],
                
                    [
                        "name" => "Đã giao",
                        "number" => "3",
                        "color" => "#26c6da",
    
                    ],
                    [
                        "name" => "Đã hoàn thành",
                        "number" => "4",
                        "color" => "#66bb6a",
    
                    ],
                  
                    
                )
            );
        } catch (\Illuminate\Database\QueryException $exception) {
        }
   
    }
}
