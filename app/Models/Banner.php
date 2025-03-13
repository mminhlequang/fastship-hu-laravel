<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Banner extends Model
{
    use HasFactory,Sortable;

    protected $table = "banners";

    protected $sortable = [
        'name',
        'updated_at'
    ];
    protected $primaryKey = 'id';

    protected $casts = [
        'type' => 'string',
        'reference_id' => 'integer'
    ];


    protected $fillable = ['name','name_en','image','link','active','arrange', 'type', 'reference_id'];

    public static $TYPE = [
        "" => "--Loại--",
        "news" => "News",
        "store" => "Store",
        "voucher" => "Voucher",
    ];

    static public function uploadAndResize($image, $width = 1349, $height = null) {
        if (empty($image)) return;

        $folder = "/images/banners/";
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
}
