<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;
use App\Models\User;


/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $usr = \DB::table('users')->count('id');

        // Thống kê driver
        $drivers = \DB::table('customers')
            ->selectRaw("SUM(active = 1 AND type = 2) as active, SUM(active = 0 AND type = 2) as not_active")
            ->first();

        // Thống kê store
        $stores = \DB::table('stores')
            ->selectRaw("SUM(active = 1) as active, SUM(active = 0) as not_active")
            ->first();

        // Thống kê đơn hàng
        $ordersCount = \DB::table('orders')->count('id');
        $ordersTotalPrice = \DB::table('orders')
            ->where('payment_status', 'completed')
            ->sum('total_price');

        // Kết quả
        $data = [
            'user' => $usr,
            'driverActive' => $drivers->active,
            'driverNotActive' => $drivers->not_active,
            'storeActive' => $stores->active,
            'storeNotActive' => $stores->not_active,
            'orders' => $ordersCount,
            'ordersTotalPrice' => $ordersTotalPrice,
        ];

        return view('adminlte::home',compact('data' ));
    }
}
