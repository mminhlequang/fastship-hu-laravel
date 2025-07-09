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

    public function addPlayers(Request $request)
    {
        $requestData = $request->all();
        $teamId = $requestData['team_id'];
        if (!empty($requestData['players'])) {
            foreach ($requestData['players'] as &$itemP) {
                \DB::table('customers')->where('id', $itemP)->update([
                    'driver_team_id' => $teamId
                ]);
            }

        }
        toastr()->success(__('settings.updated_success'));

        return redirect('admin/teams');

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