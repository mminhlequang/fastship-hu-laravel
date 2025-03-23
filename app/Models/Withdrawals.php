<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{

    protected $table = 'withdrawals';

    protected $fillable = ['wallet_id', 'user_id', 'amount', 'status', 'request_date', 'processed_date', 'currency', 'payment_method', 'transaction_id',
        'payment_account_id', 'message_reason'
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'amount' => 'double',
        'wallet_id' => 'integer',
        'user_id' => 'integer',
        'payment_account_id' => 'integer',
    ];


    public static $STATUS = [
        "" => "--Choose status--",
        "pending" => "pending",
        "reject" => "reject",
        "completed" => "completed",
    ];

    public function user()
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
