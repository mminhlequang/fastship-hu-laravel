<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    protected $table = 'driver_teams';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'logo_url', 'description', 'created_at', 'updated_at'
    ];

    public static $TYPE = [
        "" => "--Role--",
        "admin" => "Admin",
        "staff" => "Staff",
    ];


    static public function uploadAndResize($image, $width = 600, $height = null)
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

    public function members()
    {
        return $this->hasMany('App\Models\Customer', 'driver_team_id');
    }

    public function drivers()
    {
        return $this->belongsToMany(
            \App\Models\Customer::class,    // Hoặc Driver::class nếu bạn có model Driver riêng
            'driver_teams_members',         // Tên bảng trung gian
            'team_id',                      // Khóa ngoại trên bảng trung gian trỏ tới Team
            'driver_id'                     // Khóa ngoại trên bảng trung gian trỏ tới Customer/Driver
        )->withPivot('role');
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
