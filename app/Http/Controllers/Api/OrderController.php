<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\OrderResource;
use App\Models\CartItem;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\StoreWallet;
use App\Models\Wallet;
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
     *         name="delivery_type",
     *         in="query",
     *         description="Delivery type",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="process_status",
     *         in="query",
     *         description="Process status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="store_status",
     *         in="query",
     *         description="Store status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from_date",
     *         in="query",
     *         description="From date(Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="to_date",
     *         in="query",
     *         description="From date(Y-m-d)",
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
        $deliveryType = $request->delivery_type ?? '';
        $processStatus = $request->process_status ?? '';
        $storeStatus = $request->store_staus ?? '';
        $fromDate = $request->from_date ?? '';
        $toDate = $request->to_date ?? '';

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
                ->when($deliveryType != '', function ($query) use ($deliveryType) {
                    $query->where('delivery_type', $deliveryType);
                })
                ->when($processStatus != '', function ($query) use ($processStatus) {
                    $query->where('process_status', $processStatus);
                })
                ->when($storeStatus != '', function ($query) use ($storeStatus) {
                    $query->where('store_staus', $storeStatus);
                })
                ->when($fromDate != '' && $toDate, function ($query) use ($fromDate, $toDate) {
                    $query->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate);
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
     *         name="delivery_type",
     *         in="query",
     *         description="Delivery type",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="process_status",
     *         in="query",
     *         description="Process status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="store_status",
     *         in="query",
     *         description="Store status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from_date",
     *         in="query",
     *         description="From date(Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="to_date",
     *         in="query",
     *         description="From date(Y-m-d)",
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
        $deliveryType = $request->delivery_type ?? '';
        $processStatus = $request->process_status ?? '';
        $storeStatus = $request->store_status ?? '';
        $fromDate = $request->from_date ?? '';
        $toDate = $request->to_date ?? '';

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
                ->when($deliveryType != '', function ($query) use ($deliveryType) {
                    $query->where('delivery_type', $deliveryType);
                })
                ->when($processStatus != '', function ($query) use ($processStatus) {
                    $query->where('process_status', $processStatus);
                })
                ->when($storeStatus != '', function ($query) use ($storeStatus) {
                    $query->where('store_status', $storeStatus);
                })
                ->when($fromDate != '' && $toDate, function ($query) use ($fromDate, $toDate) {
                    $query->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate);
                })
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(OrderResource::collection($orders), __('GET_ORDER_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Get(
     *     path="/api/v1/order/preview_calculate_order",
     *     tags={"Order"},
     *     summary="Preview caculate order",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store_id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="ship_fee",
     *         in="query",
     *         description="ship_fee",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tip",
     *         in="query",
     *         description="tip",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="discount",
     *         in="query",
     *         description="discount",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Preview caculate order"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function previewCalculate(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'store_id' => 'required|exists:stores,id',
                'ship_fee' => 'nullable|numeric',
                'tip' => 'nullable|numeric',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $store_id = $request->store_id ?? '';
            $tip = $request->tip ?? 0;
            $shipFee = $request->ship_fee ?? 0;
            $userId = auth('api')->id();

            // Get the carts with the cart items, apply store filtering, and handle pagination
            $carts = Cart::has('cartItems')->with('cartItems')
                ->when($store_id != '', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->where('user_id', $userId)
                ->get();

            // Initialize the cart items for total calculation
            $cartItems = $carts->flatMap(function ($cart) {
                return $cart->cartItems;
            });

            $totalPrice = $cartItems->sum('price');
            $discount = $request->discount ?? 0;

            $application_fee = 0;
            $total = $totalPrice + $tip + $shipFee + $application_fee - $discount;

            $data = [
                'application_fee' => (float)$application_fee,
                'ship_fee' => (float)$shipFee,
                'tip' => (float)$tip,
                'discount' => (float)$discount,
                'subtotal' => (float)$totalPrice,
                'total ' => (float)$total,
            ];
            return $this->sendResponse($data, __('GET_ORDER_PREVIEW'));
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
     *          @OA\Property(property="delivery_type", type="string", example="ship", description="Hình thúc nhận hàng(ship, pickup)"),
     *          @OA\Property(property="process_status", type="string"),
     *          @OA\Property(property="payment_id", type="integer", example="1"),
     *          @OA\Property(property="voucher_id", type="integer", description="Id voucher"),
     *          @OA\Property(property="voucher_value", type="integer", description="Giá trị giảm voucher"),
     *          @OA\Property(property="tip", type="double", example="0", description="Tiền tip"),
     *          @OA\Property(property="ship_fee", type="double", example="0", description="Phí vận chuyển"),
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
     *          @OA\Property(property="ship_distance", type="integer", example="0"),
     *          @OA\Property(property="ship_estimate_time", type="string"),
     *          @OA\Property(property="ship_polyline", type="string"),
     *          @OA\Property(property="ship_here_raw", type="string"),
     *          @OA\Property(property="store_status", type="string"),
     *          @OA\Property(property="previous_order_id", type="integer")
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
                'delivery_type' => 'nullable|in:ship,pickup'
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
     *         description="Update order",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID order"),
     *          @OA\Property(property="delivery_type", type="string", example="ship", description="Hình thúc nhận hàng(ship, pickup)"),
     *          @OA\Property(property="process_status", type="string"),
     *          @OA\Property(property="tip", type="double", example="0", description="Tiền tip"),
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
     *          @OA\Property(property="ship_distance", type="integer", example="1"),
     *          @OA\Property(property="ship_estimate_time", type="string"),
     *          @OA\Property(property="ship_polyline", type="string"),
     *          @OA\Property(property="ship_here_raw", type="string"),
     *          @OA\Property(property="store_status", type="string")
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
                'delivery_type' => 'nullable|in:ship,pickup'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $requestData = $request->all();
            $id = $request->id;
            $order = Order::find($id);
            $order->update($requestData);
            $order->refresh();
            \DB::commit();
            return $this->sendResponse(new OrderResource($order), __('ORDER_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/order/assigned_driver",
     *     tags={"Order"},
     *     summary="Assigned driver order",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Assigned driver order",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID order"),
     *          @OA\Property(property="driver_id", type="integer", example="1", description="ID driver"),
     *          @OA\Property(property="delivery_type", type="string", example="ship", description="Hình thúc nhận hàng(ship, pickup)"),
     *          @OA\Property(property="process_status", type="string"),
     *          @OA\Property(property="tip", type="double", example="0", description="Tiền tip"),
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
     *          @OA\Property(property="ship_distance", type="integer", example="1"),
     *          @OA\Property(property="ship_estimate_time", type="string"),
     *          @OA\Property(property="ship_polyline", type="string"),
     *          @OA\Property(property="ship_here_raw", type="string"),
     *          @OA\Property(property="store_status", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Assigned driver Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function assigned(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $requestData,
            [
                'id' => 'required|exists:orders,id',
                'previous_order_id' => 'nullable|exists:orders,id',
                'driver_id' => 'required|exists:customers,id',
                'delivery_type' => 'nullable|in:ship,pickup'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $requestData = $request->all();
            $id = $request->id;
            $order = Order::find($id);
            $order->update($requestData);
            $order->refresh();

            //Gửi thông báo
            $this->sendNotificationOrderCompleted($order);

            \DB::commit();
            return $this->sendResponse(new OrderResource($order), __('ORDER_ASSIGNED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    private function createOrder($cart, $paymentMethod, $request)
    {
        //Request data
        $deliveryType = $request->delivery_type ?? 'ship';
        $addressDelivery = $request->address_delivery;

        // Fetch cart items
        $cartItems = $cart->cartItems()->get();
        if ($cartItems->isEmpty()) {
            throw new \Exception(__('CART_EMPTY'));
        }

        // Total price
        $totalPrice = $cartItems->sum('price');
        $currency = $request->currency ?? 'HUF';

        // Create or update order
        $order = Order::create([
            'user_id' => $cart->user_id,
            'store_id' => $cart->store_id,
            'total_price' => $totalPrice,
            'currency' => $currency,
            'delivery_type' => $deliveryType,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'process_status' => 'pending',
            'address_delivery_id' => $addressDelivery,
            'payment_id' => $request->payment_id,
            'tip' => $request->tip ?? 0,
            'ship_fee' => $request->ship_fee ?? 0,
            'voucher_id' => $request->voucher_id ?? null,
            'voucher_value' => $request->voucher_value ?? 0,
            'previous_order_id' => $request->previous_order_id ?? null,
            'phone' => $request->phone,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'street' => $request->street,
            'zip' => $request->zip,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'country_code' => $request->country_code,
            'ship_distance' => $request->ship_distance,
            'ship_estimate_time' => $request->ship_estimate_time,
            'ship_polyline' => $request->ship_polyline,
            'ship_here_raw' => $request->ship_here_raw,
            'store_status' => $request->store_status
        ]);

        // Attach the cart items as order items
        if (!empty($request->previous_order_id)) {
            $orderClone = Order::find($request->previous_order_id);
            // Clone các OrderItem từ đơn hàng cũ
            $orderItems = $orderClone->orderItems->map(function ($item) {
                return new OrderItem([
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'product' => $item->product, // Nếu bạn có quan hệ product, hoặc cần serialize lại
                    'variations' => $item->variations,
                    'toppings' => $item->toppings,
                ]);
            });
        } else {
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
        }

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
            if ($order->delivery_type == 'pickup') {
                //Thông báo store
                $title = "New Order Received";
                $description = "You have a new order. Please review and start processing it as soon as possible.";
                Notification::insertNotificationByUser($title, $description, '', 'order', optional($order->store)->creator_id, $order->id, $order->store_id);
            }
            $title = 'Order Received';
            $description = "Your order {$order->code} has been received by our store and is being processed. You will receive an update with tracking information once available.";
            Notification::insertNotificationByUser($title, $description, '', 'order', $order->user_id, $order->id, null);

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

            //Tính tổng tiền
            $subTotal = $order->total_price;
            $tip = $order->tip ?? 0;
            $shippingFee = $order->ship_fee ?? 0;
            $discount = $order->voucher_value ?? 0;

            // Tính application_fee, 3% của subtotal
            $orderPrice = $subTotal + $tip + $shippingFee + -$discount;

            //Save transaction
            $transaction = WalletTransaction::create([
                'price' => $orderPrice,
                'base_price' => $orderPrice,
                'fee' => 0,
                'currency' => 'HUF',
                'user_id' => auth('api')->id(),
                'payment_method' => 'card',
                'type' => 'purchase',
                'status' => 'pending',
                'order_id' => $order->id,
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            // Call Stripe payment method
            $customerS = $this->stripeService->createCustomer($order->customer);
            $paymentIntent = $this->stripeService->createPaymentIntent($orderPrice, $order->currency ?? 'eur', $transaction->code, $customerS, $order->code);

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


    private function deleteCart($userId, $storeId)
    {
        CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
            $query->where('user_id', $userId)->where('store_id', $storeId);
        })->delete();
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
     *             @OA\Property(property="id", type="integer", example="1", description="ID order")
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

            if ($order->process_status == 'completed') return $this->sendError(__('ORDER_IS_COMPLETED'));

            $order->update([
                'payment_status' => 'completed',
                'process_status' => 'completed',
                'store_status' => 'completed'
            ]);

            $subTotal = $order->total_price;
            $tip = $order->tip ?? 0;
            $shippingFee = $order->ship_fee ?? 0;
            $discount = $order->voucher_value ?? 0;

            $orderCode = $order->code;
            $deliveryType = $order->delivery_type;
            $isStripe = $order->payment_id != 5; // payment_id = 5 => Cash

            // Tính phần chia tiền
            $storeRate = ((float) \DB::table('settings')->where('key', 'store_rate')->value('value') ?? 90) / 100;
            $appRate = ((float) \DB::table('settings')->where('key', 'app_rate')->value('value') ?? 10) / 100;

            $storeEarning = $subTotal * $storeRate;
            $systemEarning = $subTotal * $appRate;
            $driverShippingEarning = 0;


            if ($deliveryType == 'ship') {
                $driverShippingEarning = $shippingFee * 0.70;
                $systemEarning += $shippingFee * 0.30;
            }

            if ($isStripe) {
                // 💳 CASE 1 & 2: STRIPE
                $systemWallet = Wallet::getOrCreateWallet(0);

                if ($deliveryType == 'ship') {
                    // 📦 CASE 1: STRIPE + SHIP

                    // Trừ ví system → chuyển cho store
                    $systemWallet->updateBalance(-$storeEarning);
                    $this->createTransaction(
                        $systemWallet->id, $order->id, $orderCode, -$storeEarning, 0, $order->store_id,
                        "Transfer store earning from order #$orderCode to store"
                    );

                    // Trừ ví system → chuyển cho driver (shipping fee)
                    $systemWallet->updateBalance(-$driverShippingEarning);
                    $this->createTransaction(
                        $systemWallet->id, $order->id, $orderCode, -$driverShippingEarning, 0, null,
                        "Transfer shipping fee from order $orderCode to driver"
                    );

                    // Trừ ví system → chuyển tip cho driver
                    if ($tip > 0) {
                        $systemWallet->updateBalance(-$tip);
                        $this->createTransaction(
                            $systemWallet->id, $order->id, $orderCode, -$tip, 0, null,
                            "Transfer tip from order $orderCode to driver"
                        );
                    }

                    // Cộng ví driver
                    if ($order->driver_id) {
                        $driverWallet = Wallet::getOrCreateWallet($order->driver_id);
                        $driverWallet->updateBalance($driverShippingEarning + $tip);
                        $this->createTransaction(
                            $driverWallet->id, $order->id, $orderCode, $driverShippingEarning + $tip, $order->driver_id, null,
                            "Driver earning (shipping + tip) from order $orderCode"
                        );
                    }


                } else {
                    // 🛍️ CASE 2: STRIPE + PICKUP

                    $systemWallet->updateBalance(-$storeEarning);
                    $this->createTransaction(
                        $systemWallet->id, $order->id, $orderCode, -$storeEarning, 0, $order->store_id,
                        "Transfer store earning from order $orderCode to store"
                    );
                }

                // Cộng ví store (cho cả SHIP & PICKUP)
                $storeWallet = StoreWallet::getStoreWallet($order->store_id);
                $storeWallet->updateBalance($storeEarning);
                $this->createTransaction(
                    $storeWallet->id, $order->id, $orderCode, $storeEarning, null, $order->store_id,
                    "Store earning from order $orderCode"
                );

            } else {
                // 💵 CASE 3 & 4: CASH

                if ($deliveryType == 'ship') {
                    // 📦 CASE 3: CASH + SHIP
                    if ($order->driver_id) {
                        $driverWallet = Wallet::getOrCreateWallet($order->driver_id);

                        // Trừ ví driver → chuyển cho store
                        $driverWallet->updateBalance(-$storeEarning);
                        $this->createTransaction(
                            $driverWallet->id, $order->id, $orderCode, -$storeEarning, $order->driver_id, null,
                            "Driver transfers store earning from order $orderCode"
                        );

                        // Trừ ví driver → chuyển cho system
                        $driverWallet->updateBalance(-$systemEarning);
                        $this->createTransaction(
                            $driverWallet->id, $order->id, $orderCode, -$systemEarning, $order->driver_id, null,
                            "Driver transfers system fee from order $orderCode"
                        );
                    }

                    // Cộng ví store
                    $storeWallet = StoreWallet::getStoreWallet($order->store_id);
                    $storeWallet->updateBalance($storeEarning);
                    $this->createTransaction(
                        $storeWallet->id, $order->id, $orderCode, $storeEarning, null, $order->store_id,
                        "Store earning from order $orderCode"
                    );

                    // Cộng ví system
                    $systemWallet = Wallet::getOrCreateWallet(0);
                    $systemWallet->updateBalance($systemEarning);
                    $this->createTransaction(
                        $systemWallet->id, $order->id, $orderCode, $systemEarning, 0, null,
                        "System commission from order $orderCode"
                    );

                } else {
                    // 🛍️ CASE 4: CASH + PICKUP

                    $storeWallet = StoreWallet::getStoreWallet($order->store_id);

                    // Trừ ví store → chuyển cho system
                    $storeWallet->updateBalance(-$systemEarning);
                    $this->createTransaction(
                        $storeWallet->id, $order->id, $orderCode, -$systemEarning, null, $order->store_id,
                        "Store transfers system fee from order $orderCode"
                    );

                    // Cộng ví system
                    $systemWallet = Wallet::getOrCreateWallet(0);
                    $systemWallet->updateBalance($systemEarning);
                    $this->createTransaction(
                        $systemWallet->id, $order->id, $orderCode, $systemEarning, 0, null,
                        "System commission from order $orderCode"
                    );
                }
            }

            $this->sendNotificationOrderCompleted($order);

            \DB::commit();
            return $this->sendResponse(null, __('ORDER_COMPLETED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    private function sendNotificationOrderCompleted($order)
    {
        //Gửi thông báo tới user
        if ($order->user_id != null) {
            $title = "Order Placed Successfully";
            $description = "Your order has been placed successfully. We'll notify you when it's being prepared and on its way!";
            Notification::insertNotificationByUser($title, $description, '', 'order', $order->user_id, $order->id, null);
        }

        //Gửi thông báo tới driver
        if ($order->driver_id != null) {
            $title = "New Order Received";
            $description = "You have a new order. Please review and start processing it as soon as possible.";
            Notification::insertNotificationByUser($title, $description, '', 'order', $order->driver_id, $order->id, null);
        }

        //Gửi thông báo tới store
        if ($order->store_id != null && $order->delivery_type == 'pickup') {
            $title = "New Order Received";
            $description = "You have a new order. Please review and start processing it as soon as possible.";
            Notification::insertNotificationByUser($title, $description, '', 'order', optional($order->store)->creator_id, $order->id, $order->store_id);
        }
    }


    private function createTransaction($walletId, $orderId, $orderCode, $price, $userId, $storeId = null, $description = null)
    {
        \DB::table('wallet_transactions')->insert([
            'code' => WalletTransaction::getCodeUnique(),
            'store_id' => $storeId,
            'wallet_id' => $walletId,
            'price' => $price,
            'base_price' => $price,
            'tax' => 0,
            'fee' => 0,
            'currency' => 'HUF',
            'user_id' => $userId,
            'type' => 'purchase',
            'status' => 'completed',
            'payment_method' => 'card',
            'description' => $description ?? ('Payment from order ' . $orderCode),
            'order_id' => $orderId,
            'transaction_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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
        \DB::beginTransaction();
        try {
            $id = $request->id;
            // Tìm cart item theo ID
            $order = Order::find($id);

            $order->update([
                'process_status' => 'cancelled',
                'store_status' => 'cancelled',
                'cancel_note' => $request->cancel_note ?? '',
            ]);

            //Gửi thông báo tới user
            if ($order->user_id != null) {
                $title = "Order Cancelled";
                $description = "Your order has been cancelled. If this was a mistake or you need help, please contact support.";
                Notification::insertNotificationByUser($title, $description, '', 'order', $order->user_id, $order->id, null);
            }

            //Gửi thông báo tới driver
            if ($order->driver_id != null && $order->delivery_type == 'ship') {
                $title = "Order Cancelled";
                $description = "The order has been cancelled. You don’t need to proceed with this order.";
                Notification::insertNotificationByUser($title, $description, '', 'order', $order->driver_id, $order->id, null);
            }

            //Gửi thông báo tới store
            if ($order->store_id != null) {
                $title = "Order Cancelled";
                $description = "The order has been cancelled. You don’t need to proceed with this order.";
                Notification::insertNotificationByUser($title, $description, '', 'order', optional($order->store)->creator_id, $order->id, $order->store_id);
            }

            $order->refresh();


            \DB::commit();
            return $this->sendResponse(new OrderResource($order), __('ORDER_CANCELED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


}
