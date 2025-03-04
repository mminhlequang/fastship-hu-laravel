<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{

    protected $table = 'customers_profile';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'content', 'arrange'
    ];

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'contacts' => 'array',
        'sex' => 'integer',
        'is_tax_code' => 'integer',
        'car_id' => 'integer',
        'payment_method' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\CustomerCar', 'car_id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method');
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
