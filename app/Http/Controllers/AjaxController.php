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

    public function getMenuStore(Request $request)
    {
        $id = $request->id;
        $store = Store::findOrFail($id);
        $categories = Category::all();
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