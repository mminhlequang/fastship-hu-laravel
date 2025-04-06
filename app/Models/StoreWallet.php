<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreWallet extends Model
{

    protected $table = 'store_wallets';

    protected $fillable = [ 'store_id', 'balance', 'frozen_balance'];

    protected $casts = [
        'balance' => 'float',
        'frozen_balance' => 'float'
    ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    // Hàm lấy ví của cửa hàng (nếu không có thì tạo mới)
    public static function getStoreWallet($storeId)
    {
        // Tìm ví của cửa hàng dựa trên store_id
        $wallet = self::where('store_id', $storeId)->first();

        // Nếu ví không tồn tại, tạo mới ví cho cửa hàng
        if (!$wallet) {
            $wallet = self::create([
                'store_id' => $storeId,
                'balance' => 0,  // Khởi tạo số dư ví là 0
                'frozen_balance' => 0,
            ]);
        }

        return $wallet;  // Trả về ví của cửa hàng
    }

    // Cập nhật số dư ví cửa hàng
    public function updateBalance($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

}
