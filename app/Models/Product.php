<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, Sortable;

    protected $table = 'products';

    public $sortable = [
        'name_vi',
        'category_id',
        'updated_at',
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'name_vi' => 'string',
        'price' => 'double',
        'active' => 'integer',
        'status' => 'integer'
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
    protected $fillable = [
        'name_vi', 'name_en', 'name_zh', 'name_hu', 'slug', 'image', 'description', 'content', 'active', 'price', 'price_compare',
        'category_id', 'creator_id', 'deleted_at', 'store_id', 'group_id',
        'status', 'time_open', 'time_close'
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

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }


    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }


    public function gallery()
    {
        return $this->hasMany('App\GalleryProduct')->orderBy('id','ASC');
    }

    public function rating()
    {
        return $this->hasMany('App\Models\ProductRating', 'product_id');
    }

    public function favorites()
    {
        return $this->belongsToMany('App\Models\Customer', 'products_favorite', 'product_id', 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_products', 'product_id', 'category_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\OrderItem', 'product_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\ToppingGroup', 'products_groups', 'product_id', 'group_id');
    }

    public function variations()
    {
        return $this->belongsToMany('App\Models\Variation', 'variation_group', 'group_id', 'variation_id')
            ->join('products_groups', 'products_groups.group_id', '=', 'variation_group.group_id')
            ->where('products_groups.product_id', $this->id);  // Lọc theo product_id
    }

    // Quan hệ nhiều-nhiều với Topping thông qua ToppingGroup
    public function toppings()
    {
        // Quan hệ qua hai bảng trung gian: 'products_groups' và 'topping_group_link'
        return $this->belongsToMany('App\Models\Topping', 'toppings_group_link', 'group_id', 'topping_id')
            ->join('products_groups', 'products_groups.group_id', '=', 'toppings_group_link.group_id')
            ->where('products_groups.product_id', $this->id);  // Lọc theo product_id
    }

    // Phương thức tính trung bình rating
    public function averageRating()
    {
        // Tính trung bình rating
        return $this->rating()->avg('star') ?? 5;
    }


    static public function uploadAndResize($image, $width = 600, $height = 400)
    {
        if (empty($image)) return;
        $folder = "/images/products/";
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)) {
            \Storage::makeDirectory(config('filesystems.disks.public.visibility') . $folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.' . $fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder . $timestamp . '-' . $filename . '.' . $fileExt);

        $img = \Image::make($image->getRealPath())->encode('webp', 100)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public') . $pathImage);

        return config('filesystems.disks.public.path') . $pathImage;
    }

    static public function uploadFile($file, $folder = 'videos/products', $filename = null)
    {
        if (empty($file)) return;

        // Set the storage disk and path for the upload folder
        $folderPath = "/$folder/";

        // Create the folder if it does not exist
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folderPath)) {
            \Storage::makeDirectory(config('filesystems.disks.public.visibility') . $folderPath);
        }

        // Generate a unique filename if not provided
        if (!$filename) {
            $timestamp = Carbon::now()->toDateTimeString();
            $fileExt = $file->getClientOriginalExtension();
            $filename = str_slug(basename($file->getClientOriginalName(), '.' . $fileExt));
            $filename = $timestamp . '-' . $filename . '.' . $fileExt;
        }

        // Define the file path
        $path = $folderPath . $filename;

        // Save the file to the storage
        \Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        // Return the public path to the uploaded file
        return config('filesystems.disks.public.url') . $path;
    }


    static function getCodeUnique($length = 6)
    {
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        do {
            $barcode = "";
            for ($i = 0; $i < $length; $i++) {
                $barcode .= $codeAlphabet[random_int(0, $max - 1)];
            }
        } while (self::where('barcode', $barcode)->count() != 0);
        return $barcode;
    }


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->barcode = self::getCodeUnique();
            $model->creator_id = \Auth::id();
            $model->slug = str_slug($model->name_en);
            $model->created_at = now();
            $model->updated_at = now();
        });

        self::saving(function ($model) {
            $model->slug = str_slug($model->name_en);
            $model->updated_at = now();
        });
    }


}
