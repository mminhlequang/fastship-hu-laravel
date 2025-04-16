<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Topping;
use App\Models\VariationValue;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class AjaxPostFrontEntController extends Controller
{
    public function __construct()
    {
        // Thiết lập khóa secret của Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
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

    public function addCart(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'product_id' => 'required|exists:products,id',
            ]
        );
        if ($validator->fails()) {
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
            $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

            if ($cartItem) {
                // If the cart item exists, increase the quantity
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $quantity,  // Add the new quantity to the existing one
                    'price' => $price,
                    'product' => collect($product),
                    'variations' => collect($variations),
                    'toppings' => collect($toppings),
                ]);
            } else {
                // If the cart item does not exist, create a new one
                $cart->cartItems()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'product' => collect($product),
                    'variations' => collect($variations),
                    'toppings' => collect($toppings),
                ]);
            }

            // Reload the cart with updated data
            return $this->loadCart('Add cart successfully');

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function loadCart($message = 'Cart updated successfully')
    {
        $carts = Cart::has('cartItems')->with('cartItems')->where('user_id', \Auth::guard('loyal_customer')->id())->get();

        $view = view('theme::front-end.ajax.cart', compact('carts'))->render();

        return response()->json([
            'status' => true,
            'view' => $view,
            'message' => $message
        ]);
    }


    public function submitOrder(Request $request)
    {
        $requestData = $request->all();

        $validator = \Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'voucher_id' => 'nullable|exists:discounts,id',
                'delivery_type' => 'required|in:ship,pickup',
                'payment_method' => 'required|in:pay_cash,pay_stripe',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            if($request->delivery_type == 'pickup') {
                $request->fee = 0;
                $request->lat = $_COOKIE['lat'] ?? null;// Xóa tọa độ latitude
                $request->lng = $_COOKIE['lng'] ?? null;// Xóa tọa độ longitude
                $request->address = null;              // Xóa địa chỉ giao hàng
                $request->ship_distance = null;        // Xóa khoảng cách giao hàng
                $request->ship_estimate_time = null;   // Xóa thời gian ước tính giao
            }

            if ($request->payment_id !== 5) {
                return $this->createStripePayment($request);
            }
            return $this->createCashPayment($request);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function createCashPayment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cart = $this->getCart($request);
            $order = $this->createOrder($cart, 'pay_cash', $request);

            //Send notification
            $title = 'Order Received';
            $description = "Your order {$order->code} has been received by our store and is being processed. You will receive an update with tracking information once available.";

            Notification::insertNotificationByUser($title, $description, '', 'order', optional($order->store)->creator_id, $order->id, $order->store_id);

            //Xoá cart
            if ($order->delivery_type == 'pickup') $this->deleteCart($order->user_id, $order->store_id);

            session(['order_id' => $order->id]);

            \DB::commit();
            return response()->json([
                'status' => true,
                'payment' => $request->payment_id,
                'message' => 'Order successfully',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function createStripePayment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cart = $this->getCart($request);

            $order = $this->createOrder($cart, 'pay_stripe', $request);

            //Tính tổng tiền
            $subTotal = $order->total_price;
            $tip = $order->price_tip ?? 0;
            $shippingFee = $order->fee ?? 0;
            $discount = $order->voucher_value ?? 0;

            // Tính application_fee, 3% của subtotal
            $application_fee = $subTotal * 0.03;
            $orderPrice = $subTotal + $tip + $shippingFee + $application_fee - $discount;

            //Save transaction
            $transaction = WalletTransaction::create([
                'price' => $orderPrice,
                'base_price' => $orderPrice,
                'fee' => 0,
                'currency' => 'eur',
                'user_id' => \Auth::guard('loyal_customer')->id(),
                'payment_method' => 'card',
                'type' => 'purchase',
                'status' => 'pending',
                'order_id' => $order->id,
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            // Call Stripe payment method
            $data = $this->createCheckoutSession($order, $orderPrice, $transaction->code);
            if (!$data['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $data['message']
                ]);
            }

            session(['order_id' => $order->id]);

            \DB::commit();
            return response()->json([
                'status' => true,
                'session_id' => $data['data'],
                'payment' => $request->payment_id,
                'message' => 'Order successfully',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Tạo một Checkout Session
    private function createCheckoutSession($order, $totalAmount, $transactionId)
    {
        try {
            $orderCode = $order->code;
            // Giả sử bạn đã có thông tin đơn hàng và khách hàng
            $customerName = optional($order->user)->name ?? '';
            $customerPhone = optional($order->user)->phone ?? '';

            $description = "Payment order code {$order->code}";

            // Giả sử bạn lấy giỏ hàng từ session hoặc database
            $items = [];
            if (count($order->orderItems) > 0) {
                foreach ($order->orderItems as $item) {
                    $items[] = [
                        'name' => $item->product['name'],
                        'price' => (int)$item->product['price'],
                        'quantity' => (int)$item->quantity,
                    ];
                }
            }

            // Tạo khách hàng trên Stripe
            $customer = \Stripe\Customer::create([
                'name' => $customerName,
                'phone' => $customerPhone,
            ]);

            //Tính tổng tiền
            $subTotal = $order->total_price;
            $tip = $order->price_tip ?? 0;
            $shippingFee = $order->fee ?? 0;
            $discount = $order->voucher_value ?? 0;

            // Tính application_fee, 3% của subtotal
            $application_fee = $subTotal * 0.03;
            $orderPrice = $subTotal + $tip + $shippingFee + $application_fee - $discount;

            // Tạo line_items từ sản phẩm
            $line_items = array_map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item['name'],
                        ],
                        'unit_amount' => (int)($item['price'] * 100),
                    ],
                    'quantity' => $item['quantity'],
                ];
            }, $items);

            // Thêm phí vận chuyển như một line_item nếu có
            if ($shippingFee > 0) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Shipping fee',
                        ],
                        'unit_amount' => (int)round($shippingFee * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Thêm tip như một line_item nếu có
            if ($tip > 0) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Courier Tip',
                        ],
                        'unit_amount' => (int)round($tip * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Thêm application_fee như một line_item
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Application fee (3%)',
                    ],
                    'unit_amount' => (int)round($application_fee * 100), // Làm tròn đúng trước khi chuyển sang int
                ],
                'quantity' => 1,
            ];

            // Thêm giảm giá như một line_item âm nếu có
            if ($discount > 0) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Discount',
                        ],
                        'unit_amount' => (int)round($discount * -100), // Giá trị âm để giảm tổng
                    ],
                    'quantity' => 1,
                ];
            }

            // Tạo session thanh toán
            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'customer' => $customer->id,
                'line_items' => $line_items,
                'metadata' => [
                    'order_id' => $transactionId,
                    'order_code' => $orderCode,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'tip' => $tip,
                    'application_fee' => $application_fee,
                    'ship_fee' => $shippingFee,
                    'discount' => $discount,
                    'sub_total' => $subTotal,
                    'total' => $orderPrice,
                    "description" => $description,
                ],
                'success_url' => url('find-driver'),
                'cancel_url' => url('my-cart'),
            ]);

            // Trả về URL của Checkout Session
            return [
                'status' => true,
                'data' => $session->id,
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function createOrder($cart, $paymentMethod, $request)
    {
        //Request data
        $deliveryType = $request->delivery_type ?? 'delivery';
        $addressDelivery = $request->address_delivery;

        // Fetch cart items
        $cartItems = $cart->cartItems()->get();
        if ($cartItems->isEmpty()) {
            throw new \Exception(__('CART_EMPTY'));
        }

        // Total price
        $totalPrice = $cartItems->sum('price');

        // Create or update order
        $order = Order::create([
            'user_id' => $cart->user_id,
            'store_id' => $cart->store_id,
            'total_price' => $totalPrice,
            'currency' => 'eur',
            'delivery_type' => $deliveryType,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'process_status' => 'pending',
            'address_delivery_id' => $addressDelivery,
            'payment_id' => $request->payment_id,
            'price_tip' => $request->price_tip ?? 0,
            'fee' => $request->fee ?? 0,
            'phone' => $request->phone,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'street' => $request->street,
            'zip' => $request->zip,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'country_code' => $request->country_code,
            'ship_distance' => $request->ship_distance,
            'ship_estimate_time' => $request->ship_estimate_time,
            'ship_polyline' => $request->ship_polyline,
            'ship_here_raw' => $request->ship_here_raw
        ]);

        // Attach the cart items as order items
        $orderItems = $cartItems->map(function ($cartItem) use ($order) {
            return new OrderItem([
                'product_id' => $cartItem->product_id,
                'price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'product' => $cartItem->product,
                'variations' => $cartItem->variations,
                'toppings' => $cartItem->toppings,
            ]);
        });

        // Save order items
        $order->orderItems()->saveMany($orderItems);


        return $order;
    }

    private function getCart(Request $request)
    {
        $storeId = $request->store_id;
        $userId = \Auth::guard('loyal_customer')->id() ?? 0;

        $cart = Cart::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->first();

        if (!$cart) {
            throw new \Exception(__('Cart is empty'));
        }

        return $cart;
    }

    private function deleteCart($userId, $storeId)
    {
        CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
            $query->where('user_id', $userId)->where('store_id', $storeId);
        })->delete();
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
            'code' => 'required|starts_with:+',
            'phone' => ['required', 'regex:/^[0-9]{6,12}$/'],
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
            $phone = ltrim($request->phone, '0');
            $fullPhone = $request->code . $phone;

//            $phone = '+84969696969';

            // Store the customer data in the session
            return response()->json([
                'status' => true,
                'data' => $fullPhone,
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
                    'phone' => $phone,
                    'type' => 1,
                ],
                [
                    'uid' => $uid,
                    'phone' => $phone,
                    'type' => 1
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


    //Check Voucher
    public function checkVoucher(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'code' => 'required|exists:discounts,code',
            'store_id' => 'required|exists:stores,id'
        ],[
            'code.exists' => 'Voucher not valid'
        ]);
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        try {
            $userId = \Auth::guard('loyal_customer')->id();
            $storeId = $request->store_id;

            // Calculate the total cart value by summing the 'price' field in the 'cart_items' table
            $cartValue = CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
                $query->where([['user_id', $userId], ['store_id', $storeId]]);
            })->sum('price'); // This will sum the 'price' field in all cart items for the user

            $voucher = Discount::where('code', $request->code)
                ->where('active', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('expiry_date', '>=', now())
                ->first();

            if (!$voucher){
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher not valid'
                ]);
            }

            // Kiểm tra giá trị đơn hàng có đủ điều kiện để áp dụng voucher
            if ($cartValue < $voucher->cart_value){
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher not enough value order'
                ]);
            }

            // If product_ids is not null, check if any cart item matches
            if ($voucher->product_ids !== null) {
                $productIds = explode(',', $voucher->product_ids); // Convert product_ids string to an array of ids
                $cartItems = CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
                    $query->where([['user_id', $userId], ['store_id', $storeId]]);
                })->pluck('product_id')->toArray(); // Get product ids from cart_items for the user

                // Check if any product in the cart matches the voucher's product_ids
                $matchingProducts = array_intersect($productIds, $cartItems);

                if (empty($matchingProducts)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Voucher no matching products'
                    ]);
                }
            }

            // Tính toán giá trị giảm giá
            $value = $this->calculateDiscount($voucher, $cartValue);

            return response()->json([
                'status' => true,
                'voucher' => $voucher->id,
                'value' => $value,
                'message' => 'Voucher applied successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
        }

    }

    private function calculateDiscount($voucher, $cartValue)
    {
        if ($voucher->type == 'percentage') {
            // Giảm giá theo tỷ lệ phần trăm
            $discount = ($voucher->value / 100) * $cartValue;
            // Giới hạn mức giảm tối đa
            return min($discount, $voucher->sale_maximum);
        } else {
            // Giảm giá cố định
            return min($voucher->value, $voucher->sale_maximum);
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
