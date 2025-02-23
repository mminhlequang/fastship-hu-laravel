<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Promotion;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;

class AjaxPostController extends Controller
{
    /**
     * Gọi ajax: sẽ gọi đến hàm = tên $action
     * @param Request $action
     * @param Request $request
     * @return mixed
     */
    public function index($action, Request $request)
    {
        return $this->{$action}($request);
    }

    public function customerRegister(Request $request)
    {
        $requestData = $request->all();
        //Kiểm tra chương trình
        $promotionId = $request->get('id');
        $validator = Validator::make(
            $requestData, [
            'Họ_tên' => 'required|max:100',
            'Điện_thoại' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15',
                function ($attribute, $value, $fail) use ($promotionId) {
                    $id = \DB::table('customers')->where([['phone', $value], ['promotion_id', $promotionId]])->value('id');
                    if ($id != null) {
                        return $fail('SĐT đã đăng ký tham gia chương trình này');
                    }
                },
            ],
            'Email' => ['email', 'max:50', function ($attribute, $value, $fail) use ($promotionId) {
                $id = \DB::table('customers')->where([['email', $value], ['promotion_id', $promotionId]])->value('id');
                if ($id != null) {
                    return $fail('Email đã đăng ký tham gia chương trình này');
                }
            },
            ],
            'file' => 'max:5000',
        ], [
            'Điện_thoại.min' => 'Số điện thoại tổi thiểu 10 kí tự',
            'Điện_thoại.max' => 'Số điện thoại tổi đa 15 kí tự',
            'Điện_thoại.regex' => 'Số điện thoại không đúng định dạng',
            'Email.email' => 'Email không đúng định dạng',
            'file.max' => 'File tối đa 5MB',
        ]);

        if ($validator->fails())
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        $currentDate = Carbon::now()->format('Y-m-d');
        try {
            $promotion = \DB::table('promotions')->where('id', $promotionId)->select(['date_start', 'date_end', 'active'])->first();
            if ($promotion == null)
                return response()->json(['status' => false, 'errors' => 'Chương trình bạn đăng ký không tồn tại']);
            if ($promotion->active != 1)
                return response()->json(['status' => false, 'errors' => 'Chương trình bạn đăng ký chưa kích hoạt']);
            if ($promotion->date_start != null && $promotion->date_start > $currentDate)
                return response()->json(['status' => false, 'errors' => "Chương trình bạn đăng ký chưa bắt đầu"]);
            if ($promotion->date_end < $currentDate)
                return response()->json(['status' => false, 'errors' => "Chương trình bạn đăng ký đã kết thúc"]);

            //Xác thực thông tin
            $fullName = $requestData['Họ_tên'] ?? "";
            $email = $requestData['Email'] ?? "";
            $phone = $requestData['Điện_thoại'] ?? "";
            $address = $requestData['Địa_chỉ'] ?? "";
            $birthday = isset($requestData['Ngày_sinh']) ? \DateTime::createFromFormat(config('settings.format.date'), $requestData["Ngày_sinh"])->format('Y-m-d') : "";

            //Upload file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $name = config('filesystems.disks.public.path') . '/register/' . time() . '_' . $fileName;
                $file->move(storage_path('app/public') . '/register/', $name);
            } else
                $name = null;


            //Loại bỏ các trường theo tên truyền vào
            $requestData = $request->except(['_token', 'file', 'id', 'province_id', 'ward_id', 'district_id', 'address', 'birthday', 'form-embed']);

            // Define the desired key order
            $desiredKeyOrder = ["Họ_tên", "Email", "Điện_thoại", "Ngày_sinh", "Tỉnh", "Huyện", "Xã", "Địa_chỉ"];

            // Create a new array with the desired keys and their values
            $newArray = [];

            foreach ($desiredKeyOrder as $key) {
                if (isset($data[$key])) {
                    $newArray[$key] = $data[$key];
                }
            }

            // Push any additional keys not present in $desiredKeys to the end
            foreach ($requestData as $key => $value) {
                if (!in_array($key, $desiredKeyOrder) && !isset($newArray[$key])) {
                    $newArray[$key] = $value;
                }
            }

            $data = Customer::create([
                'name' => $fullName,
                'input' => json_encode($newArray),
                'email' => $email,
                'phone' => $phone,
                'avatar' => $name,
                'address' => $address,
                'birthday' => $birthday,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'promotion_id' => $promotionId,
                'active' => 0,
                'created_at' => Carbon::now()
            ]);

            //Link QR
            $text = url('scan-qr.html?customer_id=' . $data->id . '&promotion_id=' . $request->get('id'));
            $avatar = ($name != null) ? url($name) : null;
            return response()->json([
                'status' => true,
                'view' => view('frontends.modal-register-inner', compact('data', 'text', 'avatar'))->render(),
                'data' => $data,
                'text' => $text,
                'message' => 'Đăng ký tham gia chương trình thành công']);
        } catch (\Exception $e) {
            return \response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }

    }


}