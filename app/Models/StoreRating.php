<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class StoreRating extends Model
{
    use Sortable;

    protected $table = 'stores_rating';

    public $sortable = [
        'id',
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'star' => 'integer',
    ];

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
    protected $fillable = [
        'store_id', 'user_id', 'star', 'content', 'active', 'order_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\StoreRatingReply', 'rating_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\StoreRatingImage', 'rating_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
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
