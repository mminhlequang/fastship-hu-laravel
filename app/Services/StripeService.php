<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        // Thiết lập API Key cho Stripe
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW'));
    }

    /**
     * Tạo PaymentMethod từ Token của Stripe
     *
     * @param string $token - Token thẻ được tạo từ frontend
     * @return array|PaymentMethod - Trả về đối tượng PaymentMethod nếu thành công, hoặc lỗi nếu có
     */
    public function createPaymentMethod($token)
    {
        try {
            // Tạo PaymentMethod từ token
            $paymentMethod = PaymentMethod::create([
                'type' => 'card', // Chỉ định loại phương thức thanh toán
                'card' => [
                    'token' => $token, // Token thẻ từ frontend
                ],
            ]);

            return $paymentMethod; // Trả về đối tượng PaymentMethod nếu thành công
        } catch (ApiErrorException $e) {
            // Nếu có lỗi, trả về thông tin lỗi
            return ['error' => $e->getMessage()];
        }
    }

    public function createPaymentIntent($amount, $currency = 'eur', $orderId, $customer, $orderCode = null)
    {
        try {
            $description = ($orderCode != null) ? "Payment order code " . $orderCode : "Payment recharge striped ID " . $orderId;
            // Tạo PaymentIntent cho giao dịch thanh toán
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe yêu cầu số tiền ở đơn vị cents
                'currency' => $currency,
                'customer' => $customer,
                "shipping" => [
                    "name" => $customer->name ?? "",
                    "address" => [
                        "line1" => $customer['address']['city'] ?? "",
                        "postal_code" => $customer['address']['postal_code'] ?? "",
                        "city" => $customer['address']['city'] ?? "",
                        "state" => $customer['address']['state'] ?? "",
                        "country" => $customer['address']['country'] ?? ""
                    ],
                ],
                'metadata' => [
                    'order_id' => $orderId, // Lưu order_id vào metadata của PaymentIntent
                    'order_code' => $orderCode, // Lưu order_id vào metadata của PaymentIntent
                    'customer_id' => $customer['metadata']['user_id'] ?? "", // Lưu order_id vào metadata của PaymentIntent
                ],
                "description" => $description,
                'payment_method_types' => ['card'],
            ]);
            return $paymentIntent;
        } catch (ApiErrorException $e) {
            return null;
        }
    }

    // Xử lý xác nhận PaymentIntent
    public function confirmPaymentTransaction($requestData)
    {
        $paymentIntentId = $requestData['data']['object']['id'] ?? "";
        $orderId = $requestData['data']['object']['metadata']['order_id'] ?? "";

        // Lấy PaymentIntent từ Stripe
        try {
            // Lấy đơn hàng từ DB
            $transaction = WalletTransaction::where('code', $orderId)->first();
            if (!$transaction) {
                return ['error' => 'WalletTransaction not found'];
            }

            // Stripe API key của bạn
            Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW'));

            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            // Kiểm tra trạng thái PaymentIntent
            Log::info('---$paymentIntent->status---', [
                'paymentIntent' => $paymentIntent->status,
                'transaction' => $orderId
            ]);

            if ($paymentIntent->status === 'succeeded') {
                if ($transaction->order_id == null) {
                    //Cộng tiền vào ví
                    $walletId = Wallet::getWalletId($transaction->user_id);
                    $priceWallet = $transaction->price;
                    \DB::table('wallets')->where('id', $walletId)->increment('balance', $priceWallet);
                    // Nếu thanh toán đã thành công, cập nhật trạng thái đơn hàng
                    $transaction->status = 'completed';
                    $transaction->wallet_id = $walletId;
                    $transaction->transaction_id = $paymentIntent->id ?? null;
                    $transaction->transaction_date = now();
                    $transaction->metadata = $requestData['data'] ?? null;
                    $transaction->save();

                    return ['success' => 'Payment has already been completed'];
                }
                if ($transaction->order_id != null) {
                    $transaction->status = 'completed';
                    $transaction->transaction_id = $paymentIntent->id ?? null;
                    $transaction->transaction_date = now();
                    $transaction->metadata = $requestData['data'] ?? null;
                    $transaction->save();

                    $order = Order::find($transaction->order_id);
                    if ($order) {
                        $order->update([
                            'payment_intent_id' => $paymentIntent->id ?? null,
                            'payment_status' => 'completed',
                            'payment_date' => now()
                        ]);
                        return ['success' => 'Payment has already been completed'];
                    }
                }

            }

        } catch (\Exception $e) {
            Log::info('Payment confirmation failed:' . $e->getMessage());
            // Xử lý lỗi nếu có
            return ['error' => 'Payment confirmation failed: ' . $e->getMessage()];
        }
    }

    // Xử lý xác nhận PaymentIntent
    public function confirmPayment($paymentIntentId, $orderId)
    {
        // Lấy đơn hàng từ DB
        $order = Order::find($orderId);
        if (!$order) {
            return ['error' => 'Order not found'];
        }

        // Stripe API key của bạn
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW'));

        // Lấy PaymentIntent từ Stripe
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            // Kiểm tra trạng thái PaymentIntent
            if ($paymentIntent->status === 'succeeded') {
                // Nếu thanh toán đã thành công, cập nhật trạng thái đơn hàng
                $order->payment_status = $paymentIntent->status;
                $order->payment_method = $paymentIntent->payment_method_types[0] ?? 'card';
                $order->payment_intent_id = $paymentIntent->id;
                $order->approve_id = 4;
                $order->save();

                return ['success' => 'Payment has already been completed'];
            }

            // Nếu trạng thái chưa thành công, xác nhận PaymentIntent
            $paymentIntent->confirm();

            // Cập nhật trạng thái đơn hàng thành "completed" khi thanh toán thành công
            if ($paymentIntent->status === 'succeeded') {
                $order->approve_id = 4;
                $order->save();
                return ['success' => 'Payment successful'];
            } else {
                return ['error' => 'Payment failed'];
            }
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return ['error' => 'Payment confirmation failed: ' . $e->getMessage()];
        }
    }

    public function createCustomer($customer)
    {
        try {
            // Tạo Customer trong Stripe
            $customer = Customer::create([
                'name' => $customer->name,
                'phone' => $customer->phone,
//                'email' => $customer->email,
                'address' => [
                    'city' => $customer->city, // City
                    'state' => $customer->state, // State
                    'country' => $customer->country, // Country code (e.g., 'US')
                ],
                'metadata' => [
                    'user_id' => $customer->uid, // You can also attach custom metadata
                ],
            ]);

            return $customer;
        } catch (ApiErrorException $e) {
            return null;
        }
    }
}