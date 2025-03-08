<?php

namespace App\Models;

use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use Sortable;

    protected $table = 'wallet_transactions';

    public $sortable = [
        'id',
        'updated_at'
    ];

    protected $fillable = ['id', 'user_id', 'price', 'payment_method', 'order_id', 'transaction_date', 'description', 'status', 'active', 'type',  'code',
        'transaction_id', 'metadata', 'wallet_id', 'base_price', 'currency', 'transaction_type', 'created_at', 'updated_at'
    ];

    public static $TYPE = [
        "" => "--Choose Type--", // Default option
        "deposit" => 'Deposit', // Translation for type 1
        "withdrawal" => 'Withdrawal', // Translation for type 2
        "purchase" => 'Purchase', // Translation for type 2
    ];


    public static $STATUS = [
        "" => "--Choose status--",
        "pending" => "pending",
        "failed" => "failed",
        "cancelled" => "cancelled",
        "completed" => "completed",
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Booking', 'order_id');
    }

    static function getCodeUnique($length = 12)
    {
        $codeAlphabet = "0123456789";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        do {
            $code = "##";
            for ($i = 0; $i < $length; $i++) {
                $code .= $codeAlphabet[random_int(0, $max - 1)];
            }
        } while (self::where('code', $code)->count() != 0);

        return $code;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code = self::getCodeUnique();
            $model->created_at = now();
            $model->updated_at = now();
        });

    }

}
