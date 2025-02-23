<?php

use App\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
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
            $news = [
                [
                    'id' => 1,'title'=>'Sân thượng 25m² xanh tốt đủ rau quả quanh năm của cô giáo dạy Toán ở Quảng Ngãi',
                    'slug'=>Str::slug('Sân thượng 25m² xanh tốt đủ rau quả quanh năm của cô giáo dạy Toán ở Quảng Ngãi'),
                    'description' => 'Khoảng sân thượng với diện tích trồng rau không quá rộng, chỉ 25m² nhưng cô giáo dạy Toán Trần Thị Tâm vẫn sắp xếp khoa học để trồng đủ rau quả ăn hàng ngày cho gia đình mình.',
                    'image'=>'/storage/images/news/san-thuong-25m.jpg','active'=>1,'is_focus'=>1
                ],
                [
                    'id' => 2,'title'=>'Khu vườn mùa xuân đẹp như tranh vẽ với đủ loại trái cây và rau củ của đôi vợ chồng đam mê trồng trọt',
                    'slug'=>Str::slug('Khu vườn mùa xuân đẹp như tranh vẽ với đủ loại trái cây và rau củ của đôi vợ chồng đam mê trồng trọt'),
                    'description' => 'Cặp vợ chồng người Úc yêu thích làm vườn, tự sản xuất thực phẩm sạch nên đã quyết định trồng các loại rau củ quả trên mảnh đất xung quanh nhà.',
                    'image'=>'/storage/images/news/khu-vuon-mua-xuan.jpg','active'=>1,'is_focus'=>1
                ],
                [
                    'id' => 3,'title'=>'Khu vườn bình yên chỉ toàn rau xanh và trái ngọt của bà mẹ 3 con',
                    'slug'=>Str::slug('Khu vườn bình yên chỉ toàn rau xanh và trái ngọt của bà mẹ 3 con'),
                    'description' => 'Với chị Mia (Hoa Kỳ), một ngày luôn là quỹ thời gian quá hạn hẹn để chăm sóc cho khu vườn trồng đủ loại cây xanh và một phần diện tích nuôi gà cùng hàng ngàn con ong.',
                    'image'=>'/storage/images/news/khu-vuon-binh-yen.jpg','active'=>1,'is_focus'=>1
                ]
            ];
            foreach ($news as $item){
                News::create($item);
            }
        }catch (\Illuminate\Database\QueryException $exception){
            echo $exception->getMessage();
        }
    }
}
