<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ward;
use App\Models\Province;
use App\Models\District;
use App\Models\Customer;
use App\Models\AddressDelivery;

use RealRashid\SweetAlert\Facades\Alert;

class AddressDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        // lay tat ca tinh
        $provinces = Province::select("*")
            ->orderByRaw('id = 31 Desc')->pluck('name', 'id');
        $provinces->prepend(__('--Chọn tỉnh--'), '')->all();
        //lấy tất cả loại hình kinh doanh
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');
        $locale = app()->getLocale();
        $province_id = $request->query('province_id');


        $address_delivery = AddressDelivery::when($keyword, function ($query, $keyword) {
            $query->where('name', 'like', "%$keyword%");
        })->when($province_id, function ($query) use ($province_id) {
            $query->where('province_id', $province_id);
        });

        $address_delivery = $address_delivery->whereNull('deleted_at')->latest()->paginate($perPage);
        return view('admin.address_delivery.index', compact('address_delivery', 'keyword', 'locale', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Lấy tất cả tỉnh
        $provinces = Province::all()->pluck('name', 'id');
        $provinces->prepend(__('--Chọn tỉnh--'), '')->all();
        //lấy tất cả loại hình kinh doanh
        $customers = Customer::all()->pluck('name', 'id');
        $customers->prepend(__('message.please_select'), '')->all();
        return view('admin.address_delivery.create', compact('provinces', 'customers'));
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

        if (!isset($request->is_default)) {
            $requestData["is_default"] = Config("settings.inactive");
        }

        $provincesName = Province::where('id', $requestData['province_id'])->first();
        $districtName = District::where('id', $requestData['district_id'])->first();
        $wardName = Ward::where('id', $requestData['ward_id'])->first();

        $requestData['address'] = $requestData['address'] . ',' . $wardName->name . ',' . $districtName->name . ',' . $provincesName->name;
        $data = AddressDelivery::create($requestData);
        $data->save();

        toastr()->success(__('settings.created_success'));

        return redirect('admin/address_delivery');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address_delivery = AddressDelivery::findOrFail($id);
        //Lấy đường dẫn cũ
        return view('admin.address_delivery.show', compact('address_delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address_delivery = AddressDelivery::findOrFail($id);
        //Lấy tất cả tỉnh
        $provinces = Province::all()->pluck('name', 'id');
        $provinces->prepend(__('--Chọn tỉnh--'), '')->all();
        //lấy tất cả loại hình kinh doanh
        $ward = Ward::where('district_id', $address_delivery->district_id)->pluck('name', 'id');
        $district = District::where('province_id', $address_delivery->province_id)->pluck('name', 'id');
        $customers = Customer::all()->pluck('name', 'id');
        $customers->prepend(__('message.please_select'), '')->all();
        return view('admin.address_delivery.edit', compact(
            'provinces',
            'address_delivery',
            'ward',
            'customers',
            'district',
        ));
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

        $address_delivery = AddressDelivery::findOrFail($id);
        $requestData = $request->all();
        if (!isset($request->is_default)) {
            $requestData["is_default"] = Config("settings.inactive");
        }
        $provincesName = Province::where('id', $requestData['province_id'])->first();
        $districtName = District::where('id', $requestData['district_id'])->first();
        $wardName = Ward::where('id', $requestData['ward_id'])->first();

        $requestData['address'] = $requestData['address'] . ',' . $wardName->name . ',' . $districtName->name . ',' . $provincesName->name;
        $address_delivery->update($requestData);

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/address_delivery');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AddressDelivery::where('id', $id)->update([
            'deleted_at' => now()
        ]);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/address_delivery');
    }
}
