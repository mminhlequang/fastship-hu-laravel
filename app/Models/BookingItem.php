<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'booking_items';

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
    protected $fillable = ['quantity', 'product_id', 'booking_id'];

    // public function producttop()
    // {
    //     return $this->belongsTo('App\Product','product_id');
    // }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}
