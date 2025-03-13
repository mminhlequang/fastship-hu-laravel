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
        $keywords = $request->get('search');
        $perPage = config('settings.perpage');
        $locale = app()->getLocale();

        // Fetch paginated data based on keywords and order by 'arrange'
        $data = Category::when($keywords != '', function ($query) use ($keywords, $locale) {
            $query->where('name_'.$locale, 'like', "%$keywords%");
        })
            ->whereNull('deleted_at')
            ->orderBy('arrange')
            ->paginate($perPage);  // Use paginate to fetch data

        // Convert the paginated data to a hierarchical structure
        $categories = $this->getTree($data->items());

        return view('admin.categories.index', compact('data', 'categories', 'locale'));
    }

    private function getTree($services, $parent_id = null, $level = 0)
    {
        $result = [];

        foreach ($services as $service) {
            if ($service->parent_id == $parent_id) {
                $service->level = $level;
                $result[] = $service;

                // Recursively get children services
                $result = array_merge($result, $this->getTree($services, $service->id, $level + 1));
            }
        }

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $locale = app()->getLocale();
        $categories = Category::getCategories(Category::all());
        return view('admin.categories.create', compact('categories', 'locale', 'stores'));
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

        if (!empty($request->hasFile('image')))
            $requestData['image'] = Category::uploadAndResize($request->file('image'));

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
        $stores = \DB::table('stores')->whereNull('deleted_at')->pluck('name', 'id');
        $stores = $stores->prepend("--Choose store --", '');

        $categories = Category::getCategories(Category::all());
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category', 'categories', 'stores'));
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

        if (!empty($requestData['active']))
            $requestData['active'] = config('settings.active');
        else
            $requestData['active'] = config('settings.inactive');

        \DB::transaction(function () use ($request, $requestData, $category) {
            if ($request->hasFile('image')) {
                \File::delete($category->image);
                $requestData['image'] = Category::uploadAndResize($request->file('image'));
            }
            $category->update($requestData);
        });

        toastr()->success(__('theme::categories.updated_success'));
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
        Category::where('id', $id)->update([
            'deleted_at' => now()
        ]);

        toastr()->success(__('theme::categories.deleted_success'));

        return redirect('admin/categories');
    }
}
