<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerImage extends Model
{

    protected $table = 'customers_images';

    protected $fillable = ['image', 'type', 'created_at', 'updated_at'];

     public static function boot()
     {
         parent::boot();
         self::creating(function ($model) {
             $model->created_at = now();
             $model->updated_at = now();
         });
     }
}
