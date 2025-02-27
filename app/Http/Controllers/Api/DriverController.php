<?php

namespace App\Http\Controllers\Api;


use App\Models\Customer;
use App\Models\CustomerRating;
use Illuminate\Http\Request;
use Validator;

class DriverController extends BaseController
{
    /**
     * @OA\SecurityScheme(
     *     securityScheme="Bearer",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Enter your Bearer token below"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/v1/driver/rating",
     *     tags={"Driver"},
     *     summary="Get all rating driver",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="user_id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="keywords",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="star",
     *         in="query",
     *         description="star",
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
     *     @OA\Response(response="200", description="Get all rating")
     * )
     */
    public function getListRating(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'user_id' => 'required|exists:customers,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';
        $star = $request->star ?? '';

        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");

        try {

            $data = CustomerRating::with('user')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('content', 'like', "%$keywords%");
            })->when($star != '', function ($query) use ($star) {
                $query->where('star', $star);
            });

            $data = $data->where('user_id', $request->user_id)->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(CustomerRating::collection($data), 'Get all rating successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/driver/rating/insert",
     *     tags={"Driver"},
     *     summary="Rating driver",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating product with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="star", type="integer", example=1),
     *             @OA\Property(property="text", type="string", example="abcd"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function insertRating(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'user_id' => 'required|exists:customers,id',
            'star' => 'required|in:1,2,3,4,5',
            'text' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");

        try {
            // Check if the driver is already rating by the user
            $isRating = \DB::table('customers_rating')
                ->where('user_id', $request->user_id)
                ->where('creator_id', $customer->id)
                ->exists();

            if ($isRating) return $this->sendResponse(null, __('api.driver_rating_exits'));

            \DB::table('customers_rating')
                ->insert([
                    'user_id' => $request->user_id,
                    'creator_id' => $customer->id,
                    'star' => $request->star,
                    'content' => $request->text ?? '',
                ]);

            return $this->sendResponse(null, __('api.driver_rating'));

        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/driver/upload",
     *     tags={"Driver"},
     *     summary="Upload images",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating product with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", format="uri", example="http://example.com/image1.jpg")
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Upload successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function uploadImages(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'images' => 'array', // Ensure that 'images' is an array
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10048', // Validate each image
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        \DB::beginTransaction();
        try {

            if (!empty($request->images)) {
                foreach ($request->images as $itemI)
                    if ($request->hasFile($itemI))
                        \DB::table('customers_images')->insert([
                            'user_id' => $customer->id,
                            'image' => Customer::uploadAndResize($itemI),
                        ]);
            }

            \DB::commit();

            return $this->sendResponse(null, __('api.upload_success'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }

}
