<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use App\Models\Customer;
use App\Models\Wallet;
use App\Models\WalletTransaction;
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
     *     tags={"Wallet Transaction"},
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

        $customer = Customer::getAuthorizationUser($request);


        try {
            $data = WalletTransaction::with('user')
                ->when($status != '', function ($query) use ($status) {
                    $query->where('status', $status);
                })->when($type != '', function ($query) use ($type) {
                    $query->where('type', $type);
                });

            $data = $data->where('user_id', $customer->id)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(TransactionResource::collection($data), 'Get all transactions successfully.');

        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/transaction/detail",
     *     tags={"Wallet Transaction"},
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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/transaction/get_my_wallet",
     *     tags={"Wallet Transaction"},
     *     summary="My wallet",
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
        $customer = Customer::getAuthorizationUser($request);


        try {

            $wallet = $customer->getBalance();

            return $this->sendResponse($wallet, __('GET_WALLET_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/request_topup",
     *     tags={"Wallet Transaction"},
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
        $customer = Customer::getAuthorizationUser($request);

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
            $requestData['currency'] = $request->currency ?? 'usd';
            $requestData['user_id'] = $customer->id;
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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/request_withdraw",
     *     tags={"Wallet Transaction"},
     *     summary="Withdrawal transaction",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wallet Transaction object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="double", example="1000"),
     *             @OA\Property(property="currency", type="string", example="usd"),
     *             @OA\Property(property="payment_method", type="string", example="card"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Withdrawal Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function requestWithdraw(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);

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
            //Check money
            $amount = $request->amount ?? 0;
            $money = $customer->getBalance();
            if ($amount > $money) return $this->sendError('api.money_not');

            $priceWallet = $amount * (1 - 0.03);  // Equivalent to multiplying by 97%

            $requestData['price'] = -$priceWallet;
            $requestData['price_base'] = -$amount;
            $requestData['currency'] = $request->currency ?? 'usd';
            $requestData['user_id'] = $customer->id;
            $requestData['transaction_date'] = now();
            $requestData['payment_method'] = $request->payment_method ?? 'card';
            $requestData['type'] = 'withdrawal';
            $requestData['description'] = 'Withdrawal ' . $amount . ' by ' . $customer->name;
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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/transaction/stripe_webhook",
     *     tags={"Wallet Transaction"},
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


}
