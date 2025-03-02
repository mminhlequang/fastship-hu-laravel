<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreRatingReply extends Model
{

    protected $table = 'stores_rating_reply';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating_id', 'user_id', 'content'
    ];


    // Cast attributes JSON to array
    protected $casts = [
        'content' => 'string',
    ];


    public function creator()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
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
