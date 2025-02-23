<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SysMenu;
use App\Traits\Authorizable;
use Session;
use Illuminate\Support\Arr;

class SysMenuController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $menus = new SysMenu();
        if (!empty($keyword)){
            $menus = SysMenu::where('title','LIKE',"%$keyword%");
        }
        $menus = $menus->get();

        $typeMenu = SysMenu::getListTypePage();

        return view('admin.menus.index', compact('menus', 'typeMenu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $treeMenus = SysMenu::all();
        $menus = new SysMenu();
        $menus = $menus->getListMenuToArray($treeMenus);
        $menus = Arr::prepend(!empty($menus) ? $menus : [], __('message.please_select'), '');
        $menu_arrange = SysMenu::count('arrange');
        $position = config('sysmenu.position');
        $typeMenu = SysMenu::getListTypePage();
        return view('admin.menus.create', compact('menu_arrange','position', 'typeMenu','menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'slug' => 'nullable',
            'type_id' => 'required'
        ]);

        $requestData = $request->all();
        if (empty($request->get('slug'))){
            $requestData['slug'] = str_slug($requestData['title']);
        }
        $requestData['slug'] = SysMenu::setTrueMenuSlug($requestData['slug']);

        \DB::transaction(function () use ($requestData){
            if (!empty($requestData['position'])){
                $requestData['position'] = implode(',', $requestData['position']);
            }
            SysMenu::create($requestData);
        });
        toastr()->success(__('theme::menus.created_success'));
        return redirect('admin/menus');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = SysMenu::findOrFail($id);
        $typeMenu = SysMenu::getListTypePage();
        return view('admin.menus.show', compact('menu', 'typeMenu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $treeMenus = SysMenu::all();
        $menus = new SysMenu();
        $menus = $menus->getListMenuToArray($treeMenus);
        $menus = Arr::prepend(!empty($menus) ? $menus : [], __('message.please_select'), '');
        $menu = SysMenu::findOrFail($id);
        $position = config('sysmenu.position');
        $position_edit = explode(',',$menu->position);
        $typeMenu = SysMenu::getListTypePage();
        
        return view('admin.menus.edit', compact('menu', 'position', 'position_edit', 'typeMenu','menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = SysMenu::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))){
            $requestData['slug'] = str_slug($requestData['title']);
            $requestData['slug'] = SysMenu::setTrueMenuSlug($requestData['slug']);
        }

        \DB::transaction(function () use ($requestData, $menu){
            if (!empty($requestData['position'])){
                $requestData['position'] = implode(',', $requestData['position']);
            }
            $menu->update($requestData);
            
        });
        
        Session::flash('flash_message', __('theme::menus.updated_success'));

        return redirect('admin/menus');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SysMenu::destroy($id);
        
        Session::flash('flash_message', __('theme::menus.deleted_success'));

        return redirect('menus');
    }
}
