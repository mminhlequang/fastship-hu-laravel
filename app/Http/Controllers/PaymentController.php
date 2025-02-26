<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createPayment(Request $request)
    {
        $billingDetails = [
            'name' => 'Dinh DUong',
            'email' => 'duyksqt1996@gmail.com',
            'address' => [
                'city' => 'City'
            ],
        ];
        $token = $request->token; // Lấy token từ frontend
        $paymentMethod = $this->stripeService->createPaymentMethod($token); // Tạo payment method từ token

        // Nếu token không hợp lệ, trả về lỗi
        if (!$paymentMethod || isset($paymentMethod['error'])) {
            return response()->json(['error' => 'Invalid card details.']);
        }

        // Tạo đơn hàng sau khi thẻ hợp lệ
        $order = Booking::create([
            'customer_id' => 1,
            'total_price' => $request->amount,
            'currency' => $request->currency,
        ]);

        // Tạo PaymentIntent
        $paymentIntent = $this->stripeService->createPaymentIntent($request->amount, $request->currency, $order->code);

        if (isset($paymentIntent['error'])) {
            return response()->json(['error' => $paymentIntent['error']]);
        }

        // Trả lại client secret và orderId cho frontend
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'orderId' => $order->id,
        ]);
    }


    public function confirmPayment(Request $request)
    {
        // Gọi StripeService để xác nhận PaymentIntent
        $result = $this->stripeService->confirmPayment($request->paymentIntentId, $request->orderId);

        // Trả kết quả về client
        if (isset($result['success'])) {
            return response()->json(['success' => $result['success']]);
        } else {
            return response()->json(['error' => $result['error']], 400);
        }
    }

    public function createCustomer(Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');

        // Tạo Customer
        $customer = $this->stripeService->createCustomer($email, $name);

        if ($customer) {
            return response()->json([
                'customer_id' => $customer->id,
            ]);
        }

        return response()->json(['error' => 'Không thể tạo khách hàng'], 500);
    }
}
