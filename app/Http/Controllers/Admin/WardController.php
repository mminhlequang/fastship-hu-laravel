<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keywords = $request->get('search');
        $perPage = config('settings.perpage');
        $locale = app()->getLocale();

        $district_id = $request->get('district_id') ?? 0;


        $districts = \DB::table('districts')->pluck('name', 'id');
        $districts = $districts->prepend("--Chọn huyện --", '');

        $data = Ward::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%");
        })->when($district_id != 0, function ($query) use ($district_id) {
            $query->where('district_id', $district_id);
        });

        $data = $data->sortable(['updated_at' => 'desc'])->paginate($perPage);

        return view('admin.wards.index', compact('keywords', 'locale', 'data', 'districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $districts = \DB::table('districts')->pluck('name', 'id');
        $districts = $districts->prepend("--Chọn huyện --", '');

        return view('admin.wards.create', compact('districts'));
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
        Ward::create($requestData);

        toastr()->success(__('Dữ liệu đã được tạo'));
        return redirect('admin/wards');
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
        $data = Ward::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.wards.show', compact('data', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $districts = \DB::table('districts')->pluck('name', 'id');
        $districts = $districts->prepend("--Chọn huyện --", '');


        $locale = app()->getLocale();
        $data = Ward::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.wards.edit', compact('data', 'backUrl', 'locale', 'districts'));
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
        $data = Ward::findOrFail($id);
        $requestData = $request->all();

        $data->update($requestData);
        toastr()->success(__('Dữ lệu cập nhật thành công'));


        return redirect('admin/wards');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ward::destroy($id);
        toastr()->success(__('Dữ liệu đã xóa'));
        return redirect('admin/wards');
    }
}
