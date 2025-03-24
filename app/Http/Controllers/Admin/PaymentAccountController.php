<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentAccount;
use App\Models\PaymentWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class PaymentAccountController extends Controller
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

        $data = PaymentAccount::when($keywords != '', function ($query) use($keywords) {
            $query->where('account_name', 'like', "%$keywords%");
        });

        $data = $data->latest()->paginate($perPage);

        return view('admin.payments_account.index', compact('keywords', 'locale', 'data'));
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

        return redirect('admin/payments_account');
    }
}
