<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class SysMenu extends Model
{
    use Sortable;
    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $sortable = [
        'title',
        'arrange',
        'updated_at'
    ];

    protected $fillable = ['type_id', 'title_vi', 'title_en', 'slug', 'position', 'arrange','parent_id','id'];

    public static function getListTypePage(){
        $arr = ['Trang danh mục', 'Trang chi tiết'];
        return $arr;
    }

    public function parent(){
        return $this->belongsTo('App\Models\SysMenu','parent_id');
    }

    public static function setTrueMenuSlug($slug){
        $locale = app()->getLocale();
        $menus = SysMenu::pluck('slug_'.$locale)->toArray();
        if (in_array($slug, $menus)){
            for ($i = 1; $i <= 10; $i++){
                $new_slug = $slug.'-'.$i;
                if (!in_array($new_slug, $menus)) break;
            }
            return $new_slug;
        }else{
            return $slug;
        }
    }

    public static function setTrueSlugVi($slug){
        $news = News::pluck('slug_vi')->toArray();
        $category_product = CategoryProduct::pluck('slug_vi')->toArray();
        $categories = Category::pluck('slug_vi')->toArray();
        $pages = Page::pluck('slug_vi')->toArray();

        $arr_slug = array_collapse([$news, $categories, $pages,$category_product]);

        if (in_array($slug, $arr_slug) || SysMenu::where('slug_vi', $slug)->exists()){
            for ($i = 1; $i <= 100; $i++){
                $new_slug = $slug.'-'.$i;
                if (!in_array($new_slug, $arr_slug)) break;
            }
            return $new_slug;
        }else{
            return $slug;
        }
    }
    public static function setTrueMenuSlugNew($slug){
        $menus = SysMenu::pluck('slug')->toArray();
        if (in_array($slug, $menus)){
            for ($i = 1; $i <= 10; $i++){
                $new_slug = $slug.'-'.$i;
                if (!in_array($new_slug, $menus)) break;
            }
            return $new_slug;
        }else{
            return $slug;
        }
    }
    public static function setTrueSlugEn($slug){
        $news = News::pluck('slug_en')->toArray();
        $category_product = CategoryProduct::pluck('slug_en')->toArray();
        $categories = Category::pluck('slug_en')->toArray();
        $pages = Page::pluck('slug_en')->toArray();

        $arr_slug = array_collapse([$news, $categories, $pages,$category_product]);

        if (in_array($slug, $arr_slug) || SysMenu::where('slug_en', $slug)->exists()){
            for ($i = 1; $i <= 100; $i++){
                $new_slug = $slug.'-'.$i;
                if (!in_array($new_slug, $arr_slug)) break;
            }
            return $new_slug;
        }else{
            return $slug;
        }
    }
    public function getListMenuToArray($menus, $menuId = null, $level = '', $result = [])
    {
        global $result;
        foreach ( $menus as $key => $item){
            if ($item['parent_id'] == $menuId){
                $result[$item->id] = $level. $item['title'];
                unset($menus[$key]);
                self::getListMenuToArray($menus, $item['id'], $level.'-- ', $result);
            }
        }
        return $result;
    }
    public function showListMenus($menus, $parent_id = '', $level = ''){
        $typeMenu = SysMenu::getListTypePage();
        foreach ($menus as $key => $item){
            if ($item['parent_id'] == $parent_id){
                echo '<tr>';
                echo '<td class="text-center"><input type="checkbox" name="chkId" id="chkId" value="'.$item->id.'" data-id="'.$item->id.'"/></td>';
                echo '<td>'.$level.$item['title'].'</td>';
                echo '<td>'.$item['slug'].'</td>';
                echo '<td>'.$item['arrange'].'</td>';
                echo '<td>'.$typeMenu[$item['type_id']].'</td>';
                echo '<td>'.Carbon::parse($item->updated_at)->format(config('settings.format.date')).'</td>';
                echo '<td class="text-center">';
                if(\Auth::user()->can("SysMenuController@show"))
                {
                    echo '<a href="'.url('admin/menus/' . $item->id) .'" title="'.trans("message.view").'"><button class="btn btn-info btn-xs" ><i class="fa fa-eye" aria-hidden="true"></i></button></a>';
                }
                if(\Auth::user()->can("SysMenuController@update"))
                {
                    echo '<a href="'.url('admin/menus/' . $item->id . '/edit').'" title="'.trans("message.edit").'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>';
                }
                if(\Auth::user()->can("SysMenuController@destroy"))
                {
                    echo '<form method="POST" action="'.url('admin/menus/'.$item['id']).'" style="display: inline">
                        <input type="hidden" name="_method" value="delete" />
                        <input class="_token" name="_token" type="hidden" value="'.csrf_token().'">
                        <button type="submit" class="com-delete btn btn-danger btn-xs" onclick="return confirm(&quot;Bạn chắc chắn muốn xoá phần tử này?&quot;)" title="Xóa"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </form>';
                    echo '</td>';
                }
                echo '</td>';
                echo '</tr>';
                unset($menus[$key]);
                $this->showListMenus($menus, $item['id'], $level.'-- ');
            }
        }
    }

}
