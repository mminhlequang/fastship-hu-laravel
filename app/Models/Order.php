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
    protected $fillable = ['code', 'note', 'cancel_note', 'discount_id',  'user_id', 'address_id', 'approve_id', 'payment_type', 'creator_id', 'total_price', 'payment_method', 'currency', 'payment_intent_id', 'payment_status',
        'store_id', 'driver_id'
        ];


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

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
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
