<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Models\District;
use App\Models\Province;
use App\Models\Store;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class StoreController extends Controller
{
    use Authorizable;
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

        $stores = \DB::table('stores')->where('active', 1)->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $data = Store::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%");
        });

        $data = $data->whereNull('deleted_at')->latest()->paginate($perPage);

        return view('admin.stores.index', compact('keywords', 'locale', 'data', 'stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.stores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:stores,name',
        ]);

        $requestData = $request->all();

        if($request->active) $requestData['active'] = 1;

        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('avatar_image'))
                $requestData['avatar_image'] = Store::uploadAndResize($request->file('avatar_image'));
            if ($request->hasFile('facade_image'))
                $requestData['facade_image'] = Store::uploadAndResize($request->file('facade_image'));
            if ($request->hasFile('contact_card_id_image_front'))
                $requestData['contact_card_id_image_front'] = Store::uploadAndResize($request->file('contact_card_id_image_front'));
            if ($request->hasFile('contact_card_id_image_back'))
                $requestData['contact_card_id_image_back'] = Store::uploadAndResize($request->file('contact_card_id_image_back'));
            Store::create($requestData);
        });

        toastr()->success(__('settings.created_success'));

        return redirect('admin/stores');
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
        $data = Store::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.stores.show', compact('data', 'backUrl', 'locale'));
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
        $data = Store::findOrFail($id);

        $backUrl = $request->get('back_url');
        return view('admin.stores.edit', compact('data', 'backUrl', 'locale'));
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
        $request->validate([
            'name' => 'required|max:255|unique:stores,name,'.$id,
        ]);

        $data = Store::findOrFail($id);

        $requestData = $request->all();

        if(!empty($request->active)) 
            $requestData['active'] = 1;
        else
            $requestData['active'] = 0;


        \DB::transaction(function () use ($request, $requestData, $data) {
            if ($request->hasFile('avatar_image')) {
                \File::delete($data->avatar_image);
                $requestData['avatar_image'] = Store::uploadAndResize($request->file('avatar_image'));
            }

            if ($request->hasFile('facade_image')) {
                \File::delete($data->facade_image);
                $requestData['facade_image'] = Store::uploadAndResize($request->file('facade_image'));
            }

            if ($request->hasFile('contact_card_id_image_front')) {
                \File::delete($data->contact_card_id_image_front);
                $requestData['contact_card_id_image_front'] = Store::uploadAndResize($request->file('contact_card_id_image_front'));
            }

            if ($request->hasFile('contact_card_id_image_back')) {
                \File::delete($data->contact_card_id_image_back);
                $requestData['contact_card_id_image_back'] = Store::uploadAndResize($request->file('contact_card_id_image_back'));
            }

            $data->update($requestData);
        });

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/stores');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::table('stores')->where('id', $id)->update([
            'deleted_at' => now(),
        ]);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/stores');
    }
}
