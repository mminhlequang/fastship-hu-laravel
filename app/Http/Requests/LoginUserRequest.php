<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class LoginUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Xác thực người dùng có quyền gửi request này
    }

    public function rules()
    {
        return [
            'phone' => 'required|regex:/^\+?1?\d{9,15}$/',
            'password' => 'required',
            'type' => 'required|in:1,2,3'
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => __('api.phone_required'),
            'phone.regex' => __('api.phone_regex'),
            'phone.digits' => __('api.phone_digits'),
            'password.required' => __('api.password_required'),

        ];
    }

    public function failedValidation(Validator $validator)
    {
        // Gọi sendError nếu validation thất bại
        throw new ValidationException($validator, response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 200));
    }
}

