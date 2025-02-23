<?php

namespace App\Models;


use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Image;

class Customer extends Authenticatable

{
    use Sortable;

    public static $TYPE = [
        "" => "--Trạng thái--",
        "0" => "Chưa kích hoạt",
        "1" => "Đã kích hoạt"
    ];

    public static $SHORT = [
        "" => "--Loại thống kê--",
        "1" => "Độ tuổi",
        "2" => "Địa bàn",
        "3" => "Chương trình",
        "4" => "Trạng thái",
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

    protected $fillable = ['name', 'email', 'phone', 'active', 'sex', 'avatar', 'input', 'promotion_id', 'address', 'birthday', 'province_id', 'district_id', 'ward_id'];

    public function getTextGenderAttribute()
    {
        return $this->gender === 1 ? __('message.user.gender_male') : ($this->gender === 0 ? __('message.user.gender_female') : "");
    }

    public function promotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'promotion_id');
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

    public function old()
    {
        return $this->belongsTo('App\Models\Old', 'old_id');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = Carbon::now();
            $model->updated_at = Carbon::now();
        });
    }

    // Hàm tính tuổi từ ngày sinh
    function calculateAge($dob)
    {
        return Carbon::parse($dob)->age;
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

    public static function uploadAndResizeAvatar($avatar)
    {
        if (empty($avatar)) return;
        //\Storage::makeDirectory("public/demo");
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has(Config("settings.public_avatar"))) {
            \Storage::makeDirectory(config('filesystems.disks.public.visibility') . Config("settings.public_avatar"));
        }
        //getting timestamp
        $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
        $pathAvatar = Config("settings.public_avatar") . $timestamp . '-' . $avatar->getClientOriginalName();
        Image::make($avatar->getRealPath())->resize(100, 100)->save(public_path('/storage') . $pathAvatar);

        return config('filesystems.disks.public.visibility') . $pathAvatar;
    }

}
