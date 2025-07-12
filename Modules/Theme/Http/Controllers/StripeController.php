<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

use Stripe\Webhook;
use App\Models\Order;
use Stripe\Checkout\Session;

use App\Models\Wallet;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    public function __construct()
    {
        // Thiết lập khóa secret của Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function handleStripeWebhook(Request $request)
    {
        // Đặt Stripe API Key
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET', 'whsec_oIeRNr87Ljz9Uq4g6fcOFBZg8twmklM5');

        try {
            // Xác thực webhook
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Xử lý sự kiện checkout.session.completed
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;

                // Lấy metadata
                $orderCode = $session->metadata->order_code ?? null;
                $transactionCode = $session->metadata->order_id ?? null;
                $paymentIntentId = $session->payment_intent ?? null;

                Log::info('--- Stripe Webhook Received ---', [
                    'type' => $event->type ?? '',
                    'order_code' => $orderCode ?? '',
                    'transaction_code' => $transactionCode ?? '',
                    'payment_intent_id' => $paymentIntentId ?? ''
                ]);

                // Tìm giao dịch
                $transaction = WalletTransaction::where('code', $transactionCode)->first();

                if (!$transaction) {
                    return response()->json(['error' => 'WalletTransaction not found'], 400);
                }

                // Xác nhận PaymentIntent với Stripe
                $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

                if ($paymentIntent->status === 'succeeded') {
                    if ($transaction->status !== 'completed') {
                        $walletId = null;

                        if ($transaction->order_id == null) {
                            // Đây là giao dịch nạp tiền vào ví
                            $walletId = Wallet::getWalletId($transaction->user_id, $transaction->currency);
                            \DB::table('wallets')->where('id', $walletId)->increment('balance', $transaction->price);
                            $transaction->wallet_id = $walletId;
                        }

                        // Cập nhật giao dịch
                        $transaction->status = 'completed';
                        $transaction->transaction_id = $paymentIntentId;
                        $transaction->transaction_date = now();
                        $transaction->metadata = $session->metadata ?? null;
                        $transaction->save();
                    }

                    // Nếu có đơn hàng liên quan => cập nhật đơn hàng
                    if ($transaction->order_id) {
                        $order = Order::find($transaction->order_id);
                        if ($order) {
                            $order->update([
                                'payment_intent_id' => $paymentIntentId,
                                'payment_status' => 'completed',
                                'payment_date' => now()
                            ]);
                        }
                    }

                    return response()->json(['status' => 'success']);
                } else {
                    return response()->json(['error' => 'Payment not succeeded'], 400);
                }
            }

            return response()->json(['status' => 'ignored']);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function handleStripeWebhookOld(Request $request)
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


    public function paymentSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session_id = $request->get('session_id');

        if (!$session_id) {
            return redirect('')->with('error', 'Session ID not found.');
        }

        $session = Session::retrieve($session_id);

        // You can access metadata here
        $metadata = $session->metadata;

        return view('theme::front-end.auth.find_driver', compact('metadata'));
    }
}
