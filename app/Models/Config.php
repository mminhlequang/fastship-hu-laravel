<?php

namespace App\Models;


use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Config extends Authenticatable

{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'configs';

    public $sortable = [
        'id',
    ];

    public static $TYPE = [
        "" => "--Kiểu dữ liệu--",
        "text" => "Text",
        "number" => "Number",
        "date" => "Date",
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

    protected $fillable = ['hotline', 'zalo', 'privacy', 'about'];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {

        });
    }



}
