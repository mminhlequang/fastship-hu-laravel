<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use App\Models\Order;
use App\Models\Store;
use App\Services\FirebaseService;
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

    public function updateMenuStore(Request $request)
    {
        $id = $request->store_id;
        $store = Store::findOrFail($id);
        $categories = $request->input('categories', []);
        $store->categories()->sync($categories); // cập nhật bảng trung gian
        return response()->json(['status' => 'success']);
    }


}