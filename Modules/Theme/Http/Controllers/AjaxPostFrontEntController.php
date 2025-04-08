<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AjaxPostFrontEntController extends Controller
{

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


    public function uploadAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $filePath = Customer::uploadAndResize($request->file('avatar'));
            \DB::table('customers')->where('id', \Auth::guard('loyal_customer')->id())
                ->update(['avatar' => $filePath]);
            return response()->json(['success' => true, 'path' => url($filePath)]);
        }
        return response()->json(['success' => false]);
    }

    // Gửi OTP đến số điện thoại
    public function sendOtp(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'phone' => [
                'required',
                'regex:/^\+?1?\d{9,15}$/'
            ],
            'g-recaptcha-response' => 'required'

        ], [
            'g-recaptcha-response.required' => 'Captcha is required',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Phone number not valid',
        ]);
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);

        try {
            // Remove the leading zero if it exists
//            $phone = ltrim($request->phone, '0');
//            $phone = $request->code . $phone;

            $phone = '+84969696969';
            // Store the customer data in the session
            return response()->json([
                'status' => true,
                'data' => $phone,
                'message' => 'Send OTP Success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Xác minh OTP
    public function verifyOtp(Request $request)
    {

        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'otp' => 'required|digits:6',
            'userData' => 'required'
        ], [
            'userData.required' => 'Not valid data',
            'otp.required' => 'Otp is required',
            'otp.digits' => 'Otp is 6 number',
        ]);
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);

        $uid = $requestData['userData']['uid'];
        $phone = $requestData['userData']['phoneNumber'];

        try {
            $customer = Customer::updateOrCreate(
                [
                    'uid' => $uid,
                    'phone' => $phone
                ],
                [
                    'uid' => $uid,
                    'phone' => $phone
                ]
            );

            \Auth::guard('loyal_customer')->login($customer);

            return response()->json([
                'status' => true,
                'message' => 'Login Success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function postContact(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:120',
            'phone' => 'required',
            'subject' => 'required|max:120',
            'message' => 'required|max:3000',
            'email' => 'required|email|unique:contacts,email'
        ]
        );
        if ($validator->passes()) {
            $data = new Contact();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->content = $request->message ?? '';
            $data->save();
            return response()->json([
                'status' => true,
                'message' => 'Thank you for getting in touch with us. Your message has been successfully sent, and we will get back to you as soon as possible.'
            ]);
        }
        return response()->json(['errors' => $validator->errors()->all()]);
    }

    public function newsLetter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->passes()) {
            return response()->json([
                'status' => true,
                'message' => 'Thank you for subscribing to our newsletter! You will now receive the latest updates and exclusive offers straight to your inbox.'
            ]);
        }
        return response()->json(['errors' => $validator->errors()->all()]);
    }

    //Review
    public function review(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'review' => 'required',
        ], [
            'name.required' => 'Vui lòng nhập họ tên!',
            'review.required' => 'Vui lòng nhập đánh giá!'
        ]);
        if ($validator->passes()) {
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }


}
