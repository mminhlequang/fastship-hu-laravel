<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryStore extends Model
{

    protected $table = 'categories_stores';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'category_id', 'user_id'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'category_id' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
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
