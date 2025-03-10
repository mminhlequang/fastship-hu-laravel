<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use App\Models\Order;
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

    public function updateModalStatus(Request $request)
    {
        $bookings = Order::find($request->id);
        $key = Approve::where('id', $request->approve_id)->first();
        $bookings->approve_id = $request->approve_id;
        $approve_id = $bookings->approve_id;
        switch ($approve_id) {
            case 1:
                $title = 'Tiếp nhận đơn hàng mới';
                $content = 'Đơn hàng '.$bookings->code.' đã được ghi nhận. Vui lòng kiểm tra thời gian nhận hàng dự kiến trong phần chi tiết đơn hàng nhé.';
                break;
            case 2:
                $title = 'Đơn hàng đã được tiếp nhận';
                $content = 'BeSoul đã tiếp nhận đơn hàng '.$bookings->code.' của bạn.';
                break;
            case 3:
                $title = 'Đơn hàng đã được vận chuyển';
                $content = 'Đơn hàng '.$bookings->code.' đang trong quá trình vận chuyển và dự kiến sẽ được giao trong thời gian sớm nhất.';
                break;
            case 4:
                $title = 'Giao hàng thành công';
                $content = 'Đơn hàng '.$bookings->code.' đã được giao thành công đến bạn.';
                break;
            case 5:
                $title = ' Đơn hàng hoàn thành';
                $content = 'BeSoul xác nhận đơn hàng '.$bookings->code.' đã hoàn thành.';
                break;
            case 6:
                $title = 'Xác nhận huỷ đơn hàng';
                $content = 'Yêu cầu hủy đơn hàng của bạn đã được chấp nhận. Đơn hàng '.$bookings->code.' đã được hủy thành công.';
                break;
        }

        $bookings->save();

        return response()->json([
            'approve' => $key,
            'data' => $bookings,
            'success' => true
        ]);
    }


}