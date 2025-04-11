<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

use Stripe\Webhook;
use App\Models\Order;

class StripeController extends Controller
{
    public function __construct()
    {
        // Thiết lập khóa secret của Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    // Tạo một Checkout Session
    public function createCheckoutSession(Request $request)
    {
        try {
            // Giả sử bạn đã có thông tin đơn hàng và khách hàng
            $orderId = 'ORD' . uniqid();  // Ví dụ order ID, có thể lấy từ database
            // Giả sử bạn lấy từ request
            $customerName = $request->name ?? 'Dinh Duong';
            $customerEmail = $request->email ?? 'duyksqt1996@gmail.com';



            // Giả sử bạn lấy giỏ hàng từ session hoặc database
            $items = [
                ['name' => 'Silver', 'price' => 1000, 'quantity' => 1], // Mỗi sản phẩm có price tính bằng cents (ví dụ 1000 cents = 10 EUR)
                ['name' => 'Gold', 'price' => 2000, 'quantity' => 2], // Ví dụ sản phẩm Gold với 2 sản phẩm
            ];

            // Tính tổng tiền cho đơn hàng
            $totalAmount = 3000;

            $description = "Payment order code #{$orderId}";

            // Tạo khách hàng trên Stripe
            $customer = \Stripe\Customer::create([
                'name'  => $customerName,
                'email' => $customerEmail,
            ]);

            // Tạo session thanh toán
            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'customer' => $customer->id, // 🔄 Gán theo customer object thay vì chỉ email
                'line_items' => array_map(function($item) {
                    return [
                        'price_data' => [
                            'currency' => 'eur',  // Đổi currency sang EUR
                            'product_data' => [
                                'name' => $item['name'],
                            ],
                            'unit_amount' => $item['price'], // Giá của sản phẩm (tính bằng cents)
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }, $items),
                'metadata' => [
                    'order_id' => $orderId,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'total_amount' => $totalAmount,
                    "description" => $description,
                ],
                'success_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ]);

            // Trả về URL của Checkout Session
            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function createPaymentLinkWithQR(Request $request)
    {
        try {
            // Giả sử bạn đã có thông tin đơn hàng và khách hàng
            $orderId = 'ORD' . uniqid();  // Ví dụ order ID, có thể lấy từ database
            // Giả sử bạn lấy từ request
            $customerName = $request->name ?? 'Dinh Duong';
            $customerEmail = $request->email ?? 'duyksqt1996@gmail.com';



            // Giả sử bạn lấy giỏ hàng từ session hoặc database
            $items = [
                ['name' => 'Silver', 'price' => 1000, 'quantity' => 1], // Mỗi sản phẩm có price tính bằng cents (ví dụ 1000 cents = 10 EUR)
                ['name' => 'Gold', 'price' => 2000, 'quantity' => 2], // Ví dụ sản phẩm Gold với 2 sản phẩm
            ];

            // Tính tổng tiền cho đơn hàng
            $totalAmount = 3000;

            $description = "Payment order code #{$orderId}";

            // Tạo khách hàng trên Stripe
            $customer = \Stripe\Customer::create([
                'name'  => $customerName,
                'email' => $customerEmail,
            ]);

            // Tạo session thanh toán
            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'customer' => $customer->id, // 🔄 Gán theo customer object thay vì chỉ email
                'line_items' => array_map(function($item) {
                    return [
                        'price_data' => [
                            'currency' => 'eur',  // Đổi currency sang EUR
                            'product_data' => [
                                'name' => $item['name'],
                            ],
                            'unit_amount' => $item['price'], // Giá của sản phẩm (tính bằng cents)
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }, $items),
                'metadata' => [
                    'order_id' => $orderId,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'total_amount' => $totalAmount,
                    "description" => $description,
                ],
                'success_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ]);

            // Tạo QR code từ session URL
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($session->url);
            return response()->json([
                'session_url' => $session->url,
                'qr_code_url' => $qrCodeUrl,
                'session_id' => $session->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleStripeWebhook(Request $request)
    {
        // Đặt khóa bí mật Stripe của bạn
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd'));

        // Lấy body và signature Stripe
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = 'whsec_Moi2yWCxURz1q2gcz6LCurB4qD7aOH6i';  // Thay thế bằng secret của webhook

        try {
            // Kiểm tra tính hợp lệ của webhook bằng signature
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Kiểm tra loại sự kiện
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;  // Lấy thông tin session thanh toán

                // Lấy thông tin order_id từ metadata
                $orderId = $session->metadata->order_id;

                // Cập nhật trạng thái đơn hàng trong database
                $order = Order::where('order_id', 2)->first();

                if ($order) {
                    // Cập nhật trạng thái đơn hàng
                    $order->status = 'completed';  // Đặt trạng thái là 'paid'
                    $order->save();
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra, trả về lỗi
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
