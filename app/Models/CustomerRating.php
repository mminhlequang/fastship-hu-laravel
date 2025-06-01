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

    public function user()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
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
