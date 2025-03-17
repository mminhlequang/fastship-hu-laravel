<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartResource;
use App\Http\Resources\CartVariationResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ToppingCartResource;
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
        $validator = Validator::make(
            $request->all(),
            [
                'store_id' => 'required|exists:stores,id'
            ]
        );
        if ($validator->fails()) {
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        }

        // Default limit and offset values
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $store_id = $request->store_id;
            $userId = \Auth::id() ?? 0;
            $cart = Cart::where('store_id', $store_id)
                ->where('user_id', $userId)
                ->first();

            if (!$cart) {
                return $this->sendResponse([], 'Get all cart successfully.');
            }

            // Apply pagination (limit and offset) to cartItems
            $cartItemsQuery = $cart->cartItems()->skip($offset)->take($limit)->get();

            // Fetch the items
            $cartItems = $cart->cartItems()->get();

            // Total quantity and total price
            $totalQuantity = $cartItems->sum('quantity');
            $totalPrice = $cartItems->sum('price');

            return $this->sendResponse([
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
                'items' => CartResource::collection(collect($cartItemsQuery)),
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
     *             @OA\Property(property="variations", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="variation_value", type="integer", example=1)
     *             ), description="List id của variations [['variation_value' => 1], ['variation_value' => 4]]"),
     *
     *             @OA\Property(property="topping_ids", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="quantity", type="integer", example=2)
     *             ), description="List id của toppings [['id' => 1, 'quantity'=> 2]]")
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

        $validator = Validator::make(
            $requestData,
            [
                'store_id' => 'required|exists:stores,id',
                'product_id' => 'required|exists:products,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate([
                'user_id' => $customer->id,
                'store_id' => $request->store_id,
            ]);

//            $request->merge([
//                'topping_ids' => [
//                    ['id' => 1, 'quantity' => 2],
//                    ['id' => 2, 'quantity' => 5],
//                ],
//                'variations' => [
//                    ['variation_value' => 2],
//                    ['variation_value' => 2],
//
//                ]
//            ]);

            // Tính giá cho sản phẩm đã chọn biến thể và topping
            $productId = $request->product_id;
            $product = Product::find($productId);

            $quantity = $request->quantity ?? 1;
            $price = $product->price * $quantity;
            // Thêm giá trị biến thể vào giá sản phẩm
            $variations = null;
            if (!empty($request->variations)) {
                // Get the variation_value IDs from the request variations
                $variationIds = collect($request->variations)->pluck('variation_value')->toArray();
                // Retrieve all VariationValue records where the id is in the provided list of IDs
                $variations = VariationValue::whereIn('id', $variationIds)->get();

                // Loop through each variation in the request and add the price
                foreach ($request->variations as $variation) {
                    $variationValue = $variations->firstWhere('id', $variation['variation_value']);
                    if ($variationValue) {
                        $variationValue->name = optional($variationValue->variation)->name;
                        $price += $variationValue->price;
                    }
                }
            }

            // Thêm topping vào giá sản phẩm
            $toppingPrice = 0;
            $toppings = null;
            // Check if topping_ids are provided
            if (!empty($request->topping_ids)) {
                // Fetch toppings based on the provided IDs
                $toppings = Topping::whereIn('id', array_column($request->topping_ids, 'id'))->get();
                foreach ($toppings as $topping) {
                    // Find the corresponding topping from the request data
                    $requestedTopping = collect($request->topping_ids)->firstWhere('id', $topping->id);
                    // Calculate the price based on the quantity in the request
                    $toppingPrice += $topping->price * $requestedTopping['quantity'];
                    $topping->quantity = $requestedTopping['quantity'];
                }
            }

            $price += $toppingPrice;
            // Assuming $cart is an instance of Cart, and you already have $productId, $price, $variations, and $toppings.
            $cart->cartItems()->updateOrCreate(
                [
                    'product_id' => $productId, // This will check if the cart item with this product exists
                    'cart_id' => $cart->id, // Ensures we're updating/creating within the correct cart
                ],
                [
                    'price' => $price, // Update price or set the price when creating
                    'product' => new ProductResource($product),
                    'variations' => CartVariationResource::collection(collect($variations)), // Update variations or set them when creating
                    'toppings' => ToppingCartResource::collection(collect($toppings)), // Update toppings or set them when creating
                ]
            );

            \DB::commit();

            return $this->sendResponse(null, __('errors.CART_CREATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
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
     *          @OA\Property(property="id", type="integer", example="1", description="ID cart item."),
     *          @OA\Property(property="quantity", type="integer", example="1", description="quantity product."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update cart Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {

        // Validate input
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            // Find cart item by ID
            $cartItemId = $request->id;
            $quantity = $request->quantity ?? 1;
            $cartItem = CartItem::find($cartItemId);

            // If cart item doesn't exist, return error
            if (!$cartItem) return $this->sendError(__('CART_NOT_EXISTS'));

            // Update total price of cart item
            $cartItem->quantity = $cartItem->quantity + $quantity;
            $cartItem->price = $cartItem->price * $cartItem->quantity;

            // Save the updated cart item
            $cartItem->save();
            $cartItem->refresh();

            // If quantity is 0 or less, delete the cart item
            if ($cartItem->quantity <= 0) $cartItem->delete();


            //Get carts
            $cart = Cart::find($cartItem->cart_id);
            // Fetch the items
            $cartItems = $cart->cartItems()->get();

            // Total quantity and total price
            $totalQuantity = $cartItems->sum('quantity');
            $totalPrice = $cartItems->sum('price');

            \DB::commit();

            return $this->sendResponse([
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
                'items' => CartResource::collection(collect($cartItems)),
            ], __('CART_UPDATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
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
