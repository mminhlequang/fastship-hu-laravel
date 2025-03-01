<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class CustomerCar extends Model
{

    protected $table = 'customers_car';

    protected $fillable = ['name_vi', 'name_en', 'name_zh', 'name_hu'];

    // Hàm lấy tên sản phẩm theo ngôn ngữ hiện tại
    public function getNameByLocale()
    {
        $locale = App::getLocale(); // Lấy ngôn ngữ hiện tại (vi, en, fr,...)
        $column = 'name_' . $locale; // Tạo tên cột theo ngôn ngữ
        return $this->$column; // Nếu không có cột tương ứng, trả về null
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = now();
        });

    }

}
