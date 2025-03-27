<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory, Sortable;

    protected $table = 'discounts';

    public $sortable = [
        'id',
        'name'
    ];
    protected $primaryKey = 'id';

    protected $fillable = ['code', 'cart_value', 'image', 'value', 'description', 'expiry_date', 'type', 'active', 'name', 'sale_maximum', 'store_id', 'user_id',
        'deleted_at', 'start_date', 'product_ids', 'creator_id'
    ];


    public static $TYPE = [
        "" => "--Loại--",
        "percentage" => "Percentage %",
        "fixed" => "Fixed amount",
    ];


    protected $casts = [
        'cart_value' => 'double',
        'sale_maximum' => 'double',
        'type' => 'integer',
        'active' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    // Mối quan hệ với User qua bảng trung gian voucher_user(lưu danh sách voucher đã dùng)
    public function users()
    {
        return $this->belongsToMany('App\Models\Customer', 'discounts_used', 'discount_id', 'user_id')
            ->withTimestamps();
    }

    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (empty($image)) return;
        $folder = "/images/discounts/";
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)) {
            \Storage::makeDirectory(config('filesystems.disks.public.visibility') . $folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.' . $fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder . $timestamp . '-' . $filename . '.' . $fileExt);

        $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public') . $pathImage);

        return config('filesystems.disks.public.path') . $pathImage;
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
