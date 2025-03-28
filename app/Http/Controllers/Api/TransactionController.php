<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaymentAccountResource;
use App\Http\Resources\PaymentWalletResource;
use App\Http\Resources\TransactionResource;
use App\Models\Customer;
use App\Models\PaymentAccount;
use App\Models\PaymentWallet;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;
use Validator;


class TransactionController extends BaseController
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transaction",
     *     tags={"Wallet"},
     *     summary="Get all transaction by user",
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="deposit, withdrawal, purchase",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="pending, failed, cancelled, completed",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all transactions"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $status = $request->status ?? '';
        $type = $request->type ?? '';

        try {
            $data = WalletTransaction::with('user')
                ->when($status != '', function ($query) use ($status) {
                    $query->where('status', $status);
                })->when($type != '', function ($query) use ($type) {
                    $query->where('type', $type);
                });

            $data = $data->where('user_id', auth('api')->id())->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(TransactionResource::collection($data), 'Get all transactions successfully.');

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/transaction/get_payment_wallet_provider",
     *     tags={"Wallet"},
     *     summary="Get all wallet provider",
     *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="is_active",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all wallet"),
     * )
     */
    public function getPaymentWallet(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $status = $request->is_active ?? '';

        try {
            $data = PaymentWallet::when($status != '', function ($query) use ($status) {
                $query->where('is_active', $status);
            });

            $data = $data->orderBy('name')->skip($offset)->take($limit)->get();

            return $this->sendResponse(PaymentWalletResource::collection($data), __('GET_LIST_SUCCESS'));

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transaction/get_payment_accounts",
     *     tags={"Wallet"},
     *     summary="Get all wallet provider",
     *     @OA\Parameter(
     *         name="is_verified",
     *         in="query",
     *         description="is_verified",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="payment_wallet_provider_id",
     *         in="query",
     *         description="payment_wallet_provider_id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="account_type",
     *         in="query",
     *         description="account_type",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all wallet"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getPaymentAccount(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $is_verified = $request->is_verified ?? '';
        $account_type = $request->account_type ?? '';
        $payment_wallet_provider_id = $request->payment_wallet_provider_id ?? '';

        try {
            $accountId = auth('api')->id();
            $data = PaymentAccount::with('payment_wallet')
                ->when($is_verified != '', function ($query) use ($is_verified) {
                    $query->where('is_verified', $is_verified);
                })->when($account_type != '', function ($query) use ($account_type) {
                    $query->where('account_type', $account_type);
                })->when($payment_wallet_provider_id != '', function ($query) use ($payment_wallet_provider_id) {
                    $query->where('payment_wallet_provider_id', $payment_wallet_provider_id);
                });

            $data = $data->where('account_id', $accountId)->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(PaymentAccountResource::collection($data), __('GET_LIST_SUCCESS'));

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/transaction/detail",
     *     tags={"Wallet"},
     *     summary="Get detail transaction by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the WalletTransaction",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="WalletTransaction not found"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:wallet_transactions,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $data = WalletTransaction::find($requestData['id']);

            return $this->sendResponse(new TransactionResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/transaction/create_payment_accounts",
     *     tags={"Wallet"},
     *     summary="Create payment account",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payment account object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="account_type", type="string", example="bank", description="bank, wallet"),
     *             @OA\Property(property="account_number", type="string", example="32132132", description="Account number"),
     *             @OA\Property(property="account_name", type="string", example="Account name", description="Account name"),
     *             @OA\Property(property="bank_name", type="string", example="Bank name", description="Bank name"),
     *             @OA\Property(property="payment_wallet_provider_id", type="integer", description="Id payment wallet"),
     *             @OA\Property(property="is_verified", type="integer", example="1", description="1:verify, 0:no"),
     *             @OA\Property(property="is_default ", type="integer", example="1", description="1:verify, 0:no"),
     *             @OA\Property(property="currency", type="string", example="eur", description="Currency"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function createPaymentAccount(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'account_type' => 'required|in:bank,wallet',
                'is_verified' => 'nullable|in:1,0',
                'is_default' => 'nullable|in:1,0',
                'payment_wallet_provider_id' => 'nullable|exists:payment_wallet_provider,id',
                'account_number' => 'required|max:120',
                'account_name' => 'required|max:120',
                'bank_name' => 'required|max:120',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $accountId = auth('api')->id();

            $requestData['account_id'] = $accountId;

            $isDefault = $request->is_default ?? 0;

            if($isDefault == 1) {
                \DB::table('payment_accounts')->where('account_id', $accountId)->update([
                    'is_default' => 0
                ]);
            }

            $data = PaymentAccount::create($requestData);

            \DB::commit();

            return $this->sendResponse(new PaymentAccountResource($data), __('PAYMENT_ACCOUNT_CREATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/update_payment_accounts",
     *     tags={"Wallet"},
     *     summary="Update payment account",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payment account object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID payment account"),
     *             @OA\Property(property="account_type", type="string", example="bank", description="bank, wallet"),
     *             @OA\Property(property="account_number", type="string", example="32132132", description="Account number"),
     *             @OA\Property(property="account_name", type="string", example="Account name", description="Account name"),
     *             @OA\Property(property="bank_name", type="string", example="Bank name", description="Bank name"),
     *             @OA\Property(property="payment_wallet_provider_id", type="integer", description="Id payment wallet"),
     *             @OA\Property(property="is_verified", type="integer", example="1", description="1:verify, 0:no"),
     *             @OA\Property(property="is_default ", type="integer", example="1", description="1:verify, 0:no"),
     *             @OA\Property(property="currency", type="string", example="eur", description="Currency"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function updatePaymentAccount(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:payment_accounts,id',
                'account_type' => 'nullable|in:bank,wallet',
                'is_verified' => 'nullable|in:1,0',
                'is_default' => 'nullable|in:1,0',
                'payment_wallet_provider_id' => 'nullable|exists:payment_wallet_provider,id',
                'account_number' => 'nullable|max:120',
                'account_name' => 'nullable|max:120',
                'bank_name' => 'nullable|max:120',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $id = $request->id;

            $accountId = auth('api')->id();

            $isDefault = $request->is_default ?? 0;

            if($isDefault == 1) {
                \DB::table('payment_accounts')->where('account_id', $accountId)->update([
                    'is_default' => 0
                ]);
            }

            $data = PaymentAccount::find($id);

            $data->update($requestData);

            $data->refresh();

            \DB::commit();

            return $this->sendResponse(new PaymentAccountResource($data), __('PAYMENT_ACCOUNT_UPDATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/transaction/delete_payment_accounts",
     *     tags={"Wallet"},
     *     summary="Update payment account",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payment account object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID payment account"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function deletePaymentAccount(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:payment_accounts,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $id = $request->id;
            PaymentAccount::destroy($id);
            return $this->sendResponse(null, __('PAYMENT_ACCOUNT_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/transaction/get_my_wallet",
     *     tags={"Wallet"},
     *     summary="My wallet",
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         example="usd",
     *         description="Currency",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet not found"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getMyWallet(Request $request)
    {

        try {
            $customer = auth('api')->user();

            $currency = $request->currency ?? 'usd';
            $wallet = $customer->getBalance($currency);
            $walletFrozen = $customer->getBalanceFrozen($currency);

            return $this->sendResponse([
                'available_balance' => $wallet,
                'frozen_balance' => $walletFrozen,
                'currency' => $currency
            ], __('GET_WALLET_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/request_topup",
     *     tags={"Wallet"},
     *     summary="Request topup transaciton",
     *     @OA\RequestBody(
     *         required=true,
     *         description="WalletTransaction object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="double", example="1000"),
     *             @OA\Property(property="currency", type="string", example="usd"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Request topup Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function requestTopup(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'amount' => 'required',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $customer = auth('api')->user();

//            $token = $request->stripe_token;
//            $paymentMethod = $this->stripeService->createPaymentMethod($token); // Tạo payment method từ token
//
//            // Nếu token không hợp lệ, trả về lỗi
//            if (!$paymentMethod || isset($paymentMethod['error'])) {
//                return $this->sendError('Invalid card details.');
//            }
            $amount = $request->amount;
            $priceWallet = $amount * (1 - 0.03);  // Equivalent to multiplying by 97%

            $requestData['price'] = $priceWallet;
            $requestData['base_price'] = $amount;
            $requestData['tax'] = 0;
            $requestData['fee'] = ($amount - $priceWallet);
            $requestData['currency'] = $request->currency ?? 'usd';
            $requestData['user_id'] = auth('api')->id();
            $requestData['transaction_date'] = now();
            $requestData['payment_method'] = 'card';
            $requestData['type'] = 'deposit';
            $requestData['description'] = 'Deposit ' . $amount . ' by ' . $customer->name ?? "";
            $requestData['status'] = 'pending';

            $data = WalletTransaction::create($requestData);

            //Tạo customer
            $customerS = $this->stripeService->createCustomer($customer);

            // Tạo PaymentIntent
            $paymentIntent = $this->stripeService->createPaymentIntent($request->amount, $request->currency, $data->code, $customerS);

            if (isset($paymentIntent['error'])) {
                return $this->sendError($paymentIntent['error']);
            }
            \DB::commit();

            // Trả lại client secret và orderId cho frontend
            return $this->sendResponse([
                'clientSecret' => $paymentIntent->client_secret,
                'orderId' => $data->code,
            ], 'Create payment successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/request_withdraw",
     *     tags={"Wallet"},
     *     summary="Withdrawal transaction",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wallet Transaction object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="double", example="1000"),
     *             @OA\Property(property="currency", type="string", example="usd"),
     *             @OA\Property(property="payment_account_id", type="integer", example="card"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Withdrawal Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function requestWithdraw(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'amount' => 'required',
                'payment_account_id' => 'nullable|exists:payment_accounts,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $customer = auth('api')->user();

            //Check money
            $currency = $request->currency ?? 'usd';
            $amount = $request->amount ?? 0;
            $money = $customer->getBalance($currency);
            if ($amount > $money) return $this->sendError(__('MONEY_NOT_ENOUGH'));

            $walletId = Wallet::getWalletId(auth('api')->id());

            //Tạo transaction
            $transaction = WalletTransaction::create([
                'user_id' => auth('api')->id(),
                'wallet_id' => $walletId,
                'transaction_type' => 'debit',
                'type' => 'withdrawal',
                'price' => $amount,
                'base_price' => $amount,
                'currency' => $currency,
                'payment_method' => $request->payment_method ?? 'card',
                'status' => 'pending',
                'transaction_date' => now(),
            ]);

            //Tạo yêu cầu rút tiền
            \DB::table('withdrawals')->insert([
                'wallet_id' => $walletId,
                'payment_account_id' => $request->payment_account_id,
                'user_id' => auth('api')->id(),
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method ?? 'card',
                'request_date' => now(),
                'transaction_id' => $transaction->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            //Update ví
            \DB::table('wallets')->where('id', $walletId)->update([
                'balance' => \DB::raw('balance - ' . (int)$amount),
                'frozen_balance' => \DB::raw('frozen_balance + ' . (int)$amount)
            ]);

            \DB::commit();

            // Trả lại client secret và orderId cho frontend
            return $this->sendResponse(null, __('REQUEST_WITHDRAW_SUCCESS'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/stripe_webhook",
     *     tags={"Wallet"},
     *     summary="Webhook confirm transaction",
     *     @OA\RequestBody(
     *         required=true,
     *         description="WalletTransaction object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="paymentIntentId", type="string", example="0964541340"),
     *             @OA\Property(property="orderId", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Webhook Confirm transaction Successful")
     * )
     */
    public function stripeWebhook(Request $request)
    {
        $requestData = $request->all();
        Log::info('---Webhook confirmPaymentTransaction---', [
            'input' => $requestData['data'],
        ]);

        // Gọi StripeService để xác nhận PaymentIntent
        $result = $this->stripeService->confirmPaymentTransaction($requestData);

        // Trả kết quả về client
        if (isset($result['success'])) {
            return $this->sendResponse($result['success'], "Payment Successfully");
        } else {
            return $this->sendError($result['error']);
        }

    }


    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        // Kiểm tra chữ ký webhook của Stripe
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, env('STRIPE_SECRET', 'sk_test_51QwQfYGbnQCWi1Bqpfc135wevKQRCr04P5QhgkE1QNlhdPePmeyMIOPQd7lFMynaVZDKhr206jqwIletM0M9NIG300UFR66XBW')
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        Log::info('---Webhook confirmPaymentTransaction---', [
            'event' => $event,
        ]);
        // Xử lý sự kiện
        switch ($event->type) {
            case 'payment_intent.canceled':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Cập nhật trạng thái giao dịch trong hệ thống của bạn, ví dụ:
                $order = WalletTransaction::where('code', $paymentIntent->id)->first();
                if ($order) {
                    $order->status = 'canceled';
                    $order->save();
                }
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Cập nhật trạng thái giao dịch thành công trong hệ thống của bạn, ví dụ:
                $order = WalletTransaction::where('code', $paymentIntent->id)->first();
                if ($order) {
                    $order->status = 'completed';
                    $order->save();
                }
                break;
            // Các sự kiện khác mà bạn cần xử lý
        }

        return response()->json(['status' => 'success']);
    }


}
