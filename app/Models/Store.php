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

    // Phương thức tính trung bình rating
    public function averageRating()
    {
        // Tính trung bình rating
        return $this->rating()->avg('star') ?? 5;
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
    public static function isStoreOpen($storeId)
    {
        // Lấy thông tin giờ hoạt động của cửa hàng
        $storeHour = self::where('id', $storeId)->first();

        if (!$storeHour) {
            return false; // Nếu không tìm thấy thông tin cửa hàng, trả về false
        }

        // Lấy ngày hiện tại và chuyển sang số ngày trong tuần (1 = Chủ nhật, 7 = Thứ Bảy)
        $currentDay = Carbon::now()->dayOfWeek + 1; // 0 = Chủ nhật, 6 = Thứ Bảy, nên cộng thêm 1 để có 1 = Chủ nhật, 7 = Thứ Bảy

        // Lấy thời gian hiện tại
        $currentTime = Carbon::now()->format('H:i'); // Định dạng giờ và phút (HH:mm)

        // Kiểm tra giờ hoạt động của cửa hàng
        foreach ($storeHour->operating_hours as $day) {
            if ($day['day'] == $currentDay) {
                // Duyệt qua tất cả các khoảng thời gian hoạt động trong ngày
                foreach ($day['hours'] as $hour) {
                    // Chuyển startTime và endTime từ timestamp thành giờ phút
                    $startTime = Carbon::createFromTimestamp($hour['startTimeInDay'])->format('H:i');
                    $endTime = Carbon::createFromTimestamp($hour['endTimeInDay'])->format('H:i');

                    // Kiểm tra xem thời gian hiện tại có nằm trong khoảng thời gian hoạt động không
                    if ($currentTime >= $startTime && $currentTime <= $endTime) {
                        return true; // Cửa hàng đang mở
                    }
                }
            }
        }

        // Nếu không tìm thấy thời gian hợp lệ, cửa hàng không mở
        return false;
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
