<?php

namespace App\Http\Controllers\Api;


use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;

class DriverController extends BaseController
{

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
