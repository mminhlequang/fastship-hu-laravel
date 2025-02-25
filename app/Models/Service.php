<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Sortable;

    protected $table = 'services';

    public $sortable = [
        'id',
        'name',
        'updated_at'
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'type' => 'integer',
        'price' => 'integer',
        'time' => 'integer',
        'unit' => 'integer'
    ];


}
