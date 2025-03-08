<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{

    protected $table = 'withdrawals';

    protected $fillable = [ 'wallet_id', 'user_id', 'amount', 'status', 'request_date', 'processed_date', 'currency', 'payment_method'];

    // Cast attributes JSON to array
    protected $casts = [
        'amount' => 'double',
        'wallet_id' => 'integer',
        'user_id' => 'user_id',
    ];


    public static $STATUS = [
        "" => "--Choose status--",
        "pending" => "pending",
        "reject" => "reject",
        "completed" => "completed",
    ];

}
