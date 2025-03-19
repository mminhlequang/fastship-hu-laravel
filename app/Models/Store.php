<?php

namespace App\Models;

use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use Sortable;

    protected $table = 'stores';

    public $sortable = [
        'id',
        'name',
        'updated_at'
    ];

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'operating_hours' => 'array',
        'active' => 'integer',
        'fee' => 'double',
        'services' => 'array',
        'foods' => 'array',
        'products' => 'array',
    ];

    protected $fillable = ['id', 'name', 'image', 'creator_id', 'address', 'content', 'province_id', 'district_id', 'ward_id', 'active',
        'phone', 'street', 'zip', 'city', 'state', 'country', 'country_code', 'lat', 'lng', 'banner', 'operating_hours',
        'type', 'phone_other', 'phone_contact', 'email', 'cccd', 'cccd_date', 'image_cccd_before', 'image_cccd_after',
        'license', 'image_license', 'tax_code', 'service_id', 'services', 'foods', 'products', 'fee', 'image_tax_code', 'created_at', 'updated_at',
        'card_bank', 'card_number', 'card_holder_name'

    ];

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\Customer', 'creator_id');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    public function ward()
    {
        return $this->belongsTo('App\Models\Ward', 'ward_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\StoreImage', 'store_id');
    }

    public function rating()
    {
        return $this->hasMany('App\Models\StoreRating', 'store_id');
    }

    public function favorites()
    {
        return $this->belongsToMany('App\Models\Customer', 'stores_favorite', 'store_id', 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_stores');
    }


    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'store_id');
    }

    // Phương thức tính trung bình rating
    public function averageRating()
    {
        // Tính trung bình rating
        return (double)$this->rating()->avg('star') ?? 5;
    }

    public function hours()
    {
        return $this->hasMany('App\Models\StoreHour', 'store_id');
    }

    static public function uploadAndResize($image, $width = 1349, $height = null)
    {
        if (empty($image)) return;

        $folder = "/images/stores/";
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

    static public function uploadFile($file, $folder = 'videos/stores', $filename = null)
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

        // Kiểm tra xem giờ hiện tại có nằm trong khoảng giờ mở cửa không
        $startTime = $storeHour->start_time; // Giờ mở cửa
        $endTime = $storeHour->end_time;     // Giờ đóng cửa

        // Kiểm tra xem thời gian hiện tại có nằm trong khoảng giờ mở cửa hay không
        if ($currentTime >= $startTime && $currentTime <= $endTime) {
            return 1; // Cửa hàng đang mở
        }

        return 0; // Cửa hàng đóng cửa
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

            // Nếu mảng hours không trống, insert thời gian mở cửa
            $startTime = isset($hours[0]) ? $hours[0] : null; // Thời gian mở cửa
            $endTime = isset($hours[1]) ? $hours[1] : null;   // Thời gian đóng cửa

            // Insert vào bảng store_hours
            $this->hours()->create([
                'day' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);
        }
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
