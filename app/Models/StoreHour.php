<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreHour extends Model
{

    protected $table = 'stores_hours';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'day', 'start_time', 'end_time', 'is_off'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'day' => 'integer',
        'is_off' => 'integer'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = now();
        });

    }

}
