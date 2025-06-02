<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $table = 'wallets';

    protected $fillable = ['user_id', 'balance', 'frozen_balance', 'currency'];

    // Cast attributes JSON to array
    protected $casts = [
        'balance' => 'float',
        'frozen_balance' => 'float',
    ];

    // Lấy ví hệ thống (id = 0)
    public static function getSystemWallet()
    {
        return self::where('user_id', 0)->first();
    }

    public static function getWalletId($userId, $currency = 'HUF')
    {
        return \DB::table('wallets')->where('user_id', $userId)->where('currency', $currency)->value('id');
    }

    public static function getOrCreateWallet($userId, $currency = 'HUF')
    {
        // Tìm ví theo user_id và currency, nếu không có thì tạo mới
        return self::firstOrCreate(
            ['user_id' => $userId, 'currency' => $currency],
            ['balance' => 0, 'frozen_balance' => 0]
        );
    }


    // Cập nhật số dư ví
    public function updateBalance($amount)
    {
        $this->balance += $amount;
        $this->save();
    }


}
