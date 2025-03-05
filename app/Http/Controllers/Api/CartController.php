<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartResource;
use App\Http\Resources\ToppingResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Topping;
use App\Models\VariationValue;
use Illuminate\Http\Request;
use Validator;

class CartController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/cart",
     *     tags={"Cart"},
     *     summary="Get all cart",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="ID store",
     *         required=true,
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
     *     @OA\Response(response="200", description="Get all cart"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getList(Request $request)
    {
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));

        $validator = Validator::make(
            $request->all(),
            [
                'store_id' => 'required|exists:stores,id'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $store_id = $request->store_id;
            $user_id = $customer->id ?? 0;
            $cart = Cart::where('store_id', $store_id)
                ->where('user_id', $user_id)
                ->first();

            if (!$cart) return $this->sendResponse([], 'Get all cart successfully.');

            $cartItems = $cart->cartItems->map(function ($cartItem) {
                $product = $cartItem->product;
                $variations = [];
                foreach ($cartItem->variations as $variation) {
                    $variationValue = VariationValue::find($variation['variation_value']);
                    $variations[] = [
                        'name' => $variationValue->variation->name,
                        'value' => $variationValue->value,
                        'price' => $variationValue->price,
                    ];
                }

                $toppings = [];
                foreach ($cartItem->toppings as $toppingId) {
                    $topping = Topping::find($toppingId);
                    $toppings[] = [
                        'name' => $topping->name,
                        'price' => $topping->price,
                    ];
                }

                return [
                    'product' => $product,
                    'variations' => $variations,
                    'toppings' => $toppings,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity
                ];
            });

            // Tổng số lượng sản phẩm và tổng giá trị
            $totalQuantity = $cartItems->count();
            $totalPrice = $cartItems->sum('price');

            return $this->sendResponse([
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
                'items' => $cartItems,
            ], 'Get all cart successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/cart/create",
     *     tags={"Cart"},
     *     summary="Create cart",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Cart object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của store."),
     *          @OA\Property(property="product_id", type="integer", example="1", description="ID của product."),
     *          @OA\Property(property="quantity", type="integer", example="1", description="ID của product."),
     *          @OA\Property(property="topping_ids", type="string", example="[1,2,3]", description="List id của toppings"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create cart Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function create(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));
        $validator = Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'product_id' => 'required|exists:products,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $cart = Cart::firstOrCreate([
                'user_id' => $customer->id,
                'store_id' => $request->store_id,
            ]);

            $request->topping_ids = [1];
            $request->variations = [
                ['variation_value' => 1],
                ['variation_value' => 4]
            ];

            // Tính giá cho sản phẩm đã chọn biến thể và topping
            $productId = $request->product_id;
            $product = Product::find($productId);
            $quantity = $request->quantity ?? 1;
            $price = $product->price * $quantity;
            dd($request->all());
            // Thêm giá trị biến thể vào giá sản phẩm
            if (!empty($request->variations)) {
                foreach ($request->variations as $variation) {
                    $variationValue = VariationValue::find($variation['variation_value']);
                    $price += $variationValue->price;
                }
            }


            // Thêm topping vào giá sản phẩm
            $toppingPrice = 0;
            if (!empty($request->topping_ids)) {
                $toppings = Topping::whereIn('id', $request->topping_ids)->get();
                foreach ($toppings as $topping) {
                    $toppingPrice += $topping->price;
                }
            }

            $price += $toppingPrice;

            // Thêm sản phẩm vào giỏ hàng
            $cartItem = $cart->cartItems()->create([
                'product_id' => $productId,
                'price' => $price,
                'variations' => $request->variations, // Lưu thông tin biến thể
                'toppings' => $request->topping_ids,  // Lưu thông tin topping
            ]);

            return $this->sendResponse($cartItem, __('errors.CART_CREATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/cart/update",
     *     tags={"Cart"},
     *     summary="Update cart",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Cart object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID của product."),
     *          @OA\Property(property="quantity", type="integer", example="1", description="ID của product."),
     *          @OA\Property(property="topping_ids", type="string", example="[1,2,3]", description="List id của toppings"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update cart Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            // Tìm cart item theo ID
            $cartItemId = $request->id;

            $cartItem = CartItem::find($cartItemId);

            // Nếu không tìm thấy cart item, trả về lỗi
            if (!$cartItem) return $this->sendError(__('CART_NOT_EXISTS'));

            // Cập nhật số lượng
            $cartItem->quantity = $request->quantity;

            // Kiểm tra nếu số lượng bằng 0, thì xóa cart item
            if ($cartItem->quantity <= 0) $cartItem->delete();

            // Tính toán lại giá trị sản phẩm (với số lượng mới)
            $product = $cartItem->product;
            $price = $product->price;

            // Thêm giá trị biến thể vào giá sản phẩm
            foreach ($request->variations as $variation) {
                $variationValue = VariationValue::find($variation['variation_value']);
                $price += $variationValue->price;
            }

            // Thêm topping vào giá sản phẩm
            $toppingPrice = 0;
            if (!empty($request->topping_ids)) {
                $toppings = Topping::whereIn('id', $request->topping_ids)->get();
                foreach ($toppings as $topping) {
                    $toppingPrice += $topping->price;
                }
            }

            $price += $toppingPrice;

            // Cập nhật lại giá của cart item
            $cartItem->price = $price * $request->quantity;

            // Cập nhật lại biến thể và topping
            $cartItem->variations = $request->variations;
            $cartItem->toppings = $request->topping_ids;

            // Lưu thay đổi vào cơ sở dữ liệu
            $cartItem->save();
            return $this->sendResponse(null, __('errors.CART_UPDATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/cart/delete",
     *     tags={"Cart"},
     *     summary="Delete cart",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID cart item"),
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
            'id' => 'required|exists:cart_items,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));
        try {
            $cartItemId = $request->id;
            // Tìm cart item theo ID
            $cartItem = CartItem::find($cartItemId);

            // Nếu không tìm thấy cart item, trả về lỗi
            if (!$cartItem) return $this->sendError(__('CART_NOT_EXISTS'));

            // Xóa cart item
            $cartItem->delete();

            return $this->sendResponse(null, __('errors.CART_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


}
