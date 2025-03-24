<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportServiceAdditional extends Model
{

    protected $table = 'support_service_additional';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'support_service_id', 'name', 'description'
    ];

}
