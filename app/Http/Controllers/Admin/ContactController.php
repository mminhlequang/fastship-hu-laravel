<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class ContactController extends Controller
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

        $data = Contact::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%");
        });

        $data = $data->latest()->paginate($perPage);

        return view('admin.contacts.index', compact('keywords', 'locale', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:commits,name',
        ]);

        $requestData = $request->all();

        if(!$request->active) $requestData['active'] = 0;

        Contact::create($requestData);

        toastr()->success(__('Dữ liệu đã được tạo'));
        return redirect('admin/contacts');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $locale = app()->getLocale();
        $data = Contact::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.contacts.show', compact('data', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $locale = app()->getLocale();
        $data = Contact::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.contacts.edit', compact('data', 'backUrl', 'locale'));
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
        $request->validate([
            'name' => 'required|max:255|unique:commits,name,'.$id,
        ]);

        $data = Contact::findOrFail($id);

        $requestData = $request->all();

        if(!$request->active) $requestData['active'] = 0;

        $data->update($requestData);

        toastr()->success(__('Dữ lệu cập nhật thành công'));

        return redirect('admin/contacts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contact::destroy($id);
        toastr()->success(__('Dữ liệu đã xóa'));
        return redirect('admin/contacts');
    }
}
