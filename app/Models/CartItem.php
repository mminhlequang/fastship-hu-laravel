<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{

    protected $table = 'cart_items';

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'product' => 'json',
        'variations' => 'json',
        'toppings' => 'json',
        'price' => 'double',
        'cart_id' => 'integer',
        'quantity' => 'integer',
        'product_id' => 'integer',
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id', 'product_id', 'price', 'product', 'variations', 'toppings', 'quantity', 'created_at', 'updated_at'
    ];


    public function cart()
    {
        return $this->belongsTo('App\Models\Cart', 'cart_id');
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
