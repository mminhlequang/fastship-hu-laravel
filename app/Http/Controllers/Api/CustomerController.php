<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\CustomerDetailResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Validator;
use Illuminate\Http\Request;
use App\Services\FirebaseAuthService;


/**
 * @OA\Info(title="FastShip API V1", version="1.0")
 */
class CustomerController extends BaseController
{

    protected $firebaseAuthService;

    public function __construct(FirebaseAuthService $firebaseAuthService)
    {
        $this->firebaseAuthService = $firebaseAuthService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Auth"},
     *     summary="Register",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Register (Type 1:Customer,2:Driver,3:Partner)",
     *         @OA\JsonContent(
     *             @OA\Property(property="id_token", type="string", example="0964541340"),
     *             @OA\Property(property="name", type="string", example="0964541340"),
     *             @OA\Property(property="phone", type="string", example="0964541340"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="type", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Register successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id_token' => 'required',
            'name' => 'required|max:120',
            'type' => 'required|in:1,2,3',
            'phone' => [
                'required',
                'regex:/^\+?1?\d{9,15}$/',
                function ($attribute, $value, $fail) use($request){
                    $type = $request->type ?? 1;
                    $id = \DB::table('customers')->where('phone', $value)->where('type', $type)->whereNull('deleted_at')->value("id");
                    if ($id) {
                        return $fail(__('api.phone_exits'));
                    }
                },
            ],
            'password' => 'required'

        ], [
            'name.required' => __('api.name_required'),
            'phone.required' => __('api.phone_required'),
            'phone.regex' => __('api.phone_regex'),
            'phone.digits' => __('api.phone_digits'),
            'password.required' => __('api.password_required')
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $phone = $request->phone;
            // Verify Firebase ID Token
            $firebaseUser = $this->firebaseAuthService->getUserByAccessToken($request->id_token);
            if (!$firebaseUser || $firebaseUser['phone_number'] != $phone) return $this->sendError('Invalid token or user not found');

            $token = $this->firebaseAuthService->login($phone);
            $requestData['uid'] = $firebaseUser['uid'];
            $customer = Customer::create($requestData);

            return $this->sendResponse([
                'token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'expires_in' => $token['expires_in'],
                'user' => new CustomerResource($customer)
            ], __("errors.USER_CREATED"));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Auth"},
     *     summary="Login Normal",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login Normal(Type 1:Customer,2:Driver,3:Partner)",
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="+84964541340"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="type", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $phone = $request->phone;
            $password = $request->password;
            $type = $request->type ?? 1;

            $customer = Customer::where([['phone', $phone], ["deleted_at", NULL], ['type', $type]])->first();
            if ($customer) {
                if ($customer->password == md5($password)) {
                    if ($customer->deleted_at != null || $customer->active != 1)
                        return $this->sendError(__('api.user_auth_deleted'));
                    $token = $this->firebaseAuthService->login($phone);
                    return $this->sendResponse([
                        'token' => $token['access_token'],
                        'refresh_token' => $token['refresh_token'],
                        'expires_in' => $token['expires_in'],
                        'user' => new CustomerResource($customer)
                    ], __('api.user_login_success'));
                } else
                    return $this->sendError(__('errors.AUTH_FAILED'));
            } else
                return $this->sendError(__('errors.AUTH_FAILED'));


        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/update_password",
     *     tags={"Auth"},
     *     summary="Update Password",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_password", type="string", example="123456"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update Profile Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function updatePassword(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($customer) {
                        if (md5($value) != $customer['password']) {
                            return $fail(__('password_not_match'));
                        }
                    }
                ],
                'password' => 'required'
            ],
            [
                'current_password.required' => __('api.password_current_not'),
                'password.required' => __('api.password_required'),
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $customer->update(['password' => $requestData['password']]);
            return $this->sendResponse(null, __('errors.PASSWORD_UPDATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reset_password",
     *     tags={"Auth"},
     *     summary="Reset Password",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reset password",
     *         @OA\JsonContent(
     *             @OA\Property(property="id_token", type="string", example="0964541340"),
     *             @OA\Property(property="phone", type="string", example="0964541340"),
     *             @OA\Property(property="new_password", type="string", example="123456"),
     *             @OA\Property(property="confirm_password", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Reset password Successful"),
     * )
     */
    public function resetPassword(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required',
                'id_token' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ],
            [
                'phone.required' => __('api.phone_required'),
                'new_password.required' => __('api.password_new_required'),
                'confirm_password.required' => __('api.password_confirm_required'),
                'confirm_password.same' => __('api.password_confirm_same'),
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            // Verify Firebase ID Token
            $firebaseUser = $this->firebaseService->signInWithIdToken($request->id_token);

            if (!$firebaseUser) return $this->sendError(__('errors.INVALID_TOKEN'));

            $customer = Customer::where([['phone', $requestData['phone']], ["deleted_at", NULL]])->first();

            if (!$customer) return $this->sendError(__('errors.USER_NOT_FOUND'));

            $customer->update(['password' => $requestData['new_password']]);

            $token = Customer::generateToken($customer);

            return $this->sendResponse([
                'token' => $token,
                'user' => new CustomerResource($customer)
            ], __('api.PASSWORD_RESET_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Auth"},
     *     summary="Get Profile",
     *     @OA\Response(response="200", description="Get Profile Successful"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getProfile(Request $request)
    {
        try {
            $customer = Customer::getAuthorizationUser($request);
            if (!$customer || $request->bearerToken() == null)
                return $this->sendError(__('errors.INVALID_SIGNATURE'));
            return $this->sendResponse(new CustomerDetailResource($customer), "Get profile successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/update_profile",
     *     tags={"Auth"},
     *     summary="Update Profile",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="0964541340", description="Họ và tên"),
     *             @OA\Property(property="phone", type="string", example="123456", description="SỐ điện thoại"),
     *             @OA\Property(property="email", type="string", example="123456", description="Địa chỉ Email"),
     *             @OA\Property(property="address", type="string", example="abcd"),
     *             @OA\Property(property="birthday", type="date", example="2020-05-19", description="Ngày sinh"),
     *             @OA\Property(property="avatar", type="string", format="binary"),
     *             @OA\Property(property="sex", type="integer", example="1", description="1:Nam, 2:Nữ"),
     *             @OA\Property(property="image_cccd_before", type="string", format="binary", description="Ảnh mặt trước CCCD"),
     *             @OA\Property(property="image_cccd_after", type="string", format="binary", description="Ảnh mặt sau CCCD"),
     *             @OA\Property(property="image_license_before", type="string", format="binary", description="Ảnh mặt trước giấy phép lái xe"),
     *             @OA\Property(property="image_license_after", type="string", format="binary", description="Ảnh mặt sau giấy phép lái xe"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *             @OA\Property(property="code_introduce", type="string", example="abcd", description="Mã giới thiệu"),
     *             @OA\Property(property="cccd", type="string", example="012345678910", description="ID cắn cước"),
     *             @OA\Property(property="tax_code", type="string", example="012345678910", description="Mã số thuế"),
     *             @OA\Property(property="is_tax_code", type="integer", example="0", description="1:Có mã số thuế, 0:Không có"),
     *             @OA\Property(property="car_id", type="integer", example="1", description="ID dòng xe tài xế"),
     *             @OA\Property(property="enabled_notify", type="integer", example="0", description="1:Bật thông báo, 0:Không"),
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
                'image_cccd_before' => 'nullable|image|max:5120',
                'image_cccd_after' => 'nullable|image|max:5120',
                'image_license_before' => 'nullable|image|max:5120',
                'image_license_after' => 'nullable|image|max:5120',
                'cccd' => 'nullable|digits:12',
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
                if ($request->hasFile('avatar'))
                    $requestData['avatar'] = Customer::uploadAndResize($request->file('avatar'));
                if ($request->hasFile('image_cmnd_before'))
                    $requestData['image_cmnd_before'] = Customer::uploadAndResize($request->file('image_cmnd_before'));
                if ($request->hasFile('image_cmnd_after'))
                    $requestData['image_cmnd_after'] = Customer::uploadAndResize($request->file('image_cmnd_after'));
                if ($request->hasFile('image_license_before'))
                    $requestData['image_license_before'] = Customer::uploadAndResize($request->file('image_license_before'));
                if ($request->hasFile('image_license_after'))
                    $requestData['image_license_after'] = Customer::uploadAndResize($request->file('image_license_after'));

                $customer->update($requestData);
                $customer->refresh();
                return $this->sendResponse(new CustomerResource($customer), __('errors.USER_UPDATED'));
            } else
                return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/check_phone",
     *     tags={"Auth"},
     *     summary="Check phone",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="0964541340"),
     *             @OA\Property(property="type", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Phone successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function checkPhone(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|in:1,2,3',
                'phone' => [
                    'required',
                    'regex:/^\+?1?\d{9,15}$/',
                    function ($attribute, $value, $fail) use ($request) {
                        $type = $request->type ?? 1;
                        $id = \DB::table('customers')->where("phone", $value)->where('type', $type)->whereNull('deleted_at')->value('id');
                        if ($id) {
                            return $fail(__('api.phone_exits'));
                        }
                    },
                ],
            ],
            [
                'phone.required' => __('api.phone_required'),
                'phone.regex' => __('api.phone_regex'),
                'phone.digits' => __('api.phone_digits')
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            return $this->sendResponse(1, 'Số điện thoại có thể sử dụng');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/update_device_token",
     *     tags={"Auth"},
     *     summary="Update device token",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Device Token object that needs to be update",
     *         @OA\JsonContent(
     *             @OA\Property(property="device_token", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update device Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function updateDeviceToken(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));

        $validator = Validator::make(
            $request->all(),
            [
                'device_token' => 'required',
            ]
        );
        try {
            if ($validator->passes()) {
                $customer->update(['device_token' => $requestData['device_token']]);

                return $this->sendResponse(null, "Cập nhật device token thành công");
            } else
                return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/refresh_token",
     *     tags={"Auth"},
     *     summary="Refresh token",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Token object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="refresh_token", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update device Successful"),
     * )
     */
    public function refreshToken(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'refresh_token' => 'required',
            ]
        );
        try {
            if ($validator->passes()) {
                $newAccessToken = $this->firebaseAuthService->refreshToken($request->refresh_token);
                return $this->sendResponse([
                    'access_token' => $newAccessToken['access_token'],
                    'refresh_token' => $newAccessToken['refresh_token'],
                ], "Refresh token thành công");
            } else
                return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/delete_account",
     *     tags={"Auth"},
     *     summary="Delete account",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reason for account deletion",
     *         @OA\JsonContent(
     *             @OA\Property(property="text", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Delete account Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function deleteAccount(Request $request)
    {
        $user = Customer::getAuthorizationUser($request);
        if (!$user)
            return $this->sendError(__('errors.INVALID_SIGNATURE'));

        try {
            $user->update([
                'deleted_request_at' => now(),
                'note' => $request->text ?? ''
            ]);
            return $this->sendResponse(null, __('errors.USER_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


}
