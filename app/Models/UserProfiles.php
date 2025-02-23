<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Image;

class UserProfiles extends Model
{
    use BaseModel;
    protected static $recordEvents = ['created', 'updated'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    /**
     * Get the user record associated with the profile
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo("App/Models/User");
    }

    /**
     * Upload and reszie - return pathfile
     * @param $avatar
     * @return string
     */
    public function uploadAndResizeAvatar($avatar){
        if(empty($avatar)) return;
        //\Storage::makeDirectory("public/demo");
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has(Config("settings.public_avatar"))){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').Config("settings.public_avatar"));
        }
        //getting timestamp
        $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
        $pathAvatar = Config("settings.public_avatar").$timestamp. '-' .$avatar->getClientOriginalName();
        Image::make($avatar->getRealPath())->resize(100, 100)->save(public_path('/storage').$pathAvatar);

        return config('filesystems.disks.public.visibility').$pathAvatar;
    }
}
