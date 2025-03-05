<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{

    protected $table = 'cart_items';

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'variations' => 'array',
        'toppings' => 'array',
        'price' => 'double',
        'cart_id' => 'integer',
        'quantity' => 'integer',
        'product_id' => 'product_id',
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id', 'product_id', 'price', 'variations', 'toppings', 'quantity', 'created_at', 'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
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
