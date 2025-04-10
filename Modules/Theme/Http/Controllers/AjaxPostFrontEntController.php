<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
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

    public function submitOrder(Request $request)
    {
        $requestData = $request->all();
        dd($requestData);

        $validator = \Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'voucher_id' => 'nullable|exists:discounts,id',
                'payment_type' => 'required|in:ship,pickup',
                'payment_method' => 'required|in:pay_cash,pay_stripe',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
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

            Notification::insertNotificationByUser($title, $description, '', 'order', optional($order->store)->creator_id, $order->id);

            \DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Order successfully'
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
                'user_id' => auth('api')->id(),
                'payment_method' => 'card',
                'type' => 'purchase',
                'status' => 'pending',
                'order_id' => $order->id,
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            // Call Stripe payment method
//            $customerS = $this->stripeService->createCustomer($order->customer);
//            $paymentIntent = $this->stripeService->createPaymentIntent($orderPrice, $order->currency ?? 'eur', $transaction->code, $customerS, $order->code);
//
//            if (isset($paymentIntent['error'])) {
//                return response()->json([
//                    'status' => false,
//                    'message' => $paymentIntent['error']
//                ]);
//            }

            \DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Order successfully',
//                'clientSecret' => $paymentIntent->client_secret,
                'order' => new OrderResource($order),
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function createOrder($cart, $paymentMethod, $request)
    {
        //Request data
        $paymentType = $request->payment_type ?? 'delivery';
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
            'payment_type' => $paymentType,
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
        $userId = auth('api')->id() ?? 0;

        $cart = Cart::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->first();

        if (!$cart) {
            throw new \Exception(__('CART_EMPTY'));
        }

        return $cart;
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
