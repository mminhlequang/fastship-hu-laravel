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
    protected $fillable = ['name','name_en','image','link','active','arrange'];
    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (empty($image)) return;
        $folder = "/images/banners/";
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)) {
            \Storage::makeDirectory(config('filesystems.disks.public.visibility') . $folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.' . $fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder . $timestamp . '-' . $filename . '.' . $fileExt);
        // $img = \Image::make($image->getRealPath());
        

        $img = \Image::make($image->getRealPath());
        $img->save(storage_path('app/public') . $pathImage);

        return config('filesystems.disks.public.path') . $pathImage;
    }
}
