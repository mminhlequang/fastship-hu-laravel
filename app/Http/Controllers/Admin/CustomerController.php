<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\RankCustomers;
use App\Traits\Authorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keywords = $request->get('search') ?? '';
        $from = $request->query('from') ?? '';
        $to = $request->query('to') ?? '';
        $active = $request->query('active') ?? '';
        $promotionId = $request->query('promotion_id') ?? 0;
        $province_id = $request->get('province_id') ?? 0;
        $district_id = $request->get('district_id') ?? 0;
        $ward_id = $request->get('ward_id') ?? 0;
        $oldId = $request->get('old_id') ?? 0;

        $olds = \DB::table('olds')->pluck('name', 'id');
        $olds = $olds->prepend("--Chọn độ tuổi --", '');
        $provinces = \DB::table('provinces')->pluck('name', 'id');
        $provinces = $provinces->prepend("--Chọn tỉnh --", '');
        $districts = \DB::table('districts')->where('province_id', $province_id)->pluck('name', 'id');
        $districts = $districts->prepend("--Chọn huyện --", '');
        $wards = \DB::table('wards')->where('district_id', $district_id)->pluck('name', 'id');
        $wards = $wards->prepend("--Chọn xã --", '');

        if(\Auth::user()->isAdminCompany())
            $promotions = \DB::table('promotions')->where('active', 1)->pluck('name', 'id');
        else
            $promotions = \DB::table('promotions')->where([['creator_id', \Auth::id()], ['active', 1]])->pluck('name', 'id');
        $promotions->prepend(_('--Vui lòng chọn chương trình--'),'');

        $perPage = config('settings.perpage');

        $ids = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();

        $customers = Customer::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%")
                ->orWhere('email', 'like', "%$keywords%")
                ->orWhere('phone', 'like', "%$keywords%");
        })->when($from != '' && $to != '', function ($query) use ($from, $to) {
            $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        })->when($promotionId != 0, function ($query) use ($promotionId) {
            $query->where('promotion_id', $promotionId);
        })->when($active != '', function ($query) use ($active) {
            $query->where('active', $active);
        })->when($province_id != 0, function ($query) use ($province_id) {
            $query->where('province_id', $province_id);
        })->when($district_id != 0, function ($query) use ($district_id) {
            $query->where('district_id', $district_id);
        })->when($ward_id != 0, function ($query) use ($ward_id) {
            $query->where('ward_id', $ward_id);
        })->when($oldId != 0, function ($query) use ($oldId) {
            $old = \DB::table('olds')->where('id', $oldId)->select(['start_old', 'end_old'])->first();
            $startOld = Carbon::now()->subYears($old->end_old)->toDateString();
            $endOld = Carbon::now()->subYears($old->start_old)->toDateString();
            $query->whereDate('birthday', '>=', $startOld)
                ->whereDate('birthday', '<=', $endOld);
        });

        if(\Auth::user()->isAdminCompany())
            $customers = $customers->orderByDesc('updated_at')->paginate($perPage);
        else
            $customers = $customers->whereIn('promotion_id', $ids)->orderByDesc('updated_at')->paginate($perPage);

        return view('admin.customers.index', compact('customers', 'promotions', 'provinces', 'districts', 'wards', 'olds'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|email|max:255|unique:customers',
            'phone' => 'required|unique:customers',
            'gender' => 'nullable|in:0,1,2',
        ], [
            'name' => 'Tên khách hàng không được để trống',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.numeric' => 'Vui lòng nhập đúng định dạng số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.unique' => 'Email đã tồn tại'
        ]);
        $requestData = $request->all();


        if (!empty($requestData["birthday"]))
            $requestData["birthday"] = \DateTime::createFromFormat(config('settings.format.date'), $requestData["birthday"])->format('Y-m-d');
        if ($request->hasFile('avatar')) {
            $requestData['avatar'] = Customer::uploadAndResizeAvatar($request->file('avatar'));
        }

        if (!isset($request->active)) {
            $requestData["active"] = Config("settings.inactive");
        }
        Customer::create($requestData);
        Alert::success(__('Thêm dữ liệu thành công'));

        return redirect('admin/customers');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $birthday = isset($customer->birthday) ? Carbon::parse($customer->birthday)->format(config('settings.format.date')) : null;
        return view('admin.customers.edit', compact('customer', 'birthday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|email|max:255|unique:customers',
            'phone' => 'required|numeric|unique:customers,phone,' . $id,
            'gender' => 'nullable|in:0,1,2',
        ], [
            'name.required' => 'Tên khách hàng không được bỏ trống',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.numeric' => 'Vui lòng nhập đúng định dạng số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.unique' => 'Email đã tồn tại'
        ]);
        $customer = Customer::findOrFail($id);
        $requestData = $request->all();

        if (!isset($request->active)) {
            $requestData["active"] = Config("settings.inactive");
        }

        if (!empty($requestData["birthday"]))
            $requestData["birthday"] = \DateTime::createFromFormat(config('settings.format.date'), $requestData["birthday"])->format('Y-m-d');
        if ($request->hasFile('avatar')) {
            $requestData['avatar'] = Customer::uploadAndResizeAvatar($request->file('avatar'));
        }
        // if(!isset($request->password)){
        //     $requestData['salt'] = bin2hex(random_bytes(16));
        //     $requestData['password'] = md5($requestData['password'] . $requestData['salt']);
        // }

        $customer->update($requestData);

        alert()->success(__('settings.updated_success'));


        return redirect('admin/customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Customer::destroy($id);
        alert()->success(__('Xóa dữ liệu thành công'));
        return redirect('admin/customers');
    }

}
