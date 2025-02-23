<?php

namespace App\Http\Controllers\Admin;

use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
{
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
        $data = Province::select("*")->orderByRaw('id = 31 Desc');
        if (!empty($keyword))
            $data = $data->where('name', 'LIKE', "%$keyword%");

        $data = $data->sortable(['updated_at' => 'desc'])->paginate($perPage);

        return view('admin.provinces.index', compact('keyword', 'locale', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.provinces.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        Province::create($requestData);

        toastr()->success(__('Dữ liệu đã được tạo'));
        return redirect('admin/provinces');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $locale = app()->getLocale();
        $data = Province::findOrFail($id);
        $backUrl = $request->get('back_url');
        
        return view('admin.provinces.show', compact('data', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $locale = app()->getLocale();
        $data = Province::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.provinces.edit', compact('data', 'backUrl', 'locale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Province::findOrFail($id);
        $requestData = $request->all();

        $data->update($requestData);
        toastr()->success(__('Dữ lệu cập nhật thành công'));


        return redirect('admin/provinces');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Province::destroy($id);
        toastr()->success(__('Dữ liệu đã xóa'));
        return redirect('admin/provinces');
    }
}
