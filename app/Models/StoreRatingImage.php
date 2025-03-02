<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class StoreRatingImage extends Model
{
    use Sortable;

    protected $table = 'stores_rating_images';

    public $sortable = [
        'id',
    ];

    public function rating()
    {
        return $this->belongsTo('App\Models\ProductRating', 'rating_id');
    }

}
