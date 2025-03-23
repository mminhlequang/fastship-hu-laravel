<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class PaymentController extends Controller
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

        $data = PaymentWallet::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%");
        });

        $data = $data->latest()->paginate($perPage);

        return view('admin.payments.index', compact('keywords', 'locale', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.payments.create');
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
            'name' => 'required|max:255|unique:payment_wallet_provider,name',
        ]);

        $requestData = $request->all();

        if(!$request->is_active) $requestData['is_active'] = 0;

        if (!empty($request->hasFile('icon_url'))) {
            $requestData['icon_url'] = PaymentWallet::uploadAndResize($request->file('icon_url'));
        }

        PaymentWallet::create($requestData);

        toastr()->success(__('settings.created_success'));
        
        return redirect('admin/payments');
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
        $data = PaymentWallet::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.payments.show', compact('data', 'backUrl', 'locale'));
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
        $data = PaymentWallet::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.payments.edit', compact('data', 'backUrl', 'locale'));
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
            'name' => 'required|max:255|unique:payment_wallet_provider,name,'.$id,
        ]);

        $data = PaymentWallet::findOrFail($id);

        $requestData = $request->all();

        if (!empty($request->hasFile('icon_url'))) {
            $requestData['icon_url'] = PaymentWallet::uploadAndResize($request->file('icon_url'));
        }

        if(!$request->active) $requestData['active'] = 0;

        $data->update($requestData);

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/payments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentWallet::destroy($id);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/payments');
    }
}
