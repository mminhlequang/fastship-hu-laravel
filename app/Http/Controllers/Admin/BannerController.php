<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use App\Models\Banner;
use RealRashid\SweetAlert\Facades\Alert;

class BannerController extends Controller
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

        $locale = app()->getLocale();
        $banners = new Banner();
        if (!empty($keyword)) {
            $banners = $banners->where('name', 'LIKE', "%$keyword%");
        }
        $banners = $banners->sortable(['updated_at' => 'desc'])->paginate($perPage);

        return view('admin.banners.index', compact('banners','locale'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrange = Banner::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;
        return view('admin.banners.create', compact('arrange'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   
        $this->validate(
            $request,
            [
                'name' => 'required|unique:banners,name',
            ],
            [
                'name.required' => ' Tên banner không được để trống !',
                'name.unique' => ' Tên banner đã tồn tại',

            ]
        );
        $requestData = $request->all();
        \DB::transaction(function () use ($request, $requestData) {      
            if (!empty($request->hasFile('image'))) {          
                $requestData['image'] = Banner::uploadAndResize($request->file('image'));        
            }
            Banner::create($requestData);

        });
        alert()->success(__('settings.created_success'));

        return redirect('admin/banners');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banners = Banner::findOrFail($id);
        return view('admin.banners.show', compact('banners'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        $banners = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banners'));
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
        $this->validate(
            $request,
            [
                'name' => 'required',
            ],
            [
                'name.required' => ' Tên banner không được để trống !',
            ]
        );
        $banners = Banner::findOrFail($id);
        $requestData = $request->all();
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }
        \DB::transaction(function () use ($request, $requestData, $banners){
            if($request->hasFile('image')) {
				\Storage::delete($banners->image);
                $requestData['image'] = Banner::uploadAndResize($request->file('image'));
            }
            $banners->update($requestData);
        });
        Alert::success(__('settings.updated_success'));
        return redirect('admin/banners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Banner::destroy($id);

        alert()->success(__('settings.deleted_success'));
        return redirect('/admin/banners');
    }
}
