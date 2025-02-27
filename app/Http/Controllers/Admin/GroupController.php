<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Models\District;
use App\Models\Province;
use App\Models\Store;
use App\Models\Topping;
use App\Models\ToppingGroup;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class GroupController extends Controller
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

        $data = ToppingGroup::when($keywords != '', function ($query) use($keywords) {
            $query->where('name_vi', 'like', "%$keywords%");
        });

        $data = $data->whereNull('deleted_at')->latest()->paginate($perPage);

        return view('admin.groups.index', compact('keywords', 'locale', 'data'));
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

        $toppings = \DB::table('toppings')->whereNull('deleted_at')->pluck('name_'.$locale, 'id');

        return view('admin.groups.create', compact('stores', 'toppings'));
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

        \DB::transaction(function () use ($request, $requestData) {
            $data = ToppingGroup::create($requestData);

            // Lấy mảng topping_ids từ chuỗi
            if(!empty($request->topping_ids)){
                $toppingIds = explode(',', $request->topping_ids);
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($toppingIds as $toppingId) {
                    \DB::table('toppings_group_link')->insert([
                        'topping_id' => $toppingId,
                        'group_id' => $data->id,
                    ]);
                }
            }
        });

        toastr()->success(__('settings.created_success'));

        return redirect('admin/groups');
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
        $data = ToppingGroup::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.groups.show', compact('data', 'backUrl', 'locale'));
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
        $data = ToppingGroup::findOrFail($id);

        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $toppings = \DB::table('toppings')->whereNull('deleted_at')->pluck('name_'.$locale, 'id');

        $backUrl = $request->get('back_url');
        return view('admin.groups.edit', compact('data', 'backUrl', 'locale', 'stores','toppings'));
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

        $data = ToppingGroup::findOrFail($id);

        $requestData = $request->all();

        if(!empty($request->status))
            $requestData['status'] = 1;
        else
            $requestData['status'] = 0;


        // Lấy mảng topping_ids từ chuỗi
        if(!empty($request->topping_ids)){
            $toppingIds = explode(',', $request->topping_ids);
            // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
            foreach ($toppingIds as $toppingId) {
                // Kiểm tra xem cặp topping_id và group_id đã tồn tại chưa
                $exists = \DB::table('toppings_group_link')
                    ->where('topping_id', $toppingId)
                    ->where('group_id', $data->id)
                    ->exists(); // Trả về true nếu đã tồn tại, false nếu chưa có

                // Nếu chưa tồn tại, tiến hành insert
                if (!$exists) {
                    \DB::table('toppings_group_link')->insert([
                        'topping_id' => $toppingId,
                        'group_id' => $data->id,
                    ]);
                }
            }
        }

        \DB::transaction(function () use ($request, $requestData, $data) {

            $data->update($requestData);
        });

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::table('toppings_group')->where('id', $id)->update([
            'deleted_at' => now(),
        ]);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/groups');
    }
}
