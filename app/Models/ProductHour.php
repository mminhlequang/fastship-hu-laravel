<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHour extends Model
{

    protected $table = 'products_hours';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'day', 'start_time', 'end_time', 'is_off'
    ];

    protected $casts = [
        'product_id' => 'integer',
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
