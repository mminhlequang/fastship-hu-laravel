<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class TransactionController extends Controller
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

        $data = Transaction::when($keywords != '', function ($query) use($keywords) {
            $query->whereHas('user', function ($query) use ($keywords){
                $query->where('name', 'like', "%$keywords%");
            });
        });

        $data = $data->whereNull('deleted_at')->latest()->paginate($perPage);

        return view('admin.transactions.index', compact('keywords', 'locale', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = \DB::table('customers')
            ->whereNull('deleted_at')
            ->select('id', \DB::raw("CONCAT(name, '-', COALESCE(phone, ''), '-', COALESCE(email, '')) AS name_with_phone"))
            ->pluck('name_with_phone', 'id');
        $customers = $customers->prepend(__("--Choose customer --"), '');

        return view('admin.transactions.create', compact('customers'));
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
            'user_id' => 'required',
        ]);

        $requestData = $request->all();

        if(isset($request->price)){
            $requestData["price"] = (float)str_replace(',', '', $request->price);
        }

        \DB::transaction(function () use ($request, $requestData) {

            Transaction::create($requestData);
        });

        toastr()->success(__('settings.created_success'));

        return redirect('admin/transactions');
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
        $data = Transaction::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.transactions.show', compact('data', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $customers = \DB::table('customers')
            ->whereNull('deleted_at')
            ->select('id', \DB::raw("CONCAT(name, '-', COALESCE(phone, ''), '-', COALESCE(email, '')) AS name_with_phone"))
            ->pluck('name_with_phone', 'id');
        $customers = $customers->prepend(__("--Choose customer --"), '');


        $locale = app()->getLocale();
        $data = Transaction::findOrFail($id);

        $backUrl = $request->get('back_url');
        return view('admin.transactions.edit', compact('data', 'backUrl', 'locale', 'customers'));
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
            'user_id' => 'required',
        ]);

        $data = Transaction::findOrFail($id);

        $requestData = $request->all();

        if(isset($request->price)){
            $requestData["price"] = (float)str_replace(',', '', $request->price);
        }

        \DB::transaction(function () use ($request, $requestData, $data) {

            $data->update($requestData);
        });

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/transactions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::table('transactions')->where('id', $id)->update([
            'deleted_at' => now(),
        ]);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/transactions');
    }
}
