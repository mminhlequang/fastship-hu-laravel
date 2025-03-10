<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders_items';

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
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'product', 'variations', 'toppings'];


    protected $casts = [
        'product' => 'array',
        'variations' => 'array',
        'toppings' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}
