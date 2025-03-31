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
        'operating_hours' => 'array',
        'name' => 'string',
        'price' => 'float',
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
        'name', 'slug', 'image', 'description', 'content', 'active', 'price', 'price_compare',
        'category_id', 'creator_id', 'deleted_at', 'store_id', 'group_id',
        'status', 'available_into'
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
        return $this->belongsToMany('App\Models\Category', 'categories_products', 'product_id', 'category_id')
            ->withPivot(['arrange', 'store_id']);
    }

    public function orders()
    {
        return $this->hasMany('App\Models\OrderItem', 'product_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\ToppingGroup', 'products_groups', 'product_id', 'group_id');
    }

    public function variationsX()
    {
        return $this->belongsToMany('App\Models\Variation', 'variations_products', 'product_id', 'variation_id')
            ->where('is_active', 1)
            ->orderBy('arrange');  // Lọc theo product_id
    }

    public function variations()
    {
        return $this->belongsToMany('App\Models\Variation', 'variations_products', 'product_id', 'variation_id');  // Lọc theo product_id
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
        $average = $this->rating()->avg('star');

        // Nếu trung bình rating bằng 0, trả về 5
        return $average > 0 ? (double)$average : 5;
    }

    public function hours()
    {
        return $this->hasMany('App\Models\ProductHour', 'product_id');
    }

    /**
     * Kiểm tra xem cửa hàng có đang mở hay không.
     *
     * @param int $storeId
     * @return bool
     */
    public function isStoreOpen()
    {
        // Lấy thời gian hiện tại và ngày trong tuần
        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek + 1; // 1 = Chủ nhật, 2 = Thứ 2, ..., 7 = Thứ 7
        $currentTime = $now->format('H:i'); // Giờ hiện tại (ví dụ: "14:30")
        // Kiểm tra xem cửa hàng có thời gian làm việc cho ngày hôm nay không
        $storeHour = $this->hours()->where('day', $dayOfWeek)->first();
        if (!$storeHour) {
            // Nếu không có giờ làm việc cho ngày hôm nay, cửa hàng sẽ đóng
            return 0;
        }

        // Kiểm tra món có thời gian mở cụ thể không
        $availableInto = $this->available_into; // Lấy thời gian mở món
        if ($availableInto) {
            // Kiểm tra nếu thời gian hiện tại nhỏ hơn thời gian mở món
            if ($now->lessThan($availableInto)) {
                return 1;  // Món có thể mở
            }
            return 0; // Món đã đóng
        }

        // Kiểm tra xem giờ hiện tại có nằm trong khoảng giờ mở cửa không
        $startTime = $storeHour->start_time; // Giờ mở cửa
        $endTime = $storeHour->end_time;     // Giờ đóng cửa
        $isOff = $storeHour->is_off ?? 0;     // Giờ đóng cửa

        // Kiểm tra xem cửa hàng có đóng cửa hay không
        if ($isOff == 1) {
            return 0; // Cửa hàng đóng cửa (nghỉ)
        }

        // Kiểm tra xem thời gian hiện tại có nằm trong khoảng giờ mở cửa không
        if ($currentTime >= $startTime && $currentTime <= $endTime) {
            return 1; // Cửa hàng đang mở
        }

        return 0; // Cửa hàng đóng cửa
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

    // Hàm xử lý insert hoặc update giờ hoạt động
    public function updateStoreHours(array $hoursData)
    {
        // Xóa tất cả giờ hoạt động hiện tại của cửa hàng
        $this->hours()->delete();

        // Duyệt qua mảng hoursData và insert các bản ghi mới
        foreach ($hoursData as $data) {
            $day = $data['day'];
            $hours = $data['hours'];
            $isOff = $data['is_off'] ?? 0;

            // Nếu mảng hours không trống, insert thời gian mở cửa
            $startTime = isset($hours[0]) ? $hours[0] : null; // Thời gian mở cửa
            $endTime = isset($hours[1]) ? $hours[1] : null;   // Thời gian đóng cửa

            // Insert vào bảng store_hours
            $this->hours()->create([
                'day' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_off' => $isOff
            ]);
        }
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->slug = str_slug($model->name);
            $model->created_at = now();
            $model->updated_at = now();
        });

        self::saving(function ($model) {
            $model->slug = str_slug($model->name);
            $model->updated_at = now();
        });
    }


}
