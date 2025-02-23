<?php

namespace Modules\Theme\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Theme\Entities\Partner;
use App\Slider;

class ThemeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $slider = [
            [
                'id' => 1,'name'=>'Slide demo 1','image' => '/storage/images/slides/fattoria-banner-1.jpg',
                'arrange' => 1,'active'=>1
            ],
            [
                'id' => 2,'name'=>'Slide demo 2','image' => '/storage/images/slides/fattoria-banner-2.jpg',
                'arrange' => 1,'active'=>1
            ],
            [
                'id' => 3,'name'=>'Slide demo 3','image' => '/storage/images/slides/fattoria-banner-3.jpg',
                'arrange' => 1,'active'=>1
            ],
        ];
        foreach ($slider as $item){
            Slider::create($item);
        }
        $partner = [
            [
                'id' => 1,'name' => 'logo 1','image' => '/storage/images/partners/fattoria-logo-brand-1.jpg','active'=>1,'arrange'=>1
            ],
            [
                'id' => 2,'name' => 'logo 2','image' => '/storage/images/partners/fattoria-logo-brand-2.jpg','active'=>1,'arrange'=>2
            ],
            [
                'id' => 3,'name' => 'logo 3','image' => '/storage/images/partners/fattoria-logo-brand-3.jpg','active'=>1,'arrange'=>3
            ],
            [
                'id' => 4,'name' => 'logo 4','image' => '/storage/images/partners/fattoria-logo-brand-4.jpg','active'=>1,'arrange'=>4
            ],
            [
                'id' => 5,'name' => 'logo 5','image' => '/storage/images/partners/fattoria-logo-brand-5.jpg','active'=>1,'arrange'=>5
            ]
        ];
        foreach ($partner as $item){
            Partner::create($item);
        }
    }
}
