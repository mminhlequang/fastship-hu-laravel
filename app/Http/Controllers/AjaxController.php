<?php

namespace App\Http\Controllers;


use App\Http\Resources\ProductResource;
use App\Models\Discount;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Category;
use App\Models\Customer;

use App\Models\News;
use App\Models\PaymentAccount;
use App\Models\Product;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjaxController extends Controller
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

    public function deletePlayer(Request $request)
    {
        try {
            $id = $request->id;
            $type = $request->type ?? 1;

            if ($type == 1)
                \DB::table('customers')->where('id', $id)->update([
                    'driver_team_id' => NULL
                ]);
            else
                \DB::table('driver_teams_members')->where('id', $id)->delete();
            return \response()->json(['status' => true, 'message' => 'Remove member successfully']);
        } catch (\Exception $e) {
            return \response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function loadViewModalPlayerClub(Request $request)
    {

        try {
            $id = $request->id;
            $playersJoin = Customer::where('driver_team_id', $id)->where('type', 4)->get();
            return \response()->json(['view' => view('admin.teams.modal_inner', compact('playersJoin'))->render()]);
        } catch (\Exception $e) {
            return \response()->json(['view' => null]);
        }
    }

    public function loadViewModalDriverClub(Request $request)
    {
        try {
            $id = $request->id;
            $playersJoin = \DB::table('driver_teams_members as dtm')
                ->join('customers as c', 'c.id', '=', 'dtm.driver_id')
                ->where('dtm.team_id', $id)
                ->select('c.*', 'dtm.role', 'dtm.id as dt_id')
                ->get();
            return \response()->json(['view' => view('admin.teams.modal_member_inner', compact('playersJoin'))->render()]);
        } catch (\Exception $e) {
            return \response()->json(['view' => null]);
        }
    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $type = $request->type ?? 4;
        $data = \DB::table('customers')
            ->where('type', $type)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('phone', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->select(['id', 'name', 'avatar', 'phone', 'email'])
            ->get();

        return response()->json($data);
    }

    public function getMenuStore(Request $request)
    {
        $id = $request->id;
        $store = Store::findOrFail($id);
        $categories = Category::where('active', 1)->get();
        $selected = $store->categories()->pluck('category_id')->toArray();

        return view('admin.stores.menu', compact('categories', 'selected'));
    }

    public function getReferenceByType(Request $request)
    {
        $type = $request->type ?? 'news';
        if ($type == 'voucher')
            $data = Discount::where('active', 1)->pluck('name', 'id');
        elseif ($type == 'store')
            $data = Store::where('active', 1)->pluck('name', 'id');
        else
            $data = News::where('active', 1)->pluck('name_en', 'id');

        return \response()->json($data);
    }

    public function activeTable(Request $request)
    {
        $ids = $request->ids;
        $table = $request->table;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $data = \DB::table($table)->where('id', $item)->first();
            $active = $data->active == config('settings.active') ? config('settings.inactive') : config('settings.active');
            \DB::table($table)->where('id', $item)->update(['active' => $active]);
            if ($table == 'stores' && $active == 1) {
                //Gửi thông báo
                $title = 'Your store is active';
                $description = 'We have completed the store application review process. Your store is now live';
                Notification::insertNotificationByUser($title, $description, "", 'system', $data->creator_id, $item);
            }
        }
        toastr()->success(__('theme::news.updated_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function deleteTable(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        $table = $request->table;
        foreach ($arrId as $item) {
            \DB::table($table)->where('id', $item)->update([
                'deleted_at' => now()
            ]);
        }
        toastr()->success(__('theme::news.deleted_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function activeNews(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $news = News::findOrFail($item);
            $active = $news->active == config('settings.active') ? config('settings.inactive') : config('settings.active');
            \DB::table('news')->where('id', $news->id)->update(['active' => $active]);
        }
        toastr()->success(__('theme::news.updated_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function activePayments(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $data = PaymentAccount::findOrFail($item);
            $active = $data->is_verified == config('settings.active') ? config('settings.inactive') : config('settings.active');
            \DB::table('payment_accounts')->where('id', $data->id)->update(['is_verified' => $active]);
        }
        toastr()->success(__('theme::news.updated_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function deleteNews(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            News::destroy($item);
        }
        toastr()->success(__('theme::news.deleted_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function activeCate(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $z = Category::findOrFail($item);
            $active = $z->active == config('settings.active') ? config('settings.inactive') : config('settings.active');
            \DB::table('categories')->where('id', $z->id)->update(['active' => $active]);
        }
        toastr()->success(__('settings.updated_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function deleteCate(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            Category::destroy($item);
        }
        toastr()->success(__('settings.deleted_success'));

        return \response()->json(['success' => 'ok']);
    }

    public function activeCustomers(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $customers = Customer::find($item);
            $active = ($customers->active == 1) ? 0 : 1;
            \DB::table('customers')->where('id', $customers->id)->update([
                'updated_at' => Carbon::now(),
                'active' => $active
            ]);
        }
        toastr()->success(__('Cập nhật trạng thái thành công'));
        return \response()->json(['success' => 'ok']);
    }


    public function deleteCustomers(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            \DB::table('customers')->where('id', $item)->update([
                'deleted_at' => now()
            ]);
        }
        toastr()->success(__('Xóa dữ liệu thành công'));

        return \response()->json(['success' => 'ok']);
    }


    public function getDistricts(Request $request)
    {
        $data = \DB::table('districts')->where('province_id', $request->id)->pluck('name', 'id');
        return \response()->json($data);
    }

    public function getWards(Request $request)
    {
        $data = \DB::table('wards')->where('district_id', $request->id)->pluck('name', 'id');
        return \response()->json($data);
    }

    /**
     * Hien thi modal trang thai
     */
    public function getModalStatus($id)
    {
        $status = Order::find($id)->first();
        return response()->json([
            'data' => $status,
            'success' => 'Get Status Modal successfully.'
        ]);
    }

    public function getAddress(Request $request)
    {
        $requestData = $request->all();
        $data = \DB::table('address_delivery')->where('customer_id', $requestData['id'])->pluck('address', 'id');
        return \response()->json($data);
    }

    public function getProduct(Request $request)
    {
        $product = Product::with('category')->where('id', $request->id)->first();
        return response()->json(new ProductResource($product));
    }

}