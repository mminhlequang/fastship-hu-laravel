<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\TypeDiscount;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');
        $locale = app()->getLocale();
        $discounts = new Discount();
        if (!empty($keyword)) {
            $discounts = $discounts->where('name', 'LIKE', "%$keyword%");
        }
        $discounts = $discounts->whereNull('deleted_at')->sortable(['updated_at' => 'desc'])->paginate($perPage);

        return view('admin.discounts.index', compact('keyword', 'locale', 'discounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        return view('admin.discounts.create', compact('stores'));
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
                'code' => 'required|unique:discounts,code',
            ],
            [
                'code.required' => ' Trường loại thương hiệu không được để trống !',
                'code.unique' => ' Tên loại thương hiệu đã tồn tại',
            ]
        );
        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
        }
        
        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image')) {
                $requestData['image'] = Discount::uploadAndResize($request->file('image'));
            }

            $requestData['cart_value'] = (float)str_replace(',', '', $request->cart_value);
            if(!isset($request->sale_maximum)){
                $requestData["sale_maximum"] = (float)str_replace(',', '', $request->sale_maximum);
            } 	
            if (!isset($requestData['sale_maximum'])) {
                $requestData["sale_maximum"] = 0;
            } else {
                $requestData["sale_maximum"] = (float)str_replace(',', '', $request->sale_maximum);
            }

            if (isset($requestData['start_date'])) {
                $requestData['start_date'] = \DateTime::createFromFormat(config('settings.format.date'), $requestData['start_date'])->format('Y-m-d');
            }

            if (!isset($requestData['expiry_date'])) {
                $requestData["expiry_date"] = \Carbon\Carbon::now();
            } else {
                $requestData['expiry_date'] = \DateTime::createFromFormat(config('settings.format.date'), $requestData['expiry_date'])->format('Y-m-d');
            }
            Discount::create($requestData);
        });

        toastr()->success(__('settings.created_success'));
        // toastr()->success(__('theme::business.created_success'));

        return redirect('admin/discounts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discounts = Discount::findOrFail($id);

        return view('admin.discounts.show', compact('discounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $locale = app()->getLocale();
        $discounts = Discount::findOrFail($id);

        $date = Carbon::parse($discounts->expiry_date)->format("d/m/Y");
        $start_date = Carbon::parse($discounts->start_date)->format("d/m/Y");

        return view('admin.discounts.edit', compact('locale', 'discounts', 'date', 'stores', 'start_date'));
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
                'code' => 'required',
                'description' => 'required',

            ],
            [
                'code.required' => ' Trường loại thương hiệu không được để trống !',
                'code.unique' => ' Tên loại thương hiệu đã tồn tại',
                'description.required' => ' Trường mô tả không được để trống !',


            ]
        );
        $discounts = Discount::findOrFail($id);

        $requestData = $request->all();
        //kiểm tra tình trạng
        if (!isset($request->active)) {
            $requestData["active"] = Config("settings.inactive");
        }

        \DB::transaction(function () use ($request, $requestData, $discounts) {
            if ($request->hasFile('image')) {
                \Storage::delete($discounts->image);
                $requestData['image'] = Discount::uploadAndResize($request->file('image'));
            }
            if (!isset($requestData['sale_maximum'])) {
                $requestData["sale_maximum"] = 0;
            } else {
                $requestData["sale_maximum"] = (float)str_replace(',', '', $request->sale_maximum);
            }			
            $requestData['cart_value'] = (float)str_replace(',', '', $request->cart_value);

            if (!isset($requestData['start_date'])) {
                $requestData["start_date"] = \Carbon\Carbon::now();
            } else {
                $requestData['start_date'] = \DateTime::createFromFormat(config('settings.format.date'), $requestData['start_date'])->format('Y-m-d');
            }

            if (!isset($requestData['expiry_date'])) {
                $requestData["expiry_date"] = \Carbon\Carbon::now();
            } else {
                $requestData['expiry_date'] = \DateTime::createFromFormat(config('settings.format.date'), $requestData['expiry_date'])->format('Y-m-d');
            }
            $discounts->update($requestData);
        });
        toastr()->success(__('settings.updated_success'));

        return redirect('admin/discounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Discount::destroy($id);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/discounts');
    }
}
