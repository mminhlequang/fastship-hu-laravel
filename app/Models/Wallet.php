<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $table = 'wallets';

    protected $fillable = [ 'user_id', 'balance', 'frozen_balance'];

    // Cast attributes JSON to array
    protected $casts = [
        'balance' => 'double',
        'frozen_balance' => 'double',
    ];

    public static function getWalletId($userId){
       return \DB::table('wallets')->where('user_id', $userId)->value('id');
    }


}
