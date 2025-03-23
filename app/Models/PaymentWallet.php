<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWallet extends Model
{

    protected $table = 'payment_wallet_provider';

    // Cast attributes JSON to array
    protected $casts = [
        'is_active' => 'integer',
    ];


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'created_at', 'updated_at'
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
