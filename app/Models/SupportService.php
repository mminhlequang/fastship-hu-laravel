<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportService extends Model
{

    protected $table = 'support_service';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icon_url', 'name', 'description', 'is_active', 'is_store_register'
    ];

}
