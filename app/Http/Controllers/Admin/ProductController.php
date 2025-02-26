<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $locale = app()->getLocale();
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $categories = Category::getCategories(Category::all());

        $categoryId = $request->query('category_id');
        $storeId = $request->query('store_id');

        $product = Product::when($keyword, function ($query) use ($keyword, $locale) {
            $query->where('name_' . $locale, 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        })->when($categoryId, function ($query) use ($categoryId) {
            $query->where('category_id', 'like', $categoryId);
        })->when($storeId, function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        });

        $product = $product->latest()->paginate($perPage);

        return view('admin.products.index', compact('product', 'locale', 'categories', 'stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $locale = app()->getLocale();

        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $categories = Category::getCategories(Category::all());

        return view('admin.products.create', compact(
            'categories',
            'locale',
            'stores'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name_vi' => 'required|max:255',
                'category_id' => 'required',
                'price' => 'required',
                'image' => 'required',

            ],
            [
                'name_vi.required' => ' Tên sản phẩm không được để trống !',
                'image.required' => ' Hình ảnh bắt buộc phải có',
                'category_id.required' => ' Vui lòng chọn danh mục sản phẩm !',
            ]
        );

        $requestData = $request->all();
        //Kiểm tra tình trạng
        if (isset($request->active)) {
            $requestData["active"] = config("settings.active");
        }

        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image')) {
                $requestData['image'] = Product::uploadAndResize($request->file('image'));
            }
            $requestData['price'] = (double)str_replace(',', '', $request->price);

            Product::create($requestData);


        });

        toastr()->success(__('settings.created_success'));

        return redirect('admin/products');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locale = app()->getLocale();

        $product = Product::findOrFail($id);

        return view('admin.products.show', compact('product', 'locale'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locale = app()->getLocale();
        //Lấy tất cả thể loại sản phẩm
        $product = Product::findOrFail($id);

        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');


        $categories = Category::getCategories(Category::all());

        return view('admin.products.edit', compact(
            'locale',
            'categories',
            'product',
            'stores'
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name_vi' => 'required|max:255|unique:products,name_vi, ' . $id,
                'category_id' => 'required',

            ],
            [
                'category_id.required' => ' Vui lòng chọn danh mục sản phẩm !',
            ]
        );
        $product = Product::findOrFail($id);

        $requestData = $request->all();

        //Kiểm tra tình trạng
        if (isset($request->active))
            $requestData["active"] = config("settings.active");
        else
            $requestData["active"] = config("settings.inactive");

        \DB::transaction(function () use ($request, $requestData, $product) {
            if ($request->hasFile('image')) {
                $requestData['image'] = Product::uploadAndResize($request->file('image'));
            }
            $requestData['price'] = (double)str_replace(',', '', $request->price);

            $product->update($requestData);

        });

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Product::destroy($id);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/products');

    }
}