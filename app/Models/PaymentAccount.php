<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{

    protected $table = 'payment_accounts';

    // Cast attributes JSON to array
    protected $casts = [
        'account_id' => 'integer',
        'is_verified' => 'integer',
        'is_default' => 'integer',
        'payment_wallet_provider_id' => 'integer',
    ];


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'account_type', 'account_number', 'account_name',
        'bank_name', 'payment_wallet_provider_id', 'currency', 'is_verified', 'is_default'
    ];

    public function payment_wallet()
    {
        return $this->belongsTo('App\Models\PaymentWallet', 'payment_wallet_provider_id');
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
