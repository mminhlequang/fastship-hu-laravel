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
            // Check if the product is already favorited by the user
            $isFavorite = \DB::table('products_favorite')
                ->where('product_id', $request->id)
                ->where('user_id', auth('api')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('products_favorite')->insert([
                    'product_id' => $request->id,
                    'user_id' => auth('api')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('errors.PRODUCT_FAVORITE_ADD'));
            } else {
                \DB::table('products_favorite')
                    ->where('product_id', $request->id)
                    ->where('user_id', auth('api')->id())
                    ->delete();
                return $this->sendResponse(null, __('errors.PRODUCT_FAVORITE_REMOVE'));
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function favoriteStore(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            // Check if the product is already favorited by the user
            $isFavorite = \DB::table('stores_favorite')
                ->where('store_id', $request->id)
                ->where('user_id', auth('api')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('stores_favorite')->insert([
                    'store_id' => $request->id,
                    'user_id' => auth('api')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('STORE_FAVORITE_ADD'));
            } else {
                \DB::table('stores_favorite')
                    ->where('store_id', $request->id)
                    ->where('user_id', auth('api')->id())
                    ->delete();
                return $this->sendResponse(null, __('STORE_FAVORITE_REMOVE'));
            }

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }




    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    private function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    private function sendError($error, $errorMessages = [], $code = 200, $result = null)
    {
        $response = [
            'status' => false,
            'message' => $error,
            'data' => $result
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

}
