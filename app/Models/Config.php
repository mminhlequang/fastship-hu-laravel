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
    protected $table = 'configs_company';

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


    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $fillable = ['user_id', 'input', 'label', 'promotion_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function promotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'promotion_id');
    }
    public static function  convertText($text){
        return Str::slug($text, '_');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {

        });
    }



}
