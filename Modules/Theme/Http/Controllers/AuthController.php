<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $settings = Setting::allConfigsKeyValue();

        \View::share([
            'settings' => $settings,
        ]);
    }

    public function myCart(Request $request)
    {
        $carts = Cart::has('cartItems')->with('cartItems')->where('user_id', \Auth::guard('loyal_customer')->id())->get();

        return view("theme::front-end.auth.my_cart", compact('carts'));
    }

    public function checkOut(Request $request)
    {
        $storeId = $request->store_id;
        $carts = CartItem::with('cart')->whereHas('cart', function ($query) use ($storeId) {
            $query->where('store_id', $storeId)->where('user_id', \Auth::guard('loyal_customer')->id());
        })->get();

        $productsQuery = Product::with('store')->whereHas('store', function ($query) {
            // Áp dụng điều kiện vào relation 'store'
            $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
        }); // Initialize the query

        $productsFavorite = $productsQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')
            ->take(4)->get();

        // Total quantity and total price for all items in the carts
        $subtotal = $carts->sum('price');
        $discount = 0;
        $shipFee = 0;
        $tip = 0;
        $applicationFee = $subtotal * 0.03;
        $total = $subtotal + $tip + $shipFee + $applicationFee - $discount;

        return view("theme::front-end.auth.check_out", compact('carts', 'subtotal', 'total', 'applicationFee', 'shipFee', 'productsFavorite', 'storeId'));
    }

    public function myAccount(Request $request)
    {
        return view("theme::front-end.auth.my_account");
    }

    public function myOrder(Request $request)
    {
        $status = $request->payment_status ?? '';
        $from = $request->from ?? '';
        $to = $request->to ?? '';
        $orders = Order::with('orderItems')
            ->when($status != '', function ($query) use ($status) {
                $query->where('payment_status', $status);
            })
            ->when($from != '' && $to != '', function ($query) use ($from, $to) {
                $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            })
            ->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->paginate(5);

        return view("theme::front-end.auth.my_order", compact('orders', 'status', 'from', 'to'));
    }

    public function myVoucher(Request $request)
    {
        return view("theme::front-end.auth.my_voucher");
    }

    public function myWishlist(Request $request)
    {
        $ids = \DB::table('stores_favorite')->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->pluck('store_id')->toArray();

        $storesQuery = Store::with('creator')->whereNull('deleted_at');
        $storesFavorite = $storesQuery->whereIn('id', $ids)->get();

        return view("theme::front-end.auth.my_wishlist", compact('storesFavorite'));
    }

    public function myWishlistProduct(Request $request)
    {
        $ids = \DB::table('products_favorite')->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->pluck('product_id')->toArray();

        $productsQuery = Product::with('store')->whereNull('deleted_at');
        $data = $productsQuery->whereIn('id', $ids)->get();

        return view("theme::front-end.auth.my_wishlist_products", compact('data'));
    }


    public function updateProfile(Request $request)
    {
        // Validate the request
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'email' => 'nullable|email|unique:customers,email,' . \Auth::guard('loyal_customer')->id(),
                'password' => 'nullable|max:120',
                'address' => 'nullable|max:120',
                'street' => 'nullable|max:120'
            ]
        );
        if ($validator->fails()) {
            // Return with the validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if ($requestData['password'] == null) unset($requestData['password']);
            $user = \Auth::guard('loyal_customer')->user();
            $user->update($requestData);
            return redirect()->back()->with('success', __('Update Profile successfully'));
        } catch (\Exception  $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteFavorite(Request $request)
    {
        try {
            $userId = \Auth::guard('loyal_customer')->id();
            \DB::table('stores_favorite')->where('user_id', $userId)->delete();
            return redirect()->back()->with('success', __('Delete all stores favorite successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
