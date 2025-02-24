<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Approve extends Model
{
    use Sortable;

    protected $table = 'approves';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $sortable = [
        'id',
        'name',
        'number',
        'color',
        'updated_at'
    ];

    protected $fillable = ['name', 'number', 'color','updated_at','created_at'];


}
