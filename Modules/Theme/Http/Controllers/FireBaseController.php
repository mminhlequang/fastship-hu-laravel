<?php

namespace Modules\Theme\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Http\Controllers\Controller;

class FireBaseController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    // Gửi OTP đến số điện thoại
    public function sendOtp(Request $request)
    {
        dd('xxx');
        $request->validate([
            'phone_number' => 'required|regex:/^(\+84|0)(9|8)[0-9]{8}$/',
        ]);

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
