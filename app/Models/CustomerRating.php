<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{

    protected $table = 'customers_rating';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id', 'user_id', 'star'
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
