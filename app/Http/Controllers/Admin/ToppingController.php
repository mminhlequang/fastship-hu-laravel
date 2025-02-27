<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Models\District;
use App\Models\Province;
use App\Models\Store;
use App\Models\Topping;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class ToppingController extends Controller
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

        $data = Topping::when($keywords != '', function ($query) use($keywords) {
            $query->where('name_vi', 'like', "%$keywords%");
        });

        $data = $data->whereNull('deleted_at')->latest()->paginate($perPage);

        return view('admin.toppings.index', compact('keywords', 'locale', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locale = app()->getLocale();
        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $groups = \DB::table('toppings_group')->whereNull('deleted_at')->pluck('name_'.$locale, 'id');
        $groups = $groups->prepend("--Choose group --", '');

        
        return view('admin.toppings.create', compact('stores', 'groups'));
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

        if($request->status) $requestData['status'] = 1;

        $requestData['price'] = (double)str_replace(',', '', $request->price);

        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image'))
                $requestData['image'] = Topping::uploadAndResize($request->file('image'));
            Topping::create($requestData);
        });

        toastr()->success(__('settings.created_success'));

        return redirect('admin/toppings');
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
        $data = Topping::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.toppings.show', compact('data', 'backUrl', 'locale'));
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
        $data = Topping::findOrFail($id);

        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $groups = \DB::table('toppings_group')->whereNull('deleted_at')->pluck('name_'.$locale, 'id');
        $groups = $groups->prepend("--Choose group --", '');

        
        $backUrl = $request->get('back_url');
        return view('admin.toppings.edit', compact('data', 'backUrl', 'locale', 'stores','groups'));
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
  
        $data = Topping::findOrFail($id);

        $requestData = $request->all();

        if(!empty($request->status))
            $requestData['status'] = 1;
        else
            $requestData['status'] = 0;

        $requestData['price'] = (double)str_replace(',', '', $request->price);


        \DB::transaction(function () use ($request, $requestData, $data) {
            if ($request->hasFile('image')) {
                \File::delete($data->image);
                $requestData['image'] = Topping::uploadAndResize($request->file('image'));
            }
            $data->update($requestData);
        });

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/toppings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::table('toppings')->where('id', $id)->update([
            'deleted_at' => now(),
        ]);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/toppings');
    }
}
