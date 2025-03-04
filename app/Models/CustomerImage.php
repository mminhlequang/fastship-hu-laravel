<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerImage extends Model
{

    protected $table = 'customers_images';

    protected $fillable = ['image', 'type'];

     public static function boot()
     {
         parent::boot();
         self::creating(function ($model) {
             $model->created_at = now();
             $model->updated_at = now();
         });
     }
}
