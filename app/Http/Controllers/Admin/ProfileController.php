<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserProfiles;
use Validator;

class ProfileController extends Controller
{

    /**
     * Show profile
     *
     * @return void
     */
    public function getProfile()
    {
        $user = \Auth::user();
        $user_roles = array_pluck($user->roles, 'name');
        $profile = $user->profile;

        if (!empty($profile->birthday))
            $profile->birthday = Carbon::parse($profile->birthday)->format(config('settings.format.date'));

        return view('admin.users.profile', compact('user', 'user_roles', 'profile'));
    }

    /**
     * Update profile
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function postProfile(Request $request)
    {

        $user = \Auth::user();
        $dateValidate = $request->wantsJson() ? 'date|date_format:Y-m-d' : 'date_format:"' . config('settings.format.date') . '"';
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'profile.birthday' => 'nullable|' . $dateValidate,
            'profile.avatar' => 'nullable|mimes:jpeg,jpg,png',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            //'username' => 'required|alpha_num|min:6|max:80|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
        ]);

        if ($validator->fails()) {
            return redirect('profile')->withErrors($validator)->withInput();
        }
        $data = $request->except(['password', 'username']);

        if ($request->has('password') && !empty($request->password)) {
            $data["password"] = $request->password;
        }
        $profile = $user->profile;
        if (!$profile) $profile = new UserProfiles($data["profile"]);
        if (!empty($request["profile"]["birthday"])) {
            if (!$request->wantsJson()) {
                $data["profile"]["birthday"] = \DateTime::createFromFormat(config('settings.format.date'), $request["profile"]["birthday"])->format('Y-m-d');
            }
        }

        if ($request->hasFile('profile.avatar')) {
            if (!empty($profile->avatar))
                \Storage::delete($profile->avatar);

            $data["profile"]["avatar"] = $profile->uploadAndResizeAvatar($request->file('profile')["avatar"]);
        }

        $user->update($data);

        foreach ($data["profile"] as $nameCol => $valCol) {
            $profile->{$nameCol} = $valCol;
        }

        $user->profile()->save($profile);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('message.user.profile_updated'),
                'user_id' => $user->id,
            ]);
        }

        toastr()->success(__('message.user.updated_success'));

        if ($user->isAdminCompany())
            return redirect('admin/users');
        else
            return redirect('admin');
    }

    public function changePassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'password_current' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        if (!empty($value) && !\Hash::check($value, \Auth::user()->getAuthPassword())) {
                            return $fail('Mật khẩu hiện tại không đúng, vui lòng thử lại.');
                        }
                    }
                ],
                'password' => 'required|min:6|confirmed|different:password_current',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'password.different' => 'Mật khẩu mới và mật khẩu hiện tại không được giống nhau.',
                'password.confirmed' => 'Giá trị xác nhận trong trường mật khẩu không khớp.',
                'password_confirmation.same' => 'Mật khẩu xác nhận với mật khẩu mới phải giống nhau.',
            ]
        );

        if ($request->has('password') && !empty($request->password)) {
            $data["password"] = $request->password;
        }

        $user = \Auth::user();
        $user->update($data);
        $user->revokeAllTokens();

        toastr()->success(__('message.user.updated_success'));

        if ($user->isAdminCompany())
            return redirect('admin/users');
        else
            return redirect('admin');
    }
}
