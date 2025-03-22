<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ApproveResource;
use App\Http\Resources\OrderResource;
use App\Models\Approve;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Validator;

class OrderController extends BaseController
{

    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order/get_orders_by_user",
     *     tags={"Order"},
     *     summary="Get all order",
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="Keywords order",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="approve_id",
     *         in="query",
     *         description="Status order",
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
     *     @OA\Response(response="200", description="Get all order"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getOrdersByUser(Request $request)
    {

        // Default limit and offset values
        $keywords = $request->keywords ?? '';
        $approveId = $request->approve_id ?? '';
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $userId = \Auth::id() ?? 0;
            $orders = Order::with('orderItems')
                ->when($keywords != '', function ($query) use ($keywords) {
                    $query->whereHas('orderItems', function ($query) use ($keywords) {
                        $query->where('product', 'like', "%$keywords%");
                    });
                })
                ->when($approveId != '', function ($query) use ($approveId) {
                    $query->where('approve_id', $approveId);
                })
                ->where('user_id', $userId)
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(OrderResource::collection($orders), __('GET_ORDER_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Get(
     *     path="/api/v1/order/get_orders_by_store",
     *     tags={"Order"},
     *     summary="Get all order",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store_id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="approve_id",
     *         in="query",
     *         description="Status order",
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
     *     @OA\Response(response="200", description="Get all order"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getOrdersByStore(Request $request)
    {

        // Default limit and offset values
        $storeId = $request->store_id ?? '';
        $approveId = $request->approve_id ?? '';
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $orders = Order::with('orderItems')
                ->when($storeId != '', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                })
                ->when($approveId != '', function ($query) use ($approveId) {
                    $query->where('approve_id', $approveId);
                })
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(OrderResource::collection($orders), __('GET_ORDER_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/order/get_approves",
     *     tags={"Order"},
     *     summary="Get all approve order",
     *     @OA\Response(response="200", description="Get all approve "),
     * )
     */
    public function getListApprove(Request $request)
    {
        try {
            $data = Approve::orderBy('arrange')->get();
            return $this->sendResponse(ApproveResource::collection($data), __('GET_APPROVE_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/order/create",
     *     tags={"Order"},
     *     summary="Create order",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Cart object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của store."),
     *          @OA\Property(property="payment_type", type="string", example="delivery", description="Hình thúc nhận hàng(delivery, pickup)"),
     *          @OA\Property(property="payment_method", type="string", example="pay_cash", description="Hình thức thanh toán(pay_cash, pay_stripe)"),
     *          @OA\Property(property="note", type="string", description="Ghi chú"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create cart Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function create(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'payment_type' => 'required|in:delivery,pickup',
                'payment_method' => 'required|in:pay_stripe,pay_cash',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            if ($request->payment_method === 'pay_stripe') {
                return $this->createStripePayment($request);
            }
            return $this->createCashPayment($request);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    private function createOrder($cart, $paymentMethod, $paymentType = 'delivery')
    {
        // Fetch cart items
        $cartItems = $cart->cartItems()->get();
        if ($cartItems->isEmpty()) {
            throw new \Exception(__('CART_EMPTY'));
        }

        // Total price
        $totalPrice = $cartItems->sum('price');

        // Create or update order
        $order = Order::updateOrCreate(
            ['user_id' => $cart->user_id, 'store_id' => $cart->store_id, 'payment_method' => $paymentMethod, 'total_price' => $totalPrice, 'payment_status' => 'pending'],
            [
                'total_price' => $totalPrice,
                'currency' => 'eur',
                'payment_type' => $paymentType,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'approve_id' => 1
            ]
        );

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

        // Loop through the cart items and update or create order items
        foreach ($cartItems as $cartItem) {
            $order->orderItems()->updateOrCreate(
                ['product_id' => $cartItem->product_id, 'order_id' => $order->id], // The unique fields for matching
                [
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'product' => $cartItem->product,
                    'variations' => $cartItem->variations,
                    'toppings' => $cartItem->toppings,
                ]
            );
        }

        // Save order items
//        $order->orderItems()->saveMany($orderItems);


        return $order;
    }

    private function createCashPayment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cart = $this->getCart($request);
            $paymentType = $request->payment_type ?? 'delivery';
            $order = $this->createOrder($cart, 'pay_cash', $paymentType);
            \DB::commit();
            return $this->sendResponse(new OrderResource($order), __('errors.ORDER_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    private function createStripePayment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cart = $this->getCart($request);
            $paymentType = $request->payment_type ?? 'delivery';
            $order = $this->createOrder($cart, 'pay_stripe', $paymentType);
            //Save transaction
            $transaction = WalletTransaction::create([
                'price' => $order->total_price,
                'base_price' => $order->total_price,
                'fee' => 0,
                'currency' => 'eur',
                'user_id' => \Auth::id(),
                'transaction_date' => now(),
                'payment_method' => 'card',
                'type' => 'purchase',
                'status' => 'pending',
                'order_id' => $order->id
            ]);
            // Call Stripe payment method
            $customerS = $this->stripeService->createCustomer($order->customer);
            $paymentIntent = $this->stripeService->createPaymentIntent($order->total_price, $order->currency ?? 'eur', $transaction->code, $customerS);
            if (isset($paymentIntent['error'])) {
                return $this->sendError($paymentIntent['error']);
            }

            \DB::commit();
            return $this->sendResponse([
                'clientSecret' => $paymentIntent->client_secret,
                'order' => new OrderResource($order),
            ], 'Create payment successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    private function getCart(Request $request)
    {
        $storeId = $request->store_id;
        $userId = \Auth::id() ?? 0;

        $cart = Cart::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->first();

        if (!$cart) {
            throw new \Exception(__('CART_EMPTY'));
        }

        return $cart;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/order/cancel",
     *     tags={"Order"},
     *     summary="Cancel order",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete order",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID order"),
     *             @OA\Property(property="cancel_note", type="string", example="1", description="Lý ho huỷ"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delete successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function delete(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:orders,id',
            'cancel_note' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $id = $request->id;
            // Tìm cart item theo ID
            $data = Order::find($id);

            $data->update([
                'approve_id' => 5,
                'cancel_note' => $request->cancel_note ?? '',
            ]);

            return $this->sendResponse(null, __('ORDER_CANCEL'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


}
