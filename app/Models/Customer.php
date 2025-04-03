<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject

{
    use Sortable;


    public static $TYPE = [
        "" => "--Trạng thái--",
        "0" => "Chưa kích hoạt",
        "1" => "Đã kích hoạt"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    public $sortable = [
        'id',
        'name',
        'email',
        'phone',
        'created_at',
        'updated_at',
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'uid' => 'string',
        'active' => 'integer',
        'is_tax_code' => 'integer',
        'is_confirm' => 'integer',
        'sex' => 'integer',
        'type' => 'integer',
        'enabled_notify' => 'integer',
        'lat' => 'float',
        'lng' => 'float'
    ];

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'email', 'phone', 'password', 'address', 'sex', 'avatar', 'birthday', 'device_token', 'province_id', 'district_id', 'ward_id',
        'street', 'zip', 'city', 'state', 'country', 'country_code', 'lat', 'lng', 'deleted_request_at', 'note', 'is_confirm', 'token', 'type',
        'code_introduce', 'cccd', 'image_cmnd_before', 'image_cccd_after', 'uid',
        'tax_code', 'is_tax_code', 'image_license_before', 'image_license_after', 'car_id', 'enabled_notify', 'code'
    ];

    /**
     * Get the identifier that will be stored in the JWT claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Hoặc bạn có thể trả về ID của customer nếu bạn sử dụng khóa chính khác.
    }

    /**
     * Get custom claims to add to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'name' => $this->name,
            'phone' => $this->phone,
            'type' => $this->type
        ];
    }


    public function getTextGenderAttribute()
    {
        return $this->gender === 1 ? __('message.user.gender_male') : ($this->gender === 0 ? __('message.user.gender_female') : "");
    }

    public function card()
    {
        return $this->belongsTo('App\Models\CustomerCar', 'car_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\WalletTransaction', 'user_id');
    }

    public function wallet()
    {
        return $this->hasOne('App\Models\Wallet', 'user_id');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\CustomerProfile', 'user_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\CustomerImage', 'user_id');
    }

    public function rating()
    {
        return $this->hasMany('App\Models\CustomerRating', 'user_id');
    }

    public function steps()
    {
        return $this
            ->belongsToMany('App\Models\Step', 'customers_steps', 'user_id', 'step_id')->withPivot('id', 'comment', 'image', 'link', 'status')->orderBy('step_id'); // Sắp xếp theo step_i;
    }

    // Phương thức tính trung bình rating
    public function averageRating()
    {
        // Tính trung bình rating
        $average = $this->rating()->avg('star');

        // Nếu trung bình rating bằng 0, trả về 5
        return $average > 0 ? (double)$average : 5;
    }

    public function setPasswordAttribute($password)
    {
        // Mã hóa mật khẩu bằng bcrypt
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Hàm tính tổng tiền hiện có của người dùng từ các giao dịch
     *
     * @return float
     */
    public function getBalance($currency = 'eur')
    {
        return doubleval($this->wallet()->where('currency', $currency)->sum('balance') ?? 0);
    }

    /**
     * Hàm tính tổng tiền hiện có của người dùng từ các giao dịch
     *
     * @return float
     */
    public function getBalanceFrozen($currency = 'eur')
    {
        return doubleval($this->wallet()->where('currency', $currency)->sum('frozen_balance') ?? 0);
    }


    public static function convertPhoneNumber($phoneNumber)
    {
        // Kiểm tra nếu số điện thoại bắt đầu bằng '0' thì thay thế bằng '+84'
        if (substr($phoneNumber, 0, 1) === '0') {
            return '+84' . substr($phoneNumber, 1);
        }
        return $phoneNumber;
    }


    public static function getAuthorizationUser($request)
    {
        try {
            $user = auth()->user();
            if (empty($user)) return false;
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Show avatar
     * @return string|void
     */
    public function showAvatar($avatar)
    {
        if (isset($avatar)) {
            if (\Storage::exists($avatar))
                return '<img alt="avatar" width=40px; src="' . asset(\Storage::url($avatar)) . '" />';
        }
        return;
    }

    public static function getAvatarDefault($type = 1)
    {
        switch ($type == 1) {
            case 1:
                return 'images/user.png';
            case 2:
                return 'images.driver.png';
            default:
                return 'images/shop.png';
        }
    }


    static public function uploadAndResize($image, $width = 1349, $height = null)
    {
        if (empty($image)) return;

        $folder = "/images/customers/";
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


    public static function getCodeUnique()
    {
        $code = strtoupper(str_random(6));
        if (self::where('code', $code)->exists()) {
            $code = strtoupper(str_random(6));
        }
        return $code;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code = self::getCodeUnique();
            $model->active = 1;
            $model->created_at = Carbon::now();
            $model->updated_at = Carbon::now();
        });
    }

}
