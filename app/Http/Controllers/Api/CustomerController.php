<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;


/**
 * @OA\Info(title="FastShip API", version="0.1")
 */
class CustomerController extends BaseController
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="0964541340"),
     *             @OA\Property(property="phone", type="string", example="0964541340"),
     *             @OA\Property(property="password", type="string", example="123456"),
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
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone' => [
                function ($attribute, $value, $fail) {
                    $id = \DB::table('customers')->where('phone', $value)->whereNull('deleted_at')->value("id");
                    if ($id) {
                        return $fail('Số điện thoại đã được đăng ký');
                    }
                },
            ],
            'password' => 'required'

        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $customer = Customer::create($requestData);

            $token = Customer::generateToken($customer);

            return $this->sendResponse([
                'token' => $token,
                'user' => new CustomerResource($customer)
            ], "Tạo tài khoản thành công");

        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login Normal",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login Normal",
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="0964541340"),
     *             @OA\Property(property="password", type="string", example="123456"),
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
    public function login(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'required'
        ], [
            'phone.regex' => 'SĐT không đúng định dạng'
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $phone = $requestData['phone'];
            $password = $requestData['password'];
            $customer = Customer::where([['phone', $phone], ["deleted_at", NULL]])->first();
            if ($customer) {
                if ($customer->password == md5($password)) {
                    if ($customer->deleted_at != null)
                        return $this->sendError('Tài khoản của bạn đã bị xóa');

                    $token = Customer::generateToken($customer);
                    return $this->sendResponse([
                        'token' => $token,
                        'user' => new CustomerResource($customer)
                    ], "Đăng nhập thành công");
                } else
                    return $this->sendError('Tài khoản hoặc mật khẩu không chính xác');
            } else
                return $this->sendError('Tài khoản hoặc mật khẩu không chính xác');
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/update_password",
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
            return $this->sendError("Invalid signature");
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($customer) {
                        if (md5($value) != $customer['password']) {
                            return $fail('Mật khẩu hiện tại không đúng, vui lòng thử lại!');
                        }
                    }
                ],
                'password' => 'required'
            ],
            [
                'current_password.required' => 'Mật khẩu hiện tại không được để trống!',
                'password.required' => 'Mật khẩu mới không được để trống!',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $customer->update(['password' => $requestData['password']]);
            return $this->sendResponse(null, "Cập nhật mật khẩu thành công!");
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reset_password",
     *     tags={"Auth"},
     *     summary="Reset Password",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reset password",
     *         @OA\JsonContent(
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
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ],
            [
                'phone.required' => 'SĐT không được để trống!',
                'new_password.required' => 'Mật khẩu mới không được để trống!',
                'confirm_password.required' => 'Mật khẩu xác nhận không được để trống!',
                'confirm_password.same' => 'Mật khẩu xác nhận không giống mật khẩu mới',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $customer = Customer::where([['phone', $requestData['phone']], ["deleted_at", NULL]])->first();

            if (!$customer) return $this->sendError('Không tìm thấy tài khoản');

            $customer->update(['password' => $requestData['new_password']]);

            $token = Customer::generateToken($customer);

            return $this->sendResponse([
                'token' => $token,
                'user' => new CustomerResource($customer)
            ], "Reset mật khẩu mới thành công!");
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Auth"},
     *     summary="Get Profile",
     *     @OA\Response(response="200", description="Get Profile Successful"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getProfile(Request $request)
    {
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer || $request->bearerToken() == null)
            return $this->sendError("Invalid signature");
        try {
            return $this->sendResponse(new CustomerResource($customer), "Lấy thông tin người dùng thành công");
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/update_profile",
     *     tags={"Auth"},
     *     summary="Update Profile",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="0964541340"),
     *             @OA\Property(property="phone", type="string", example="123456"),
     *             @OA\Property(property="address", type="string", example="abcd"),
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
            return $this->sendError("Invalid signature");
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'email',
                'birth_date' => 'date_format:Y-m-d',
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
                'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'phone' => [
                    function ($attribute, $value, $fail) use ($customer) {
                        $id = \DB::table('customers')->where([["id", "!=", $customer->id]])->whereNull('deleted_at')->value("id");
                        if ($id) {
                            return $fail('Số điện thoại đã được đăng ký');
                        }
                    },
                ],
            ],
            [
                'email.email' => 'Email không hợp lệ!',
                'phone.regex' => 'Số điện thoại không hợp lệ!',
                'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự!'
            ]
        );
        try {
            if ($validator->passes()) {
                if ($request->hasFile('avatar'))
                    $requestData['avatar'] = Customer::uploadAndResize($request->file('avatar'));
                $customer->update($requestData);
                return $this->sendResponse(new CustomerResource($customer), "Cập nhật thông tin thành công");
            } else
                return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/check_phone",
     *     tags={"Auth"},
     *     summary="Check phone",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="0964541340"),
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
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'phone' => [
                    function ($attribute, $value, $fail) {
                        $id = \DB::table('customers')->where("phone", $value)->whereNull('deleted_at')->value('id');
                        if ($id) {
                            return $fail('Số điện thoại đã được đăng ký');
                        }
                    },
                ],
            ],
            [
                'phone.required' => 'Số điện thoại bắt buộc phải có',
                'phone.regex' => 'Số điện thoại không hợp lệ!',
                'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự!'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            return $this->sendResponse(1, 'Số điện thoại có thể sử dụng');
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/update_device_token",
     *     tags={"Auth"},
     *     summary="Update device token",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
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
            return $this->sendError("Invalid signature");

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
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/delete_account",
     *     tags={"Auth"},
     *     summary="Delete account",
     *     @OA\Response(response="200", description="Delete account Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function deleteAccount(Request $request)
    {
        $user = Customer::getAuthorizationUser($request);
        if (!$user)
            return $this->sendError("Invalid signature");

        try {
            $user->update(['deleted_at' => Carbon::now()]);
            return $this->sendResponse(null, "Xóa tài khoản thành công");
        } catch (\Exception $e) {
            return $this->sendError('Lỗi hệ thống ' . $e->getMessage());
        }

    }


}
