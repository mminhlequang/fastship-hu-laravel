<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use Sortable;

    protected $table = 'products_rating';

    public $sortable = [
        'id',
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'star' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductRatingImage', 'rating_id');
    }

}
