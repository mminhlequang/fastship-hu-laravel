<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Language extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';
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
    protected $fillable = ['name', 'prefix'];
    public $sortable = [
        'name'
    ];

    static public function getListLanguage(){
        $languages = Language::pluck('name', 'id')->toArray();

        return $languages;
    }

    static public function getArrayLanguage(){
        $languages = Language::orderBy('created_at', 'ASC')->pluck('name', 'prefix');
        $datas = [];
        foreach ($languages as $id => $name){
            $datas[$id] = trans($name);
        }
        return $datas;
    }

    public static function getLanguages(){
        return Language::orderBy('created_at', 'ASC')->get();
    }
}
