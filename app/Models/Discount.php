<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory,Sortable;

    protected $table = 'discounts';

    public $sortable = [
        'id',
        'name'
    ];
    protected $primaryKey = 'id';

    protected $fillable = ['code','cart_value','image','value','description','expiry_date','type','active','name','sale_maximum'];


    public static $TYPE = [
        "" => "--Loại--",
        "1" => "Giảm giá theo %",
        "2" => "Giảm giá theo đơn hàng",
    ];

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


}
