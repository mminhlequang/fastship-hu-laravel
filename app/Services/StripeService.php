<?php

namespace App\Services;

use App\Models\Booking;
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

    public function createPaymentIntent($amount, $currency = 'usd', $orderId, $customer)
    {
        try {
            // Tạo PaymentIntent cho giao dịch thanh toán
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe yêu cầu số tiền ở đơn vị cents
                'currency' => $currency,
                'customer' => $customer->id ?? "",
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
                    'customer_id' => $customer['metadata']['user_id'] ?? "", // Lưu order_id vào metadata của PaymentIntent
                ],
                "description" => "Payment recharge striped ID " . $orderId,
                'payment_method_types' => ['card'],
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            dd($e->getMessage());
            return null;
        }
    }

    // Xử lý xác nhận PaymentIntent
    public function confirmPaymentTransaction($requestData)
    {
        $paymentIntentId = $requestData['data']['object']['id'] ?? "";
        $orderId = $requestData['data']['object']['metadata']['order_id'] ?? "";

        // Lấy PaymentIntent từ Stripe
        \DB::beginTransaction();
        try {

            // Lấy đơn hàng từ DB
            $transaction = WalletTransaction::where('code', $orderId)->first();
            if (!$transaction) {
                return ['error' => 'WalletTransaction not found'];
            }

            // Stripe API key của bạn
            Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW'));

            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);


            $walletId = Wallet::getWalletId($transaction->user_id);

            if ($paymentIntent->status === 'succeeded') {
                //Cộng tiền vào ví
                \DB::table('wallet')->where('id', $walletId)->increment('balance', $transaction->price);

                // Nếu thanh toán đã thành công, cập nhật trạng thái đơn hàng
                $transaction->status = 'completed';
                $transaction->wallet_id = $walletId;
                $transaction->payment_method = 'card';
                $transaction->transaction_id = $paymentIntent->id ?? null;
                $transaction->transaction_date = now();
                $transaction->metadata = $requestData['data'] ?? null;
                $transaction->save();

                return ['success' => 'Payment has already been completed'];
            }

            // Nếu trạng thái chưa thành công, xác nhận PaymentIntent
//            $paymentIntent->confirm();

            // Nếu trạng thái chưa thành công, cần kiểm tra xem PaymentMethod có gắn với Customer chưa
            $customer = \Stripe\Customer::retrieve($paymentIntent->customer);

            if (!$paymentIntent->payment_method) {
                // Nếu PaymentMethod chưa được gắn vào PaymentIntent, gắn một PaymentMethod
                $paymentMethod = PaymentMethod::retrieve($requestData['data']['object']['payment_method']);

                // Gắn payment method vào customer
                $paymentMethod->attach([
                    'customer' => $customer->id,
                ]);

                // Cập nhật customer để sử dụng payment method mặc định
                $customer->update([
                    'invoice_settings' => [
                        'default_payment_method' => $paymentMethod->id,
                    ],
                ]);
            }

            // Sau khi gắn PaymentMethod vào Customer, xác nhận PaymentIntent
            $paymentIntent->confirm([
                'payment_method' => $paymentMethod->id,
            ]);

            // Kiểm tra trạng thái PaymentIntent
            Log::info('---$paymentIntent->status---', [
                'paymentIntent' => $paymentIntent->status,
            ]);

            if ($paymentIntent->status === 'succeeded') {
                //Cộng tiền vào ví
                \DB::table('wallet')->where('id', $walletId)->increment('balance', $transaction->price);
                // Nếu thanh toán đã thành công, cập nhật trạng thái đơn hàng
                $transaction->status = 'completed';
                $transaction->wallet_id = $walletId;
                $transaction->payment_method = 'card';
                $transaction->transaction_id = $paymentIntent->id ?? null;
                $transaction->transaction_date = now();
                $transaction->metadata = $requestData['data'] ?? null;
                $transaction->save();

                return ['success' => 'Payment has already been completed'];
            }else {
                return ['error' => 'Payment failed'];
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::info('Payment confirmation failed:' . $e->getMessage());
            // Xử lý lỗi nếu có
            return ['error' => 'Payment confirmation failed: ' . $e->getMessage()];
        }
    }

    // Xử lý xác nhận PaymentIntent
    public function confirmPayment($paymentIntentId, $orderId)
    {
        // Lấy đơn hàng từ DB
        $order = Booking::find($orderId);
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