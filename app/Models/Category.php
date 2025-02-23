<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "categories";

    protected $sortable = [
        'title',
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
    protected $fillable = ['name','slug','avatar','description','active','parent_id'];

    public function parent(){
        return $this->belongsTo('App\Models\Category','parent_id');
    }

    public function news(){
        return $this->hasMany('App\Models\News');
    }

    public static function getListCategoryToArray($categories, $parent_id = '', $level = '', $result = []){
        global $result;
        $locale = app()->getLocale();
        foreach ( $categories as $key => $item){
            if ($item['parent_id'] == $parent_id){
                $result[$item->id] = $level. $item['title_'.$locale];
                unset($categories[$key]);
                self::getListCategoryToArray($categories, $item['id'], $level.'-- ', $result);
            }
        }
        return $result;
    }

    public function showListCategories($categories, $parent_id = '', $level = ''){
        foreach ($categories as $key => $item){
            if ($item['parent_id'] == $parent_id){
                echo '<tr>';
                echo '<td>'.$level.$item['name'].'</td>';
                echo '<td>'.$item['slug'].'</td>';
                echo '<td>'.Str::limit($item['description'], 50).'</td>';
                echo '<td class="text-center">'.(($item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '')).'</td>';
                echo '<td class="text-center">'.Carbon::parse($item->updated_at)->format(config('settings.format.date')).'</td>';
                echo '<td class="text-center">';
                if(\Auth::user()->can("CategoryController@show"))
                {
                    echo '<a href="'.url('/admin/categories/' . $item->id) .'" title="'.trans("message.view").'"><button class="btn btn-info btn-xs" style="margin-right: 5px;"><i class="fa fa-eye" aria-hidden="true"></i></button></a>';
                }

                if(\Auth::user()->can("CategoryController@update"))
                {
                    echo '<a href="'.url('/admin/categories/' . $item->id . '/edit').'" title="'.trans("message.edit").'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>';
                }
                echo '</td>';
                echo '</tr>';
                unset($categories[$key]);
                $this->showListCategories($categories, $item['id'], $level.'-- ');
            }
        }
    }

   
    public static function getCategories($treeCategories){
        $categories = self::getListCategoryToArray($treeCategories);
        $categories = Arr::prepend(!empty($categories) ? $categories : [], __('message.please_select'), '');
        return $categories;
    }

    static public function uploadAndResize($image, $width = 450, $height = null){
        if(empty($image)) return;
        $folder = "/images/categories/";
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.'.$fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder.$timestamp. '-' .$filename.'.'.$fileExt);

        $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public').$pathImage);

        return config('filesystems.disks.public.path').$pathImage;
    }
}
