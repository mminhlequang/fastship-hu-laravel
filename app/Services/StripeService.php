<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Transaction;
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
        Stripe::setApiKey(env('STRIPE_SECRET'));
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
//                'billing_details' => $billingDetails, // Thêm billing details vào đây
            ]);

            return $paymentMethod; // Trả về đối tượng PaymentMethod nếu thành công
        } catch (ApiErrorException $e) {
            // Nếu có lỗi, trả về thông tin lỗi
            return ['error' => $e->getMessage()];
        }
    }

    public function createPaymentIntent($amount, $currency = 'usd', $orderId)
    {
        try {
            // Tạo PaymentIntent cho giao dịch thanh toán
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe yêu cầu số tiền ở đơn vị cents
                'currency' => $currency,
                'metadata' => [
                    'order_id' => $orderId, // Lưu order_id vào metadata của PaymentIntent
                ],
                "description" => "Payment recharge striped orderID ".$orderId,
                'payment_method_types' => ['card'],
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            return null;
        }
    }

    // Xử lý xác nhận PaymentIntent
    public function confirmPaymentTransaction($paymentIntentId, $orderId)
    {
        // Lấy đơn hàng từ DB
        $transaction = Transaction::where('code', $orderId)->first();
        if (!$transaction) {
            return ['error' => 'Transaction not found'];
        }

        // Stripe API key của bạn
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW'));

        // Lấy PaymentIntent từ Stripe
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            // Kiểm tra trạng thái PaymentIntent
            if ($paymentIntent->status === 'succeeded') {
                // Nếu thanh toán đã thành công, cập nhật trạng thái đơn hàng
                $transaction->status = 'completed';
                $transaction->payment_method = $paymentIntent->payment_method_types[0] ?? 'card';
                $transaction->payment_intent_id = $paymentIntent->id ?? null;
                $transaction->transaction_date = now();
                $transaction->save();

                return ['success' => 'Payment has already been completed'];
            }

            // Nếu trạng thái chưa thành công, xác nhận PaymentIntent
            $paymentIntent->confirm();

            // Cập nhật trạng thái đơn hàng thành "completed" khi thanh toán thành công
            if ($paymentIntent->status === 'succeeded') {
                $transaction->status = 'completed';
                $transaction->payment_method = $paymentIntent->payment_method_types[0] ?? 'card';
                $transaction->payment_intent_id = $paymentIntent->id ?? null;
                $transaction->transaction_date = now();
                $transaction->save();
                return ['success' => 'Payment successful'];
            } else {
                return ['error' => 'Payment failed'];
            }
        } catch (\Exception $e) {
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

    public function createCustomer($email, $name)
    {
        try {
            // Tạo Customer trong Stripe
            $customer = Customer::create([
                'email' => $email,
                'name' => $name,
            ]);

            return $customer;
        } catch (ApiErrorException $e) {
            return null;
        }
    }
}