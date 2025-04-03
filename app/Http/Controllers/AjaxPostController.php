<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use App\Models\Order;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class AjaxPostController extends Controller
{
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Gọi ajax: sẽ gọi đến hàm = tên $action
     * @param Request $action
     * @param Request $request
     * @return mixed
     */
    public function index($action, Request $request)
    {
        return $this->{$action}($request);
    }

    protected $firebaseService;



    // Gửi OTP đến số điện thoại
    public function sendOtp(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'phone' => 'required|regex:/^\+?1?\d{9,15}$/',
            ]
        );
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        dd($request->all());

        $response = $this->firebaseService->sendOtp($request->phone_number);

        return response()->json($response);
    }

    // Xác minh OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^(\+84|0)(9|8)[0-9]{8}$/',
            'otp' => 'required|string',
        ]);

        $response = $this->firebaseService->verifyOtp($request->phone_number, $request->otp);

        return response()->json($response);
    }


}