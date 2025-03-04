<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{

    protected $table = 'steps';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'content', 'content_pending', 'arrange'
    ];

    public static $STATUS = [
        "" => "--Choose status--",
        "cancel" => "cancel",
        "pending" => "pending",
        "completed" => "completed",
    ];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = now();
        });

    }

}
