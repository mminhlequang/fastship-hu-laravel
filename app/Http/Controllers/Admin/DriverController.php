<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\RankCustomers;
use App\Traits\Authorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DriverController extends Controller
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

        $perPage = config('settings.perpage');

        $customers = Customer::when($keywords != '', function ($query) use ($keywords) {
            $query->where('name', 'like', "%$keywords%")
                ->orWhere('email', 'like', "%$keywords%")
                ->orWhere('phone', 'like', "%$keywords%");
        })->when($from != '' && $to != '', function ($query) use ($from, $to) {
            $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        });

        $customers = $customers->where('type', 2)->whereNull('deleted_at')->latest()->paginate($perPage);

        return view('admin.drivers.index', compact('customers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.drivers.create');
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

        return redirect('admin/drivers');
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
        $steps = \DB::table('steps')->whereNull('deleted_at')->pluck('name', 'id');
        $steps = $steps->prepend("--Choose step --", '');

        $stepsX = \DB::table('steps')->whereNull('deleted_at')->get();
        $customer = Customer::findOrFail($id);

        return view('admin.drivers.show', compact('customer', 'steps', 'stepsX'));
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
        return view('admin.drivers.edit', compact('customer', 'birthday'));
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
        $requestData = $request->all();
        \DB::beginTransaction();
        try {
            $customer = Customer::find($id);
            $stepId = $request->step_id;
            $customer->update([
                'step_id' => $stepId
            ]);
            if (!empty($requestData['data'])) {
                foreach ($requestData['data'] as $item) {
                    $image = (!empty($item['image'])) ? Customer::uploadAndResize($item['image']) : null;
                    \DB::table('customers_steps')->where('id', $item['id'])->update([
                        'comment' => $item['comment'],
                        'image' => $image,
                        'link' => $item['link'],
                        'status' => $item['status'],
                    ]);
                }

            }

            toastr()->success(__('settings.updated_success'));

            \DB::commit();

            return redirect('admin/drivers/'.$id);
        } catch (\Exception $e) {
            \DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect('admin/drivers/' . $id);
        }

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
        \DB::table('customers')->where('id', $id)->update([
            'deleted_at' => now()
        ]);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/drivers');
    }

}
