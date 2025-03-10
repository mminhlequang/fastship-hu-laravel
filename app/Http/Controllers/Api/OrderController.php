<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ApproveResource;
use App\Http\Resources\OrderResource;
use App\Models\Approve;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Validator;

class OrderController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/order",
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
    public function getList(Request $request)
    {

        // Default limit and offset values
        $keywords = $request->keywords ?? '';
        $approveId = $request->approve_id ?? '';
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $userId = \Auth::id() ?? 0;
            $orders = Order::with('orderItems')->where('user_id', $userId)
                ->when($keywords != '', function ($query) use ($keywords){
                    $query->whereHas('orderItems', function ($query) use ($keywords){
                        $query->where('product', 'like', "%$keywords%");
                    });
                })
                ->when($approveId != '', function ($query) use ($approveId){
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
     *     path="/api/v1/order/approve",
     *     tags={"Order"},
     *     summary="Get all approve order",
     *     @OA\Response(response="200", description="Get all approve "),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getListApprove(Request $request)
    {
        try {
            $data = Approve::orderBy('arrange')->get();
            return $this->sendResponse(ApproveResource::collection($data), __('GET_ORDER_APPROVE_SUCCESS'));
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
     *          @OA\Property(property="payment_type", type="string", example="delivery", description="Hình thúc nhận hàng"),
     *          @OA\Property(property="payment_method", type="string", example="pay_cash", description="Hình thức thanh toán"),
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
                'store_id' => 'required|exists:stores,id'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {

            $storeId = $request->store_id;
            $userId = \Auth::id() ?? 0;
            $cart = Cart::where('store_id', $storeId)
                ->where('user_id', $userId)
                ->first();

            if (!$cart) {
                return $this->sendError(__('CART_EMPTY'));
            }

            // Fetch the items
            $cartItems = $cart->cartItems()->get();

            if (count($cartItems) == 0) {
                return $this->sendError(__('CART_EMPTY'));
            }

            // Total quantity and total price
            $totalPrice = $cartItems->sum('price');

            // Create or update an order (depending on your use case)
            $order = Order::updateOrCreate(
                ['user_id' => $cart->user_id, 'store_id' => $cart->store_id, 'payment_status' => 'pending'], // Update conditions
                [
                    'total_price' => $totalPrice,
                    'payment_type' => 'delivery',
                    'payment_method' => 'pay_cash',
                    'payment_status' => 'pending',
                    'approve_id' => 1,
                ]
            );
            // Attach the related cart items as order items using Eloquent relationship
            $orderItems = $cartItems->map(function ($cartItem) use ($order) {
                return new OrderItem([
                    'product_id' => $cartItem->product_id,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'product' =>  $cartItem->product,
                    'variations' => $cartItem->variations,
                    'toppings' => $cartItem->toppings,
                ]);
            });

            // Save order items using Eloquent relationship
            $order->orderItems()->saveMany($orderItems);

            \DB::commit();

            return $this->sendResponse(null, __('errors.ORDER_CREATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
