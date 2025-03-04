<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\CustomerResource;
use App\Http\Resources\DataResource;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Resources\StepResource;
use App\Models\Customer;
use App\Models\CustomerCar;
use App\Models\CustomerRating;
use App\Models\PaymentMethod;
use App\Models\Step;
use Illuminate\Http\Request;
use Validator;

class DriverController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/driver/rating",
     *     tags={"Driver"},
     *     summary="Get all rating driver",
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
     *     @OA\Response(response="200", description="Get all rating"),
     *     security={{"bearerAuth":{}}},
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
            return $this->sendError(__('errors.INVALID_SIGNATURE'));

        try {

            $data = CustomerRating::with('user')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('content', 'like', "%$keywords%");
            })->when($star != '', function ($query) use ($star) {
                $query->where('star', $star);
            });

            $data = $data->where('user_id', $request->user_id)->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(CustomerRating::collection($data), 'Get all rating successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/driver/cars",
     *     tags={"Driver"},
     *     summary="Get all cars driver",
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="keywords",
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
     *     @OA\Response(response="200", description="Get all cars driver"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getListCars(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        try {

            $data = CustomerCar::when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            });

            $data = $data->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->skip($offset)->take($limit)->get();

            return $this->sendResponse(DataResource::collection($data), 'Get all cars successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/driver/payment_method",
     *     tags={"Driver"},
     *     summary="Get all payment method",
     *     @OA\Response(response="200", description="Get all payment")
     * )
     */
    public function getListPayment(Request $request)
    {
        try {
            $data = PaymentMethod::where('active', 1)->orderBy('arrange')->get();
            return $this->sendResponse(PaymentMethodResource::collection($data), 'Get all payment successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/driver/steps",
     *     tags={"Driver"},
     *     summary="Get all steps",
     *     @OA\Response(response="200", description="Get all steps driver"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getListSteps(Request $request)
    {
        try {
            $data = Step::orderBy('arrange')->get();
            return $this->sendResponse(StepResource::collection($data), 'Get all steps successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/driver/steps/confirm",
     *     tags={"Driver"},
     *     summary="Confirm step",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Confirm step",
     *         @OA\JsonContent(
     *             @OA\Property(property="step_id", type="integer", example="1", description="ID step confirm"),
     *             @OA\Property(property="image", type="string", description="Image if exits")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Confirm successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function confirmStep(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));

        $validator = \Validator::make($requestData, [
            'step_id' => 'required|exists:steps,id', // Ensure that 'images' is an array
            'image' => 'nullable|image|max:10048', // Ensure that 'images' is an array
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));


        \DB::beginTransaction();
        try {
            $stepId = $request->step_id;
            $customerId = $customer->id ?? 0;

            // Check if the record exists
            $exists = \DB::table('customers_steps')
                ->where('step_id', $stepId)
                ->where('user_id', $customerId)
                ->exists();

            if ($exists) {
                // If record exists, delete it
                \DB::table('customers_steps')
                    ->where('step_id', $stepId)
                    ->where('user_id', $customerId)
                    ->delete();
            } else {
                // If record doesn't exist, insert a new one
                \DB::table('customers_steps')->insert([
                    'step_id' => $stepId,
                    'user_id' => $customerId,
                    'created_at' => now(),  // Add created_at field if needed
                    'updated_at' => now()   // Add updated_at field if needed
                ]);
            }


            $message = ($exists) ? __('CONFIRM_REMOVE_SUCCESS') : __('CONFIRM_ADD_SUCCESS');

            \DB::commit();

            return $this->sendResponse(null, $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
            return $this->sendError(__('errors.INVALID_SIGNATURE'));

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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/driver/upload",
     *     tags={"Driver"},
     *     summary="Upload image",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Store object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="type", type="string", description="image_cccd_before, image_cccd_after, image_license_before, image_license_after, avatar...")
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

    public function uploadImage(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'image' => 'required|image|max:10048', // Ensure that 'images' is an array
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {
            $customer = Customer::getAuthorizationUser($request);
            if (!$customer)
                return $this->sendError(__('errors.INVALID_SIGNATURE'));

            if ($request->hasFile('image'))
                $image = Customer::uploadAndResize($request->image);
            else
                $image = null;

            \DB::table('customers_images')->updateOrInsert([
                'user_id' => $customer->id,
                'type' => $request->type
            ], [
                'user_id' => $customer->id,
                'image' => $image,
                'type' => $request->type
            ]);

            \DB::commit();

            return $this->sendResponse($image, __('UPLOAD_SUCCESS'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/driver/update_profile",
     *     tags={"Driver"},
     *     summary="Update Profile",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Update profile driver",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="0964541340", description="Họ và tên"),
     *             @OA\Property(property="sex", type="integer", example="1", description="1:Nam, 2:Nữ"),
     *             @OA\Property(property="birthday", type="date", example="2020-05-19", description="Ngày sinh"),
     *             @OA\Property(property="code_introduce", type="string", example="abcd", description="Mã giới thiệu"),
     *             @OA\Property(property="address", type="string", example="abcd"),
     *             @OA\Property(property="cccd", type="string", example="012345678910", description="ID cắn cước"),
     *             @OA\Property(property="cccd_date", type="date", example="2020-05-19", description="Ngày CCCD"),
     *             @OA\Property(property="image_cccd_before", type="string", example="storage/image/products/2023-12-05-17-54-57-101.jpg", description="Ảnh mặt trước CCCD"),
     *             @OA\Property(property="image_cccd_after", type="string", example="storage/image/products/2023-12-05-17-54-57-101.jpg", description="Ảnh mặt sau CCCD"),
     *             @OA\Property(property="image_license_before", type="string", format="binary", description="Ảnh mặt trước giấy phép lái xe"),
     *             @OA\Property(property="image_license_after", type="string", format="binary", description="Ảnh mặt sau giấy phép lái xe"),
     *             @OA\Property(property="address_temp", type="string", example="Address temp", description="Địa chỉ tạm thời"),
     *             @OA\Property(property="is_tax_code", type="integer", example="0", description="1:Có mã số thuế, 0:Không có"),
     *             @OA\Property(property="tax_code", type="string", example="012345678910", description="Mã số thuế"),
     *             @OA\Property(property="car_id", type="integer", example="1", description="ID dòng xe tài xế"),
     *             @OA\Property(property="payment_method", type="integer", example="1", description="ID paymen_method"),
     *             @OA\Property(property="card_number", type="string", example="012345678910", description="Card number"),
     *             @OA\Property(property="card_expires", type="string", example="012345678910", description="Card exp"),
     *             @OA\Property(property="card_cvv", type="string", example="012345678910", description="Card CVV"),
     *             @OA\Property(property="contacts", type="string", example="012345678910", description="List địa chỉ liên hệ khẩn cấp"),
     *             @OA\Property(property="license", type="string", example="012345678910", description="Biển số xe")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update Profile Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function updateProfile(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
                'email' => 'nullable|email|max:120',
                'birthday' => 'nullable|date_format:Y-m-d',
                'avatar' => 'nullable|image|max:5120',
                'cccd' => 'nullable|digits:12',
                'cccd_Date' => 'nullable|date_format:Y-m-d',
                'sex' => 'nullable|in:1,2',
                'is_tax_code' => 'nullable|in:0,1',
                'enabled_notify' => 'nullable|in:0,1',
                'car_id' => 'nullable|integer',
                'tax_code' => 'nullable|max:120',
                'phone' => [
                    'required',
                    'regex:/^\+?1?\d{9,15}$/',
                    function ($attribute, $value, $fail) use ($customer, $request) {
                        $type = $request->type ?? 1;
                        $id = \DB::table('customers')->where([["id", "!=", $customer->id], ['type', $type]])->whereNull('deleted_at')->value("id");
                        if ($id) {
                            return $fail(__('api.phone_exits'));
                        }
                    },
                ],
            ],
            [
                'birthday.date_format' => __('api.date_format'),
                'email.email' => __('api.email_valid'),
                'phone.regex' => __('api.phone_regex'),
                'phone.digits' => __('api.phone_digits')
            ]
        );
        try {
            if ($validator->passes()) {
                $customer->profile()->update($requestData);
                return $this->sendResponse(new CustomerResource($customer), __('errors.USER_UPDATED'));
            } else
                return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

}
