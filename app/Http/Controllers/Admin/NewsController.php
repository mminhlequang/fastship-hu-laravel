<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = Category::all()->pluck('name', 'id');
        $category->prepend(__('--Chọn danh mục sản phẩm--'), '')->all();
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');
        $locale = app()->getLocale();
 
        $news = new News();
        $active = News::getListActive();

        if (!empty($keyword)) {
            $news = $news->where('title', 'LIKE', "%$keyword%");
        }
        if(!empty($request->get('category_id'))){
            $news = $news->where('category_id', $request->get('category_id'));
        }

        $news = $news->with('category');

        $news = $news->sortable(['updated_at' => 'desc'])->paginate($perPage);
        return view('admin.news.index', compact('news', 'active','category', 'locale'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locale = app()->getLocale();
        $news_cate = Category::all()->pluck('name', 'id');
        $news_cate->prepend(__('--Chọn danh mục sản phẩm--'), '')->all();
        return view('admin.news.create', compact('news_cate', 'locale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image'))
                $requestData['image'] = News::uploadAndResize($request->file('image'));
            News::create($requestData);
        });

        Alert::success(__('theme::business.created_success'));


        return redirect('admin/news');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $news = News::findOrFail($id);
        $locale = app()->getLocale();

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.news.show', compact('news', 'backUrl','locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $locale = app()->getLocale();
        $news = News::findOrFail($id);
        $news_cate = Category::all()->pluck('name', 'id');
        $news_cate->prepend(__('--Chọn danh mục sản phẩm--'), '')->all();
                $backUrl = $request->get('back_url');
        return view('admin.news.edit', compact('news', 'news_cate', 'backUrl', 'locale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locale = app()->getLocale();
        $news = News::findOrFail($id);
        $requestData = $request->all();

        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }  
        if(!isset($request->is_focus)){
			$requestData["is_focus"] = config("settings.inactive");
        } 


        \DB::transaction(function () use ($request, $requestData, $news) {
            if ($request->hasFile('image')) {
                \File::delete($news->image);
                $requestData['image'] = News::uploadAndResize($request->file('image'));
            }
            $news->update($requestData);
        });

        Alert::success(__('theme::business.updated_success'));


        return redirect('admin/news');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $new = News::findOrFail($id);
        if (!empty($new->image)) {
            \File::delete($new->image);
        }

        News::destroy($id);

        Alert::success(__('theme::business.deleted_success'));

        return redirect('admin/news');
    }

}
