<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Topping;
use App\Models\VariationValue;
use Illuminate\Http\Request;
use Validator;

class CartController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/cart/get_carts_by_user",
     *     tags={"Cart"},
     *     summary="Get all cart",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="ID store",
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
     *     @OA\Response(response="200", description="Get all cart"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getList(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'store_id' => 'nullable|exists:stores,id'
            ]
        );
        if ($validator->fails()) {
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        }

        // Default limit and offset values
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;

        try {
            $store_id = $request->store_id ?? '';
            $userId = auth('api')->id();

            // Get the carts with the cart items, apply store filtering, and handle pagination
            $carts = Cart::has('cartItems')->with('cartItems')
                ->when($store_id != '', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->where('user_id', $userId)
                ->skip($offset)
                ->take($limit)
                ->get();

            // Initialize the cart items for total calculation
//            $cartItems = $carts->flatMap(function ($cart) {
//                return $cart->cartItems;
//            });
//
//            // Total quantity and total price for all items in the carts
//            $totalQuantity = $cartItems->sum('quantity');
//            $totalPrice = $cartItems->sum('price');

            // Return the response
            return $this->sendResponse(CartResource::collection($carts), __('GET_CARTS'));

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *          @OA\Property(property="product_id", type="integer", example="2", description="ID của product."),
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
                'user_id' => auth('api')->id(),
                'store_id' => $request->store_id,
            ]);

            // Tính giá cho sản phẩm đã chọn biến thể và topping
            $productId = $request->product_id;
            $product = Product::find($productId);

            $quantity = $request->quantity ?? 1;
            $price = $product->price * $quantity;

            // Thêm giá trị biến thể vào giá sản phẩm
            $variations = null;
            if ($request->variations != null && !empty($request->variations)) {
                // Get the variation_value IDs from the request variations
                $variationIds = collect($request->variations)->pluck('variation_value')->toArray();
                // Retrieve all VariationValue records where the id is in the provided list of IDs
                $variations = VariationValue::whereIn('id', $variationIds)->get();

                // Loop through each variation in the request and add the price
                foreach ($request->variations as $variation) {
                    $variationValue = $variations->firstWhere('id', $variation['variation_value']);
                    if ($variationValue) {
                        $variationValue->variation;
                        $price += $variationValue->price;
                    }
                }
            } else {
                unset($requestData['variations']);
            }

            // Thêm topping vào giá sản phẩm
            $toppingPrice = 0;
            $toppings = null;
            // Check if topping_ids are provided
            if ($request->topping_ids != null && !empty($request->topping_ids)) {
                // Fetch toppings based on the provided IDs
                $toppings = Topping::whereIn('id', array_column($request->topping_ids, 'id'))->get();
                foreach ($toppings as $topping) {
                    // Find the corresponding topping from the request data
                    $requestedTopping = collect($request->topping_ids)->firstWhere('id', $topping->id);
                    // Calculate the price based on the quantity in the request
                    $toppingPrice += $topping->price * $requestedTopping['quantity'];
                    $topping->quantity = $requestedTopping['quantity'];
                }
            } else {
                unset($requestData['topping_ids']);
            }

            $price += $toppingPrice;
            // Assuming $cart is an instance of Cart, and you already have $productId, $price, $variations, and $toppings.
            $cart->cartItems()->updateOrCreate(
                [
                    'product_id' => $productId, // This will check if the cart item with this product exists
                    'cart_id' => $cart->id, // Ensures we're updating/creating within the correct cart
                ],
                [
                    'quantity' => $quantity,
                    'price' => $price, // Update price or set the price when creating
                    'product' => collect($product),
                    'variations' => collect($variations), // Update variations or set them when creating
                    'toppings' => collect($toppings), // Update toppings or set them when creating
                ]
            );

            \DB::commit();

            return $this->sendResponse(null, __('CART_CREATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *          @OA\Property(property="variations", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="variation_value", type="integer", example=1)
     *             ), description="List id của variations [['variation_value' => 1], ['variation_value' => 4]]"),
     *
     *          @OA\Property(property="topping_ids", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="quantity", type="integer", example=2)
     *             ), description="List id của toppings [['id' => 1, 'quantity'=> 2]]")
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
            $requestData = $request->all();
            $cartItemId = $request->id;
            $quantity = $request->quantity ?? 1; // Default to 1 if quantity is not provided
            $cartItem = CartItem::with(['cart', 'productR'])->find($cartItemId);

            // Delete the cart item if the quantity is less than or equal to 0
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
                \DB::commit();
                return $this->sendResponse(null, __('CART_DELETED'));
            }

            $product = $cartItem->productR;
            //Tính lại giá
            $price = $product->price * $quantity;

            // Thêm giá trị biến thể vào giá sản phẩm
            $variations = null;
            if ($request->variations != null && !empty($request->variations)) {
                // Get the variation_value IDs from the request variations
                $variationIds = collect($request->variations)->pluck('variation_value')->toArray();
                // Retrieve all VariationValue records where the id is in the provided list of IDs
                $variations = VariationValue::whereIn('id', $variationIds)->get();

                // Loop through each variation in the request and add the price
                foreach ($request->variations as $variation) {
                    $variationValue = $variations->firstWhere('id', $variation['variation_value']);
                    if ($variationValue) {
                        $variationValue->variation;
                        $price += $variationValue->price;
                    }
                }
            } else {
                unset($requestData['variations']);
            }

            // Add topping price if available
            $toppingPrice = 0;
            $toppings = null;
            if ($request->topping_ids != null && !empty($request->topping_ids)) {
                // Fetch toppings based on the provided IDs
                $toppings = Topping::whereIn('id', array_column($request->topping_ids, 'id'))->get();

                foreach ($toppings as $topping) {
                    // Find the corresponding topping from the request data
                    $requestedTopping = collect($request->topping_ids)->firstWhere('id', $topping->id);

                    // Calculate the price based on the quantity in the request
                    $toppingPrice += $topping->price * $requestedTopping['quantity'];
                    $topping->quantity = $requestedTopping['quantity']; // Set the quantity on the topping object
                }
            } else {
                unset($requestData['topping_ids']); // Remove topping data if not provided
            }

            // Add topping price to total price
            $price += $toppingPrice;

            // Update cart item with new quantity, price, variations, and toppings
            $cartItem->updateOrCreate(
                [
                    'product_id' => $cartItem->product_id, // This will check if the cart item with this product exists
                    'cart_id' => $cartItem->cart_id, // Ensures we're updating/creating within the correct cart
                ],
                [
                    'quantity' => $quantity,
                    'price' => $price, // Update price or set the price when creating
                    'product' => collect($cartItem->product),
                    'variations' => collect($variations), // Update variations or set them when creating
                    'toppings' => collect($toppings), // Update toppings or set them when creating
                ]
            );

            \DB::commit();

            return $this->sendResponse(null, __('CART_UPDATED'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="store_id", type="integer", example="1", description="ID store"),
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
            'store_id' => 'nullable|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $id = $request->id;
            $storeId = $request->store_id ?? '';
            if($storeId != ''){
                //Nếu có store_id thì xoá hết sản phẩm theo store
                CartItem::whereHas('cart',  function ($query) use ($storeId){
                    $query->where('store_id', $storeId);
                })->delete();
            }else{
                // Tìm cart item theo ID
                $cartItem = CartItem::find($id);
                // Xóa cart item
                $cartItem->delete();
            }
            return $this->sendResponse(null, __('CART_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


}
