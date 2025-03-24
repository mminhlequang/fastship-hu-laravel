<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AjaxFrontendController extends Controller
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

    public function loadChildCategoryWithProductsByUser(Request $request)
    {
        try {
            $categoryId = $request->id;
            $categories = Category::with('products')
                ->where('parent_id', $categoryId)->whereNull('deleted_at')->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->get();

            return \response()->json([
                'status' => true,
                'view' => view('theme::front-end.pages.categories', compact('categories'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }

    public function loadChildCategoryWithProductsByStore(Request $request)
    {
        try {
            $storeId = $request->store_id ?? 0;
            $categoryId = $request->id;
            $categories = Category::with('products')
                ->when($storeId != 0, function ($query) use ($storeId) {
                    $query->whereHas('stores', function ($query) use ($storeId) {
                        $query->where('store_id', $storeId);
                    });
                    $query->join('categories_stores', 'categories.id', '=', 'categories_stores.category_id')
                        ->select('categories.*') // Select all fields from the categories table
                        ->orderBy('categories_stores.arrange');
                })->where('parent_id', $categoryId)->whereNull('deleted_at')->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->get();

            return \response()->json([
                'status' => true,
                'view' => view('theme::front-end.pages.categories', compact('categories'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function favoriteProduct(Request $request)
    {
        try {
            $id = $request->id;
            \DB::table('address_delivery')->where('id', $id)->delete();
            return response()->json(['status' => true, 'message' => 'Xóa thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


}
