<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

class News extends Model
{
    use Sortable, Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'news.title_vi' => 10,
            'news.title_en' => 10
        ],
    ];

    public function searchableAs()
    {
        return 'news_index';
    }

    public $sortable = [
        'title',
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
    protected $fillable = ['title', 'category_id', 'slug', 'image', 'video_url', 'description', 'content', 'active', 'is_focus', 'creator_id'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }



    public function toSearchableArray()
    {
        $array = $this->toArray();

        $data = [
            'title' => $array['title']
        ];
        
        return $data;
    }


    public static function getListActive()
    {
        $arr = [1 => 'Hiển thị', 2 => 'Không hiển thị'];
        return $arr;
    }

    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (empty($image)) return;
        $folder = "/images/news/";
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
            $model->creator_id = \Auth::user()->id;
        });

    }
}
