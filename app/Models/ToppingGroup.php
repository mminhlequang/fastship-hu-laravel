<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class ToppingGroup extends Model
{
    use Sortable;

    protected $table = 'toppings_group';

    public $sortable = [
        'id',
        'updated_at'
    ];

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'status' => 'integer',
        'max_quantity' => 'integer',
    ];

    protected $fillable = ['name_vi', 'name_en', 'name_zh', 'name_hu', 'creator_id', 'status', 'deleted_at', 'store_id', 'max_quantity'
    ];

    // Hàm lấy tên sản phẩm theo ngôn ngữ hiện tại
    public function getNameByLocale()
    {
        $locale = App::getLocale(); // Lấy ngôn ngữ hiện tại (vi, en, fr,...)
        $column = 'name_' . $locale; // Tạo tên cột theo ngôn ngữ
        return $this->$column; // Nếu không có cột tương ứng, trả về null
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    public function toppings()
    {
        return $this->belongsToMany('App\Models\Topping', 'toppings_group_link', 'group_id', 'topping_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'products_groups', 'group_id', 'product_id');
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
