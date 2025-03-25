<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportService extends Model
{

    protected $table = 'support_service';

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'is_active' => 'integer',
        'is_store_register' => 'integer',
    ];
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icon_url', 'name', 'description', 'is_active', 'is_store_register'
    ];

    public function additionals()
    {
        return $this->hasMany('App\Models\SupportServiceAdditional', 'support_service_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\SupportService', 'support_service_id');
    }

}
