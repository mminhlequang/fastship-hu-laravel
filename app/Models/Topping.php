<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    use Sortable;

    protected $table = 'toppings';

    public $sortable = [
        'id',
        'name',
        'updated_at'
    ];

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'price' => 'double',
        'status' => 'integer',
    ];

    protected $fillable = ['name_vi', 'name_en', 'name_zh', 'name_hu', 'image', 'creator_id', 'status', 'deleted_at', 'store_id', 'price'
    ];

    // Hàm lấy tên sản phẩm theo ngôn ngữ hiện tại
    public function getNameByLocale()
    {
        $locale = App::getLocale(); // Lấy ngôn ngữ hiện tại (vi, en, fr,...)
        $column = 'name_' . $locale; // Tạo tên cột theo ngôn ngữ
        return $this->$column; // Nếu không có cột tương ứng, trả về null
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\Customer', 'creator_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }


    static public function uploadAndResize($image, $width = 1349, $height = null) {
        if (empty($image)) return;

        $folder = "/images/toppings/";
        $diskVisibility = config('filesystems.disks.public.visibility');

        if (!\Storage::disk($diskVisibility)->has($folder)) {
            \Storage::makeDirectory($diskVisibility . $folder);
        }

        // Getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = 'webp'; // Set the desired extension to webp
        $filename = str_slug(basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension()));
        $pathAvatar = str_replace([' ', ':'], '-', $folder . $timestamp . '-' . $filename . '.' . $fileExt);

        // Resize and convert to WebP
        \Image::make($image->getRealPath())
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('webp', 90) // Convert to WebP with quality 90
            ->save(public_path('storage') . $pathAvatar);

        return config('filesystems.disks.public.path') . $pathAvatar;
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
