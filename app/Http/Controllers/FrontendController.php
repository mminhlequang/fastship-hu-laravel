<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Old;
use App\Models\Province;
use Illuminate\Http\Request;
use Validator;

class FrontendController extends Controller
{
    /**
     * Form đăng ký quay thưởng
     *
     * @return \Illuminate\Http\Response
     */
    public function getFormCustomerRegister(Request $request)
    {
        $id = $request->id ?? 1;
        $companyId = \DB::table('promotions')->where('id', $id)->value('creator_id') ?? 1;
        $settings = \DB::table('configs_company')->where('user_id', $companyId)->where('promotion_id', $id)->value('input');
        $label = \DB::table('configs_company')->where('user_id', $companyId)->where('promotion_id', $id)->value('label');
        $promotion = \DB::table('promotions')->where('id', $id)->value('name');
        $promotionContent = \DB::table('promotions')->where('id', $id)->value('description');

        //Get select
        $provinces = \DB::table('provinces')->pluck('name', 'id');
        $provinces = $provinces->prepend("--Chọn tỉnh/thành phố--", '');
        $districts = ['' => '--Chọn quận/huyện--'];
        $wards = ['' => '--Chọn xã/phường--'];

        return view('frontends.register', compact('settings', 'promotion', 'provinces', 'districts', 'wards', 'promotionContent', 'label'));
    }

    public function customerRegister(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make(
            $requestData, [
            'full_name' => 'required|max:100',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'email' => 'required|email|max:100',
        ], [
            'phone_number.min' => 'SĐT tổi thiểu 10 kí tự',
            'phone_number.max' => 'SĐT tổi đa 10 kí tự',
            'phone_number.regex' => 'SĐT không đúng định dạng',
            'email.email' => 'Email không đúng định dạng',
        ]);

        if ($validator->fails())
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);

        try {
            //Xác thực thành công
            $fullName = $requestData['full_name'];
            $email = $requestData['email'];
            $phone = $requestData['phone_number'];
            $address = isset($requestData['address']) ? $requestData['address'] : "";

            $data = Customer::create([
                'name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address
            ]);

            return response()->json(['status' => true, 'data' => $data, 'message' => 'Đăng ký nhận quà thành công.Vui lòng kiểm tra email để nhận mã quay thưởng.']);
        } catch (\Exception $e) {
            return \response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }

    }

    public function getPage($slug, Request $request)
    {
        switch ($slug) {
            case "dang-ky":
                $id = $request->id ?? 1;
                $companyId = \DB::table('promotions')->where('id', $id)->value('creator_id') ?? 1;
                $settings = \DB::table('configs_company')->where('user_id', $companyId)->where('promotion_id', $id)->value('input');
                $label = \DB::table('configs_company')->where('user_id', $companyId)->where('promotion_id', $id)->value('label');
                $promotion = \DB::table('promotions')->where('id', $id)->value('name');
                $promotionContent = \DB::table('promotions')->where('id', $id)->value('description');

                //Get select
                $provinces = \DB::table('provinces')->pluck('name', 'id');
                $provinces = $provinces->prepend("--Chọn tỉnh/thành phố--", '');
                $districts = ['' => '--Chọn quận/huyện--'];
                $wards = ['' => '--Chọn xã/phường--'];

                return view('frontends.register', compact('settings', 'promotion', 'provinces', 'districts', 'wards', 'promotionContent', 'label'));
            case "scan-qr":
                if ($request->get('customer_id') == null || $request->get('promotion_id') == null)
                    return redirect('dang-ky.html');
                return view('frontends.scanQR');
            default:
                return view("frontends.404");
        }

    }

}
