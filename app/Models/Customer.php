<?php

namespace App\Models;


use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Image;
use Firebase\JWT\JWT;

class Customer extends Authenticatable

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

    protected $fillable = ['name', 'email', 'phone', 'address', 'sex', 'avatar', 'birthday', 'device_token', 'province_id', 'district_id', 'ward_id', 'latitude', 'longitude'];

    public function getTextGenderAttribute()
    {
        return $this->gender === 1 ? __('message.user.gender_male') : ($this->gender === 0 ? __('message.user.gender_female') : "");
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


    static public function uploadAndResize($image, $width = 1349, $height = null) {
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


    public static function getAuthorizationUser($request)
    {
        try {
            JWT::$leeway = 60;
            $jwt = $request->bearerToken();

            $user = self::whereNull('deleted_at')->where('token', $jwt)->first();

            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function generateToken($user)
    {
        try {
            $secret_key = "";
            $issuer_claim = "FastShip";
            $audience_claim = "fastship@gmail.com";
            $issuedate_claim = time();
            $notbefore_claim = $issuedate_claim + 1;
            $expire_claim =  time() + (10 * 365 * 24 * 60 * 60);
            $jwt = null;
            $tokenData = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedate_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $user["id"],
                    "name" => $user["name"],
                    "phone" => $user["phone"],
                    "password" => $user["password"]
                ),
            );
            $jwt = JWT::encode($tokenData, $secret_key, 'HS256');
            $user->update(['token' => $jwt]);
            return $jwt;
        } catch (\Exception $e) {
            return false;
        }
    }


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = Carbon::now();
            $model->updated_at = Carbon::now();
        });
    }

}
