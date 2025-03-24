<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportBusiness extends Model
{

    protected $table = 'business_type';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'support_service_id', 'name', 'description', 'is_active'
    ];

}
