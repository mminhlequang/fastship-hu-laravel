<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStep extends Model
{

    protected $table = 'customers_steps';


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
       'step_id', 'user_id', 'comment', 'image', 'link', 'status'
    ];

    public function step()
    {
        return $this->belongsTo('App\Models\Step', 'step_id');
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
