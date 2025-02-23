<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistrictController extends Controller
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

        $province_id = $request->get('province_id') ?? 0;


        $provinces = \DB::table('provinces')->pluck('name', 'id');
        $provinces = $provinces->prepend("--Chọn tỉnh --", '');

        $data = District::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%");
        })->when($province_id != 0, function ($query) use ($province_id) {
            $query->where('province_id', $province_id);
        });

        $data = $data->sortable(['updated_at' => 'desc'])->paginate($perPage);

        return view('admin.districts.index', compact('keywords', 'locale', 'data', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $provinces = \DB::table('provinces')->pluck('name', 'id');
        $provinces = $provinces->prepend("--Chọn tỉnh --", '');

        return view('admin.districts.create', compact('provinces'));
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
        District::create($requestData);

        toastr()->success(__('Dữ liệu đã được tạo'));
        return redirect('admin/districts');
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
        $data = District::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.districts.show', compact('data', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $provinces = \DB::table('provinces')->pluck('name', 'id');
        $provinces = $provinces->prepend("--Chọn tỉnh --", '');

        $locale = app()->getLocale();
        $data = District::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.districts.edit', compact('data', 'backUrl', 'locale', 'provinces'));
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
        $data = District::findOrFail($id);
        $requestData = $request->all();

        $data->update($requestData);
        toastr()->success(__('Dữ lệu cập nhật thành công'));


        return redirect('admin/districts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        District::destroy($id);
        toastr()->success(__('Dữ liệu đã xóa'));
        return redirect('admin/districts');
    }
}
