<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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
        'active' => 'integer',
    ];

    protected $fillable = ['id', 'name', 'creator_id', 'address', 'active',
        'phone', 'street', 'zip', 'city', 'state', 'country', 'country_code', 'lat', 'lng',
        'created_at', 'updated_at',
        'support_service_id', 'support_service_additional_ids', 'business_type_ids',
        'contact_type', 'contact_full_name', 'contact_company', 'contact_company_address', 'contact_phone', 'contact_email', 'contact_card_id', 'contact_card_id_issue_date', 'contact_card_id_image_front', 'contact_card_id_image_back', 'contact_image_license',
        'contact_tax', 'avatar_image', 'facade_image', 'slug'
    ];

    public function service()
    {
        return $this->belongsTo('App\Models\SupportService', 'support_service_id');
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

    public function documents()
    {
        return $this->hasMany('App\Models\StoreDocument', 'store_id');
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

    public function rating()
    {
        return $this->hasMany('App\Models\StoreRating', 'store_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'store_id')->whereNull('deleted_at');
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
        if (empty($file)) return null;

        // Generate timestamp and filename
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s'); // Better format for filenames
        $fileExt = $file->getClientOriginalExtension();
        $originalName = basename($file->getClientOriginalName(), '.' . $fileExt);
        $filename = $filename ?: str_slug($originalName);

        // Define the file name
        $fileName = "{$timestamp}-{$filename}.{$fileExt}";

        // Store the file
        $path = $file->storeAs($folder, $fileName, 'public');

        // Return the full public URL
//        return Storage::disk('public')->url($path);
        return 'storage/' . $path;
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

    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        if(empty($lat1) || empty($lat2)) return 0;
        
        $earthRadius = 6371; // Đơn vị: km

        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Đơn vị km

        // Chuyển đổi từ km sang m
        $distanceInMeters = $distance * 1000;

        return round($distanceInMeters, 2); // Đơn vị: m

    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->slug = str_slug($model->name);
            $model->created_at = now();
            $model->updated_at = now();
        });

    }

}
