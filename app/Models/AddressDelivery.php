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
    protected $fillable = ['name', 'province_id', 'phone', 'district_id', 'ward_id', 'address', 'is_default', 'customer_id',
        'phone', 'street', 'zip', 'city', 'state', 'country', 'country_code', 'lat', 'lng', 'deleted_at'
        ];

    public function districts()
    {
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    public function provinces()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }

    public function wards()
    {
        return $this->belongsTo('App\Models\Ward', 'ward_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
