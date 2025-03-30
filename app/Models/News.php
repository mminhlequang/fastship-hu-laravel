<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Str;

class News extends Model
{
    use Sortable, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';


    public $sortable = [
        'name',
        'category_id',
        'updated_at'
    ];

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name_vi', 'name_en', 'name_zh', 'name_hu', 'slug', 'image', 'description', 'content', 'active', 'creator_id', 'country_code'];

    // Hàm lấy tên sản phẩm theo ngôn ngữ hiện tại
    public function getNameByLocale()
    {
        $locale = App::getLocale(); // Lấy ngôn ngữ hiện tại (vi, en, fr,...)
        $column = 'name_' . $locale; // Tạo tên cột theo ngôn ngữ
        return $this->$column; // Nếu không có cột tương ứng, trả về null
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public static function getListActive()
    {
        $arr = [1 => 'Hiển thị', 2 => 'Không hiển thị'];
        return $arr;
    }

    static public function uploadAndResize($image, $width = 1349, $height = null)
    {
        if (empty($image)) return;

        $folder = "/images/news/";
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
            $model->creator_id = \Auth::id();
            $model->slug = str_slug($model->name_vi);
        });

        self::saving(function ($model) {
            $model->slug = str_slug($model->name_vi);
        });
    }
}
