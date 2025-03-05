<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationValue extends Model
{

    protected $table = 'variation_values';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'variation_id', 'value', 'price', 'created_at', 'updated_at'
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'price' => 'double',
    ];

    public function variation()
    {
        return $this->belongsTo('App\Models\Variation', 'variation_id');
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
