<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class WithdrawalController extends Controller
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

        $data = Withdrawals::when($keywords != '', function ($query) use ($keywords) {
            $query->whereHas('user', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            });
        });

        $data = $data->latest()->paginate($perPage);

        return view('admin.withdrawals.index', compact('keywords', 'locale', 'data'));
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

        return view('admin.withdrawals.create', compact('customers'));
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

        if (isset($request->base_price)) {
            $requestData["base_price"] = (float)str_replace(',', '', $request->base_price);
        }

        \DB::transaction(function () use ($request, $requestData) {

            Withdrawals::create($requestData);
        });

        toastr()->success(__('settings.created_success'));

        return redirect('admin/withdrawals');
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
        $data = Withdrawals::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.withdrawals.show', compact('data', 'backUrl', 'locale'));
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
        $data = Withdrawals::findOrFail($id);

        $backUrl = $request->get('back_url');
        return view('admin.withdrawals.edit', compact('data', 'backUrl', 'locale', 'customers'));
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

        $data = Withdrawals::findOrFail($id);

        $requestData = $request->all();

        \DB::transaction(function () use ($request, $requestData, $data) {
            $data->update($requestData);
            $walletId = $data->wallet_id;
            $amount = $data->amount;
            //Nếu ở trang thái thành công nạp + tiền, rút tiền - trừ.
            if ($data->status == 'completed') {
                $data->processed_date = now();
                $data->save();
                //Ghi nhận giao dịch trong bảng tbl_transactions với loại “debit”.
                \DB::table('wallet_transactions')->where('id', $data->transaciton_id)->update([
                    'status' => 'completed'
                ]);
                //Trừ số tiền khỏi frozen_balance.
                \DB::table('wallets')->where('id', $walletId)->update([
                    'frozen_balance' => \DB::raw('frozen_balance - ' . (int)$amount)
                ]);


            } elseif ($data->status == 'reject') {
                //Cập nhật bản ghi yêu cầu thành “rejected”.
                $data->processed_date = now();
                $data->save();
                //Chuyển lại số tiền:
                \DB::table('wallet_transactions')->where('id', $data->transaciton_id)->update([
                    'status' => 'reject'
                ]);
                //frozen_balance = frozen_balance - [số tiền rút]
                //available_balance = available_balance + [số tiền rút]
                \DB::table('wallets')->where('id', $walletId)->update([
                    'balance' => \DB::raw('balance + ' . (int)$amount),
                    'frozen_balance' => \DB::raw('frozen_balance - ' . (int)$amount)
                ]);

            }
        });

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/withdrawals');
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

        return redirect('admin/withdrawals');
    }
}
