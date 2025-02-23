<?php

namespace App\Models;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use Sortable;
    protected $table = 'provinces';

    public $sortable = [
        'id',
        'name',
        'updated_at'
    ];

    protected $fillable = ['id','name','gso_id','created_at','updated_at'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

}
