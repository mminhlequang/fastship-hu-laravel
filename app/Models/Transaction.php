<?php

namespace App\Models;

use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use Sortable;

    protected $table = 'transactions';

    public $sortable = [
        'id',
        'updated_at'
    ];

    protected $fillable = ['id', 'user_id', 'price',  'payment_method', 'order_id', 'transaction_date', 'description', 'status', 'active', 'type'];

    public static $TYPE = [
        "" => "--Choose Type--", // Default option
        "1" => 'Deposit', // Translation for type 1
        "2" => 'Purchase', // Translation for type 2
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

}
