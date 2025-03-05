<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
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
        if (!$customer) {
            return $this->sendError(__('errors.INVALID_SIGNATURE'));
        }

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
            $user_id = $customer->id ?? 0;
            $cart = Cart::where('store_id', $store_id)
                ->where('user_id', $user_id)
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
                'items' => CartResource::collection($cartItemsQuery),
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
        \DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate([
                'user_id' => $customer->id,
                'store_id' => $request->store_id,
            ]);

            $request->merge([
                'topping_ids' => [1],
                'variations' => [
                    ['variation_value' => 2],
                    ['variation_value' => 5]
                ]
            ]);


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
                        $price += $variationValue->price;
                    }
                }
            }

            // Thêm topping vào giá sản phẩm
            $toppingPrice = 0;
            $toppings = null;
            if (!empty($request->topping_ids)) {
                $toppings = Topping::whereIn('id', $request->topping_ids)->get();
                foreach ($toppings as $topping) {
                    $toppingPrice += $topping->price;
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
                    'variations' => $variations, // Update variations or set them when creating
                    'toppings' => ToppingResource::collection($toppings), // Update toppings or set them when creating
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

        // Validate input
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            // Find cart item by ID
            $cartItemId = $request->id;
            $cartItem = CartItem::find($cartItemId);

            // If cart item doesn't exist, return error
            if (!$cartItem) return $this->sendError(__('CART_NOT_EXISTS'));

            // Update quantity
            $cartItem->quantity = $request->quantity;

            // If quantity is 0 or less, delete the cart item
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
            }

            // Calculate product price based on variations and toppings
            $product = $cartItem->product;
            $price = $product->price;

            // Check if variations have been passed in the request and if they differ from current
            if (!empty($request->variations)) {
                $updatedVariations = $request->variations;

                // Compare and only update if there is a change in variations
                if ($updatedVariations !== $cartItem->variations) {
                    // Update the variations' price
                    foreach ($updatedVariations as $variation) {
                        $variationValue = VariationValue::find($variation['id']);
                        $price += $variationValue->price;
                    }

                    // Update variations in the cart item
                    $cartItem->variations = $updatedVariations;
                }
            }

            // Check if topping_ids have been passed in the request and if they differ from current
            $toppingPrice = 0;
            if (!empty($request->topping_ids)) {
                $updatedToppingIds = $request->topping_ids;
                // Compare and only update if there is a change in toppings
                if ($updatedToppingIds !== $cartItem->toppings) {
                    // Recalculate topping prices if toppings have changed
                    $toppings = Topping::whereIn('id', $updatedToppingIds)->get();
                    foreach ($toppings as $topping) {
                        $toppingPrice += $topping->price;
                    }

                    // Update toppings in the cart item
                    $cartItem->toppings = $updatedToppingIds;
                }
            }

            // Add topping price to the product price
            $price += $toppingPrice;

            // Update total price of cart item
            $cartItem->price = $price * $cartItem->quantity;

            // Save the updated cart item
            $cartItem->save();

            // Return response with success message
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
