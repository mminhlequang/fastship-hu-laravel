<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use Sortable;
    protected $table = 'districts';

    public $sortable = [
        'id',
        'name'
    ];

    protected $fillable = ['id', 'name', 'gso_id', 'created_at', 'updated_at', 'province_id'];

    public function province()
    {
        return $this->belongsTo(Province::class, config('vietnam-maps.columns.province_id'), 'id');
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

}
