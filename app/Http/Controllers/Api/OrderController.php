<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\OrderResource;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
     *         name="payment_status",
     *         in="query",
     *         description="Status order",
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
     *     @OA\Response(response="200", description="Get all order"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getOrdersByUser(Request $request)
    {

        // Default limit and offset values
        $keywords = $request->keywords ?? '';
        $paymentStatus = $request->payment_status ?? '';
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
                ->when($paymentStatus != '', function ($query) use ($paymentStatus) {
                    $query->where('payment_status', $paymentStatus);
                })
                ->where('user_id', $userId)
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(OrderResource::collection($orders), __('GET_ORDER_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *         name="payment_status",
     *         in="query",
     *         description="Status order",
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
     *     @OA\Response(response="200", description="Get all order"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getOrdersByStore(Request $request)
    {

        // Default limit and offset values
        $storeId = $request->store_id ?? '';
        $paymentStatus = $request->payment_status ?? '';
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $orders = Order::with('orderItems')
                ->when($storeId != '', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                })
                ->when($paymentStatus != '', function ($query) use ($paymentStatus) {
                    $query->where('payment_status', $paymentStatus);
                })
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(OrderResource::collection($orders), __('GET_ORDER_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/order/detail",
     *     tags={"Order"},
     *     summary="Get detail order by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID or code order",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => [
                'required',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->orWhere('id', request('id'))
                        ->orWhere('code', request('id'));
                }),
            ],
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $id = $request->id;

            $data = Order::where('id', $id)->orWhere('code', $id)->first();

            return $this->sendResponse(new OrderResource($data), __("GET_DETAIL_ORDER"));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *          @OA\Property(property="payment_type", type="string", example="ship", description="Hình thúc nhận hàng(ship, pickup)"),
     *          @OA\Property(property="process_status", type="string"),
     *          @OA\Property(property="payment_id", type="integer", example="1"),
     *          @OA\Property(property="voucher_id", type="integer", description="Id voucher"),
     *          @OA\Property(property="voucher_value", type="integer", description="Giá trị giảm voucher"),
     *          @OA\Property(property="price_tip", type="double", example="0", description="Tiền tip"),
     *          @OA\Property(property="fee", type="double", example="0", description="Phí vận chuyển"),
     *          @OA\Property(property="note", type="string", description="Ghi chú"),
     *          @OA\Property(property="phone", type="string", example="123456"),
     *          @OA\Property(property="address", type="string", example="abcd"),
     *          @OA\Property(property="lat", type="double", example="123.102"),
     *          @OA\Property(property="lng", type="double", example="12.054"),
     *          @OA\Property(property="street", type="string", example="abcd"),
     *          @OA\Property(property="zip", type="string", example="abcd"),
     *          @OA\Property(property="city", type="string", example="abcd"),
     *          @OA\Property(property="state", type="string", example="abcd"),
     *          @OA\Property(property="country", type="string", example="abcd"),
     *          @OA\Property(property="country_code", type="string", example="abcd")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create order Successful"),
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
                'payment_id' => 'required|exists:payment_wallet_provider,id',
                'voucher_id' => 'nullable|exists:discounts,id',
                'payment_type' => 'required|in:ship,pickup'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            if ($request->payment_id !== 5) {
                return $this->createStripePayment($request);
            }
            return $this->createCashPayment($request);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/order/update",
     *     tags={"Order"},
     *     summary="Update order",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Cart object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID order"),
     *          @OA\Property(property="payment_type", type="string", example="ship", description="Hình thúc nhận hàng(ship, pickup)"),
     *          @OA\Property(property="process_status", type="string"),
     *          @OA\Property(property="price_tip", type="double", example="0", description="Tiền tip"),
     *          @OA\Property(property="note", type="string", description="Ghi chú"),
     *          @OA\Property(property="phone", type="string", example="123456"),
     *          @OA\Property(property="address", type="string", example="abcd"),
     *          @OA\Property(property="lat", type="double", example="123.102"),
     *          @OA\Property(property="lng", type="double", example="12.054"),
     *          @OA\Property(property="street", type="string", example="abcd"),
     *          @OA\Property(property="zip", type="string", example="abcd"),
     *          @OA\Property(property="city", type="string", example="abcd"),
     *          @OA\Property(property="state", type="string", example="abcd"),
     *          @OA\Property(property="country", type="string", example="abcd"),
     *          @OA\Property(property="country_code", type="string", example="abcd"),
     *          @OA\Property(property="driver_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $requestData,
            [
                'id' => 'required|exists:orders,id',
                'payment_type' => 'nullable|in:ship,pickup'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $requestData = $request->all();
            $id = $request->id;
            $data = Order::find($id);
            $data->update($requestData);
            $data->refresh();
            \DB::commit();
            return $this->sendResponse(new OrderResource($data), __('ORDER_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    private function createOrder($cart, $paymentMethod, $request)
    {
        //Request data
        $paymentType = $request->payment_type ?? 'delivery';
        $addressDelivery = $request->address_delivery;

        // Fetch cart items
        $cartItems = $cart->cartItems()->get();
        if ($cartItems->isEmpty()) {
            throw new \Exception(__('CART_EMPTY'));
        }

        // Total price
        $totalPrice = $cartItems->sum('price');

        // Create or update order
        $order = Order::create([
            'user_id' => $cart->user_id,
            'store_id' => $cart->store_id,
            'total_price' => $totalPrice,
            'currency' => 'eur',
            'payment_type' => $paymentType,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'process_status' => 'pending',
            'address_delivery_id' => $addressDelivery,
            'payment_id' => $request->payment_id,
            'price_tip' => $request->price_tip ?? 0,
            'phone' => $request->phone,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'street' => $request->street,
            'zip' => $request->zip,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'country_code' => $request->country_code
        ]);

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

        // Save order items
        $order->orderItems()->saveMany($orderItems);


        return $order;
    }

    private function createCashPayment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cart = $this->getCart($request);
            $order = $this->createOrder($cart, 'pay_cash', $request);

            //Send notification
            $title = 'Order Received';
            $description = "Your order {$order->code} has been received by our store and is being processed. You will receive an update with tracking information once available.";

            Notification::insertNotificationByUser($title, $description, '', 'order', optional($order->store)->creator_id, $order->id);

            \DB::commit();
            return $this->sendResponse(new OrderResource($order), __('ORDER_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    private function createStripePayment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cart = $this->getCart($request);

            $order = $this->createOrder($cart, 'pay_stripe', $request);
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
            $paymentIntent = $this->stripeService->createPaymentIntent($order->total_price, $order->currency ?? 'eur', $transaction->code, $customerS, $order->code);

            if (isset($paymentIntent['error'])) {
                return $this->sendError($paymentIntent['error']);
            }

            \DB::commit();
            return $this->sendResponse([
                'clientSecret' => $paymentIntent->client_secret,
                'order' => new OrderResource($order),
            ], __('ORDER_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    private function getCart(Request $request)
    {
        $storeId = $request->store_id;
        $userId = auth('api')->id() ?? 0;

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
     *     path="/api/v1/order/complete",
     *     tags={"Order"},
     *     summary="Complete order",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Complete order",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID order"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Complete successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function complete(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:orders,id'
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $id = $request->id;

            $order = Order::find($id);

            $order->update([
                'payment_status' => 'completed',
                'process_status' => 'completed'
            ]);

            //Cộng tiền cho driver
            if ($order->driver_id != null) {
                $transaction = WalletTransaction::create([
                    'price' => $order->price,
                    'base_price' => $order->price,
                    'tax' => 0,
                    'fee' => 0,
                    'currency' => 'eur',
                    'user_id' => $order->driver_id,
                    'transaction_date' => now(),
                    'type' => 'purchase',
                    'status' => 'completed',
                    'description' => 'Payment from the order ' . $order->code,
                    'order_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            //Cộng tiền cho shop
            if ($order->store_id != null) {
                $partnerId = optional($order->store)->creator_id;
            }

            \DB::commit();
            return $this->sendResponse(null, __('ORDER_COMPLETE'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

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
    public function cancel(Request $request)
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
                'payment_status' => 'canceled',
                'cancel_note' => $request->cancel_note ?? '',
            ]);

            return $this->sendResponse(null, __('ORDER_CANCEL'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


}
