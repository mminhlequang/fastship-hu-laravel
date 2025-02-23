<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SysMenu;
use App\Traits\Authorizable;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');
        $locale = app()->getLocale();
        $categories = new Category();

        if (!empty($keyword)) {
            $categories = $categories->where('title_' . $locale, 'LIKE', "%$keyword%");
        }
        $categories = $categories->sortable()->paginate($perPage);

        return view('admin.categories.index', compact('categories', 'locale'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locale = app()->getLocale();
        $categories = Category::getCategories(Category::all());
        return view('admin.categories.create', compact('categories', 'locale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = Str::slug($requestData['name']);
        }


        if (!empty($request->hasFile('avatar')))
            $requestData['avatar'] = Category::uploadAndResize($request->file('avatar'));

        Category::create($requestData);


        toastr()->success(__('theme::categories.created_success'));

        return redirect('admin/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locale = app()->getLocale();
        $category = Category::findOrFail($id);
        $categories = Category::where('id', $category->parent_id)->first();
        return view('admin.categories.show', compact('category', 'categories', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::getCategories(Category::all());
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $locale = app()->getLocale();
        $requestData = $request->all();
        if (empty($request->get('slug')))
            $requestData['slug'] = Str::slug($requestData['name']);

        if (empty($requestData['active'])) {
            $requestData['active'] = config('settings.inactive');
        }
        \DB::transaction(function () use ($request, $requestData, $category) {
            if ($request->hasFile('avatar')) {
                \File::delete($category->image);
                $requestData['avatar'] = Category::uploadAndResize($request->file('avatar'));
            }
            $category->update($requestData);
        });

        toastr()->success(__('theme::business.updated_success'));
        return redirect('admin/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);

        toastr()->success(__('subjects.deleted_success'));
        return redirect('admin/categories');
    }
}
