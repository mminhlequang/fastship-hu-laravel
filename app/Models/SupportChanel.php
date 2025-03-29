<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportChanel extends Model
{

    protected $table = 'support_channels';

    protected $casts = [
        'type' => 'string',
        'is_for_driver' => 'integer',
        'is_for_partner' => 'integer',
        'is_for_customer' => 'integer',
        'arrange' => 'integer',
    ];
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'url', 'phone_number', 'icon', 'arrange',
        'is_for_driver', 'is_for_partner', 'is_for_customer'
    ];



}
