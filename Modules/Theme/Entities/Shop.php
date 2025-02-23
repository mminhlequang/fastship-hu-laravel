<?php

namespace Modules\Theme\Entities;

use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "shops";

    protected $sortable = [
        'name',
        'updated_at'
    ];

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','address','phone', 'time', 'active','arrange'];
}
