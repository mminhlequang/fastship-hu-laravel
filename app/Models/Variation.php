<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{

    protected $table = 'variations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_vi', 'name_en', 'name_zh', 'name_hu', 'store_id', 'creator_id', 'created_at', 'updated_at'
    ];

    public function values()
    {
        return $this->hasMany(VariationValue::class);
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
