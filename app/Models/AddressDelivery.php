<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AddressDelivery extends Model
{
    use HasFactory, Sortable;

    protected $table = "address_delivery";

    protected $sortable = [
        'name',
        'updated_at'
    ];
    // Cast attributes JSON to array
    protected $casts = [
        'is_default' => 'integer'
    ];

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'phone', 'address', 'is_default', 'customer_id',
        'phone', 'street', 'zip', 'city', 'state', 'country', 'country_code', 'lat', 'lng', 'deleted_at'
        ];


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
