<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Topping;
use App\Models\VariationValue;
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

    public function addCart(Request $request){
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'product_id' => 'required|exists:products,id',
            ]
        );
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => join(PHP_EOL, $validator->errors()->all())
            ]);
        }

        try {
            $cart = Cart::firstOrCreate([
                'user_id' => \Auth::guard('loyal_customer')->id(),
                'store_id' => $request->store_id,
            ]);

            // Tính giá cho sản phẩm đã chọn biến thể và topping
            $productId = $request->product_id;
            $product = Product::find($productId);

            $quantity = $request->quantity ?? 1;
            $price = $product->price * $quantity;

            // Thêm giá trị biến thể vào giá sản phẩm
            $variations = null;
            if ($request->variations != null && !empty($request->variations)) {
                // Get the variation_value IDs from the request variations
                $variationIds = collect($request->variations)->pluck('variation_value')->toArray();
                // Retrieve all VariationValue records where the id is in the provided list of IDs
                $variations = VariationValue::whereIn('id', $variationIds)->get();

                // Loop through each variation in the request and add the price
                foreach ($request->variations as $variation) {
                    $variationValue = $variations->firstWhere('id', $variation['variation_value']);
                    if ($variationValue) {
                        $variationValue->variation;
                        $price += $variationValue->price;
                    }
                }
            } else {
                unset($requestData['variations']);
            }

            // Thêm topping vào giá sản phẩm
            $toppingPrice = 0;
            $toppings = null;
            // Check if topping_ids are provided
            if ($request->topping_ids != null && !empty($request->topping_ids)) {
                // Fetch toppings based on the provided IDs
                $toppings = Topping::whereIn('id', array_column($request->topping_ids, 'id'))->get();
                foreach ($toppings as $topping) {
                    // Find the corresponding topping from the request data
                    $requestedTopping = collect($request->topping_ids)->firstWhere('id', $topping->id);
                    // Calculate the price based on the quantity in the request
                    $toppingPrice += $topping->price * $requestedTopping['quantity'];
                    $topping->quantity = $requestedTopping['quantity'];
                }
            } else {
                unset($requestData['topping_ids']);
            }

            $price += $toppingPrice;
            // Assuming $cart is an instance of Cart, and you already have $productId, $price, $variations, and $toppings.
            $cart->cartItems()->updateOrCreate(
                [
                    'product_id' => $productId, // This will check if the cart item with this product exists
                    'cart_id' => $cart->id, // Ensures we're updating/creating within the correct cart
                ],
                [
                    'quantity' => $quantity,
                    'price' => $price, // Update price or set the price when creating
                    'product' => collect($product),
                    'variations' => collect($variations), // Update variations or set them when creating
                    'toppings' => collect($toppings), // Update toppings or set them when creating
                ]
            );

            // Reload the cart with updated data
            return $this->loadCart();
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateCart(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => join(PHP_EOL, $validator->errors()->all())
            ]);
        }
        try {
            // Start database transaction
            \DB::beginTransaction();

            $cartItemId = $request->id;
            $quantity = $request->quantity ?? 1; // Default to 1 if quantity is not provided
            $cartItem = CartItem::with(['cart', 'productR'])->find($cartItemId);

            // Delete the cart item if the quantity is less than or equal to 0
            if ($quantity <= 0) {
                $cartItem->delete();
                \DB::commit();
                return $this->loadCart();
            }

            $product = $cartItem->productR;
            $price = $product->price * $quantity;

            // Handle variations
            $variations = null;
            if ($request->variations) {
                $variationIds = collect($request->variations)->pluck('variation_value')->toArray();
                $variations = VariationValue::whereIn('id', $variationIds)->get();

                foreach ($request->variations as $variation) {
                    $variationValue = $variations->firstWhere('id', $variation['variation_value']);
                    if ($variationValue) {
                        $price += $variationValue->price;
                    }
                }
            }

            // Handle toppings
            $toppingPrice = 0;
            $toppings = null;
            if ($request->topping_ids) {
                $toppingIds = collect($request->topping_ids)->pluck('id')->toArray();
                $toppings = Topping::whereIn('id', $toppingIds)->get();

                foreach ($toppings as $topping) {
                    $requestedTopping = collect($request->topping_ids)->firstWhere('id', $topping->id);
                    $toppingPrice += $topping->price * $requestedTopping['quantity'];
                    $topping->quantity = $requestedTopping['quantity']; // Set quantity on topping object
                }
            }

            // Add topping price to total price
            $price += $toppingPrice;

            // Update the cart item with new values
            $cartItem->updateOrCreate(
                [
                    'product_id' => $cartItem->product_id, // Ensure this product is in the cart
                    'cart_id' => $cartItem->cart_id, // Ensure this is the correct cart
                ],
                [
                    'quantity' => $quantity,
                    'price' => $price,
                    'variations' => collect($variations),
                    'toppings' => collect($toppings),
                ]
            );

            // Commit the transaction
            \DB::commit();

            // Reload the cart with updated data
            return $this->loadCart();
        } catch (\Exception $e) {
            // Rollback in case of any error
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update cart. Please try again.',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function loadCart()
    {
        $carts = Cart::has('cartItems')->with('cartItems')->where('user_id', \Auth::guard('loyal_customer')->id())->get();

        $view = view('theme::front-end.ajax.cart', compact('carts'))->render();

        return response()->json([
            'status' => true,
            'view' => $view,
            'message' => 'Cart updated successfully'
        ]);
    }

    public function deleteCart(Request $request){
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => join(PHP_EOL, $validator->errors()->all())
            ]);
        }
        try {
            $id = $request->id;
            // Tìm cart item theo ID
            $cartItem = CartItem::find($id);
            // Xóa cart item
            $cartItem->delete();

            // Reload the cart with updated data
            return $this->loadCart();

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
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
