<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Category
        \DB::table('category_products')->insert([
            'name' => 'Trái Cây',
            'slug' => 'trai-cay',
            'image' => '/storage/images/products/17-600x600.jpg',
            'description' => 'The device is in good cosmetic condition and will show minor scratches and/or scuff marks.',
            'active' => '1',
            'parent_id' => '0',
        ]);
        \DB::table('category_products')->insert([
            'name' => 'Rau Xanh',
            'slug' => 'rau-xanh',
            'image' => '/storage/images/products/17-600x600.jpg',
            'description' => 'The device is in good cosmetic condition and will show minor scratches and/or scuff marks.',
            'active' => '1',
            'parent_id' => '0',
        ]);

        //Product
        \DB::table('products')->insert([
            'id' => 1,
            'category_id' => 1,
            'name' => 'Cam Sành Canada',
            'slug' => 'cam-sanh-canada',
            'description' => 'A brand new, sealed Lilac Purple Verizon Global Unlocked Galaxy S9 by Samsung.',
            'content' => 'Hoa quả luôn là thức ăn dinh dưỡng tốt cho sức khỏe con người hằng ngày,nhưng hiện nay ...',
            'image' => '/storage/images/products/15-600x600.jpg',
            'price' => '700.000',
            'active' => 1,

        ]);

        \DB::table('products')->insert([
            'id' => 2,
            'category_id' => 1,
            'name' => 'Ớt chuông đà lạt',
            'slug' => 'ot-chuong-da-lat',
            'description' => 'GSM & CDMA FACTORY UNLOCKED! WORKS WORLDWIDE! FACTORY UNLOCKED. iPhone x 64gb. iPhone 8 64gb. iPhone 8 64gb. iPhone X with iOS 11.',
            'content' => 'Hoa quả luôn là thức ăn dinh dưỡng tốt cho sức khỏe con người hằng ngày,nhưng hiện nay ...',
            'image' => '/storage/images/products/15-600x600.jpg',
            'price' => '900.000',
            'active' => 1,

        ]);

        \DB::table('products')->insert([
            'id' => 3,
            'category_id' => 1,
            'name' => 'Google Pixel 2 XL',
            'slug' => 'google-pixel-2-xl',
            'description' => 'New condition
                • No returns, but backed by eBay Money back guarantee',
            'content' => 'Hoa quả luôn là thức ăn dinh dưỡng tốt cho sức khỏe con người hằng ngày,nhưng hiện nay ...',
            'image' => '/storage/images/products/15-600x600.jpg',
            'price' => '500.000',
            'active' => 1,

        ]);

        \DB::table('products')->insert([
            'id' => 4,
            'category_id' => 1,
            'name' => 'Chuối đà lạt',
            'slug' => 'chuoi-da-lat',
            'description' => 'NETWORK Technology GSM. Protection Corning Gorilla Glass 4.',
            'content' => 'Hoa quả luôn là thức ăn dinh dưỡng tốt cho sức khỏe con người hằng ngày,nhưng hiện nay ...',
            'image' => '/storage/images/products/15-600x600.jpg',
            'price' => '800.000',
            'active' => 1,

        ]);

        


        //Attibutes
        \DB::table('product_attributes')->insert([
            'id' => 1,
            'key' => 'Color',
            'value' => '{"0":"\u0110\u1ecf","1":"Xanh","2":"T\u00edm"}',
            'active' => 1,
        ]);

        \DB::table('product_attributes')->insert([
            'id' => 2,
            'key' => 'Size',
            'value' => '{"0":"XL","1":"M"}',
            'active' => 1,

        ]);
        
        //Group 
        \DB::table('group_products')->insert([
            'id' => 1,
            'name' => 'Sản phẩm nổi bật',
            'slug' => 'san-pham-noi-bat',
            'active' => 1,

        ]);

        \DB::table('group_products')->insert([
            'id' => 2,
            'name' => 'Sản phẩm khuyến mãi',
            'slug' => 'san-pham-khuyen-mai',
            'active' => 1,

        ]);

        //Provier
        \DB::table('provider_products')->insert([
            'id' => 1,
            'name' => 'Fashion',
            'slug' => 'fashion',
            'active' => 1,

        ]);

        \DB::table('provider_products')->insert([
            'id' => 2,
            'name' => 'New Look',
            'slug' => 'new-look',
            'active' => 1,

        ]);

        //Gallery
        \DB::table('gallery_products')->insert([
            'id' => 1,
            'image' => '5139061583210204.png',
            'product_id' => 3,

        ]);

        \DB::table('gallery_products')->insert([
            'id' => 2,
            'image' => '5139061583210204.png',
            'product_id' => 1,

        ]);

        //SEO 
        \DB::table('seo_ecommerce')->insert([
            'id' => 1,
            'meta_title' => 'Title product',
            'meta_keyword' => 'Keyword product',
            'meta_description'=> 'Descript...',
            'slug' => 'slug-product',
            'type_id' => 1,

        ]);

        \DB::table('seo_ecommerce')->insert([
            'id' => 2,
            'meta_title' => 'Title product',
            'meta_keyword' => 'Keyword product',
            'meta_description'=> 'Descript...',
            'slug' => 'slug-product',
            'type_id' => 2,

        ]);

        \DB::table('seo_ecommerce')->insert([
            'id' => 3,
            'meta_title' => 'Title product',
            'meta_keyword' => 'Keyword product',
            'meta_description'=> 'Descript...',
            'slug' => 'slug-product',
            'type_id' => 3,

        ]);

        \DB::table('seo_ecommerce')->insert([
            'id' => 4,
            'meta_title' => 'Title product',
            'meta_keyword' => 'Keyword product',
            'meta_description'=> 'Descript...',
            'slug' => 'slug-product',
            'type_id' => 4,

        ]);
    }
}
