<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Setting;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class AuthController extends Controller
{
    public function __construct()
    {
        $settings = Setting::allConfigsKeyValue();

        \View::share([
            'settings' => $settings,
        ]);
    }

    public function myAccount(Request  $request){
        return view("theme::front-end.auth.my_account");
    }

    public function myOrder(Request  $request){
        return view("theme::front-end.auth.my_order");
    }

    public function myVoucher(Request  $request){
        return view("theme::front-end.auth.my_voucher");
    }

    public function myWishlist(Request  $request){
        $storesQuery = Store::with('creator')->whereNull('deleted_at');
        $storesFavorite = $storesQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')->get();
        return view("theme::front-end.auth.my_wishlist", compact('storesFavorite'));
    }


    public function login(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'phone' => 'required',
                'password' => 'required'
            ]
        );
        if ($validator->fails())
            return redirect()->back()->withErrors(['errors' => $validator->errors()->all()]);
        $checkAuth = \Auth::guard('loyal_customer')->attempt(['phone' => $request->phone, 'password' => $request->password], $request->get('remember'));
        if ($checkAuth) return redirect()->intended('/');
        return redirect()->back()->withErrors(['errors' => 'Tài khoản hoặc mật khẩu không đúng']);
    }

    public function register(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'phone' => 'nullable|email|unique:customers,phone',
                'password' => 'required'
            ]
        );
        if ($validator->fails())
            return redirect()->back()->withErrors(['errors' => $validator->errors()->all()]);

        Customer::create([
            'name' => $requestData['name'],
            'phone' => $requestData['phone'],
            'password' => $requestData['password'],
            'type' => 1
        ]);
        return redirect('/')->with('success', 'Đăng ký tài khoản thành công.');
    }

    public function changePassword(Request $request)
    {
        $this->validate([
            'password_old' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',

        ]);

        $requestData = $request->all();
        $id = \Auth::guard('loyal_customer')->id();
        $data = Customer::find($id);
        if (!\Hash::check($requestData['password_old'], $data->password))
            return back()->with('error', 'Mật khẩu cũ không đúng , vui lòng thử lại !');
        else {
            Customer::where('id', $id)->update(['password' => \Hash::make($request->password)]);
            return redirect('/')->with('success', __('UPDATE_PASSWORD_SUCCESS'));
        }
    }


    public function updateProfile(Request $request)
    {
        $this->validate([
            'name' => 'required|max:120',
        ]);

        $requestData = $request->all();
        try {
            $user = \Auth::guard('loyal_customer')->user();
            $user->update($requestData);
            return redirect('/')->with('success', __('UPDATE_PROFILE'));
        } catch (\Exception  $e) {
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'email' => 'required|email|exists:customers',
            ],
            [
                'email.exists' => 'Email không tồn tại trong hệ thống',
                'email.email' => 'Email không đúng dịnh dạng'
            ]
        );
        if ($validator->fails()) return redirect()->back()->withErrors(['errors' => $validator->errors()->all()]);

        $token = \Str::random(64);
        \DB::table('customers')->where('email', $requestData['email'])->update(['token' => $token]);

        return redirect()->back()->with('success', 'Vui lòng kiểm tra email để thay đổi mật khẩu');
    }

    public function resetPassword(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'password' => 'required',
                'password_confirm' => 'required|same:password',
            ]
        );
        if ($validator->fails())
            return redirect()->back()->withErrors(['errors' => $validator->errors()->all()]);
        $token = $requestData['token'];
        \DB::table('customers')->where('token', $token)->update(['password' => \Hash::make($request->password)]);
        return redirect()->back()->with('success', 'Đổi mật khẩu thành công.');
    }

    public function insertAddress(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required'
            ]
        );
        if ($validator->fails())
            return redirect()->back()->withErrors(['errors' => $validator->errors()->all()]);
        $isDefault = \DB::table('address_delivery')->where([['customer_id', \Auth::guard('loyal_customer')->id()], ['is_default', 1]])->value('id');

        \DB::table('address_delivery')->insert([
            'name' => $requestData['name'],
            'address' => $requestData['address'],
            'phone' => $requestData['phone'],
            'province_id' => $requestData['province_id'],
            'district_id' => $requestData['district_id'],
            'ward_id' => $requestData['ward_id'],
            'is_default' => (!$isDefault) ? 1 : 0,
            'customer_id' => \Auth::guard('loyal_customer')->id(),
        ]);
        return redirect('dia-chi.html')->with('success', 'Thêm dịa chỉ thành công.');
    }

    public function updateAddress(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required'
            ]
        );
        if ($validator->passes()) {
            $id = $requestData['id'];
            $isDefault = isset($requestData['is_default']) ? $requestData['is_default'] : 0;
            if ($isDefault == 1)
                \DB::table('address_delivery')->where('customer_id', \Auth::guard('loyal_customer'))->update(['is_default' => 0]);

            \DB::table('address_delivery')->where('id', $id)->update([
                'name' => $requestData['name'],
                'address' => $requestData['address'],
                'phone' => $requestData['phone'],
                'province_id' => isset($requestData['province_id']) ? $requestData['province_id'] : null,
                'district_id' => isset($requestData['district_id']) ? $requestData['district_id'] : null,
                'ward_id' => isset($requestData['ward_id']) ? $requestData['ward_id'] : null,
                'is_default' => $isDefault,
                'customer_id' => \Auth::guard('loyal_customer')->id()
            ]);
            return redirect('dia-chi.html')->with('success', 'Cập nhật dịa chỉ thành công.');
        }
        return redirect()->back()->withErrors(['errors' => $validator->errors()->all()]);
    }

    public function postContact(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        if ($validator->passes()) {
            $contact = new Contact();
            $contact->fullname = $request->fullname;
            $contact->email = $request->email;
            $contact->address = !empty($request->address) ? $request->address : '';
            $contact->phone = !empty($request->phone) ? $request->phone : '';
            $contact->message = $request->message;
            $contact->save();
            //Send mail
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors' => $validator->errors()->all()]);
    }

    public function logout(Request $request)
    {
        \Auth::guard('loyal_customer')->logout();
        return redirect('/');
    }

}
