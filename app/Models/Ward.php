<?php

namespace App\Models;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use Sortable;
    protected $table = 'wards';

    public $sortable = [
        'id',
        'name'
    ];

    protected $fillable = ['id','name','gso_id','created_at','updated_at','district_id'];

    public function district()
    {
        return $this->belongsTo(District::class, config('vietnam-maps.columns.district_id'), 'id');
    }

}
