<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreWallet extends Model
{

    protected $table = 'wallets';

    protected $fillable = [ 'store_id', 'balance', 'frozen_balance'];

    protected $casts = [
        'balance' => 'float',
        'frozen_balance' => 'frozen_balance'
    ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    // Cập nhật số dư ví cửa hàng
    public function updateBalance($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

}
