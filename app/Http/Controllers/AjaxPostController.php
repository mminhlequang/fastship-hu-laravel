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

    public function insertDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|min:10|max:15|unique:customers,phone',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        $validated['type'] = 4;
        // Nếu có password thì mã hóa, không thì gán chuỗi rỗng
        $validated['password'] = $request->filled('password')
            ? bcrypt($request->input('password'))
            : bcrypt(123456); // hoặc dùng default password nào đó

        $customer = \App\Models\Customer::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Added member successfully!',
            'data' => $customer
        ]);
    }

    public function addDrivers(Request $request)
    {
        $requestData = $request->all();
        $teamId = $requestData['team_id'];
        if (!empty($requestData['players'])) {
            foreach ($requestData['players'] as &$itemP) {
                \DB::table('driver_teams_members')->updateOrInsert(
                    [
                        'driver_id' => $itemP['player_id'],
                        'team_id' => $teamId,
                    ],
                    [
                        'driver_id' => $itemP['player_id'],
                        'team_id' => $teamId,
                        'role' => $itemP['type'] ?? 'staff'
                    ]
                );

            }

        }
        toastr()->success(__('settings.updated_success'));

        return redirect('admin/teams');

    }

    public function addPlayers(Request $request)
    {
        $requestData = $request->all();
        $teamId = $requestData['team_id'];
        if (!empty($requestData['players'])) {
            foreach ($requestData['players'] as &$itemP) {
                \DB::table('customers')->where('id', $itemP['player_id'])->update([
                    'driver_team_id' => $teamId,
                    'role' => $itemP['type'] ?? 'staff'
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