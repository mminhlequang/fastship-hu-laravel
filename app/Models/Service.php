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

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_vi', 'description_vi', 'type', 'parent_id', 'arrange', 'deleted_at'
    ];

    // Cast attributes JSON to array
    protected $casts = [
        'type' => 'integer',
    ];

    public static $TYPE = [
        "" => "--Choose type--",
        "1" => "Type Service",
        "2" => "Service",
        "3" => "Food",
        "4" => "Product",
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Service', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Service', 'parent_id');
    }

    public static function getSelect($parent_id = null, $level = '', $result = [])
    {
        // Add the top-level option to the result array (e.g., "Please select" with an empty key)
        $result[''] = '-- Vui lòng chọn --';

        // Fetch all services where deleted_at is null (non-deleted)
        $services = \DB::table('services')->whereNull('deleted_at')->get();

        // Get the current locale
        $locale = app()->getLocale();

        // Iterate over the services
        foreach ($services as $key => $item) {
            // Check if the service is a child of the given parent
            if ($item->parent_id == $parent_id) {
                // Add the service to the result array, with the name prefixed by the appropriate level of indentation
                $result[$item->id] = $level . $item->{'name_' . $locale};

                // Remove the current service from the services array so it’s not processed again
                unset($services[$key]);

                // Recursively process the children of this service
                $result = self::getSelect($item->id, $level . '-- ', $result);
            }
        }

        // Return the result array
        return $result;
    }





    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = now();
        });

    }


}
