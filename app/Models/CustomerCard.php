<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCard extends Model
{

    protected $table = 'customers_cards';

    protected $fillable = ['card_holder_name', 'card_brand', 'gateway', 'card_exp_month',
        'card_exp_year', 'card_last4', 'set_as_default', 'fingerprint'];

    protected $casts = [
        'card_exp_month' => 'integer',
        'card_exp_year' => 'integer',
        'set_as_default' => 'integer'
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
