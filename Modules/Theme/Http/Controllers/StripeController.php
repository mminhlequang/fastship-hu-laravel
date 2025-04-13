<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

use Stripe\Webhook;
use App\Models\Order;

class StripeController extends Controller
{
    public function __construct()
    {
        // Thiết lập khóa secret của Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function handleStripeWebhook(Request $request)
    {
        // Đặt khóa bí mật Stripe của bạn
        Stripe::setApiKey('sk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd');

        // Lấy body và signature Stripe
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = 'whsec_oIeRNr87Ljz9Uq4g6fcOFBZg8twmklM5';  // Thay thế bằng secret của webhook
        try {
            // Kiểm tra tính hợp lệ của webhook bằng signature
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);



            // Kiểm tra loại sự kiện
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;  // Lấy thông tin session thanh toán

                // Lấy thông tin order_id từ metadata
                $orderId = $session->metadata->order_code;

                $transactionId = $session->metadata->order_id;

                // Kiểm tra trạng thái PaymentIntent
                Log::info('---$paymentIntent->status web---', [
                    'type' => $event->type ?? '',
                    'order_code' => $orderId ?? '',
                    'transaction_code' => $transactionId ?? '',
                ]);

                // Cập nhật trạng thái đơn hàng trong database
                $order = Order::where('code', $orderId)->first();

                $transaction = WalletTransaction::where('code', $transactionId)->first();

                if($transaction){
                    $transaction->status = 'completed';
                    $transaction->transaction_id = $session->payment_intent ?? null;
                    $transaction->transaction_date = now();
                    $transaction->metadata = $session->metadata ?? null;
                    $transaction->save();
                }

                if ($order) {
                    // Cập nhật trạng thái đơn hàng
                    $order->payment_status = 'completed';  // Đặt trạng thái là 'paid'
                    $order->payment_date = now();
                    $transaction->payment_intent_id = $session->payment_intent ?? null;
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
