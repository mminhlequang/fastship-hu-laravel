<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Order extends Model
{
    use Sortable;

    public $sortable = [
        'id',
        'updated_at',
        'approved',
        'created_at',
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'note', 'cancel_note', 'voucher_id', 'user_id', 'approve_id', 'payment_type', 'creator_id', 'total_price', 'payment_method', 'currency', 'payment_intent_id', 'payment_status',
        'store_id', 'driver_id', 'fee', 'voucher_value',
        'payment_id', 'price_tip', 'phone','address', 'lat', 'lng','street', 'zip', 'city', 'state', 'country', 'country_code',
        'payment_date'
    ];

    protected $casts = [
        'total_price' => 'float',
        'price_tip' => 'float',
        'fee' => 'float',
        'lat' => 'float',
        'lng' => 'float',
        'voucher_value' => 'float'
    ];

    public function payment()
    {
        return $this->belongsTo('App\Models\PaymentWallet', 'payment_id');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\AddressDelivery');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Customer', 'driver_id');
    }

    public function approve()
    {
        return $this->belongsTo('App\Models\Approve', 'approve_id');
    }

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Models\Discount', 'voucher_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    public function address_delivery()
    {
        return $this->belongsTo('App\Models\AddressDelivery', 'address_delivery_id');
    }

    static function getCodeUnique($length = 10)
    {
        $codeAlphabet = "0123456789";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        do {
            $code = "#";
            for ($i = 0; $i < $length; $i++) {
                $code .= $codeAlphabet[random_int(0, $max - 1)];
            }
        } while (self::where('code', $code)->count() != 0);

        return $code;
    }

    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Đơn vị: km

        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Đơn vị km

        // Add 5 minutes for every 1 km of distance
        $timeToAdd = $distance * 10; // 5 minutes per km

        // Round the time to add to the nearest integer
        $timeToAdd = round($timeToAdd);

        return [
            'distance_km' => $distance,   // Distance in kilometers
            'time_added_minutes' => $timeToAdd  // Time to be added in minutes
        ];
    }

    public static function getShippingFee($distance)
    {
        try {
            $baseFee = \DB::table('settings')->where('key', 'fee_base')->value('value') ?? 10000; // Phí cố định
            $feePerKm = \DB::table('settings')->where('key', 'fee_km')->value('value') ?? 5000;
            return $baseFee + ($distance * $feePerKm);
        } catch (\Exception $e) {
            return 0;
        }
    }


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->code = self::getCodeUnique();
            $model->creator_id = \Auth::id();
        });
        self::deleted(function ($modal) {
        });
    }

}
