<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class ProductRatingImage extends Model
{
    use Sortable;

    protected $table = 'products_rating';

    public $sortable = [
        'id',
    ];

    public function rating()
    {
        return $this->belongsTo('App\Models\ProductRating', 'rating_id');
    }

}
