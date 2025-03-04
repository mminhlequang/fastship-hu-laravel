<?php

namespace App\Http\Controllers\Admin;

use App\Models\Step;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class StepController extends Controller
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

        $data = Step::when($keywords != '', function ($query) use ($keywords) {
            $query->where('name', 'like', "%$keywords%");
        });

        $data = $data->whereNull('deleted_at')->orderBy('arrange')->paginate($perPage);

        return view('admin.steps.index', compact('keywords', 'locale', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrange = Step::max('arrange');
        $arrange = empty($arrange) ? 1 : $arrange + 1;

        return view('admin.steps.create', compact('arrange'));
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
            'name' => 'required|max:255|unique:steps,name',
        ]);

        $requestData = $request->all();

        Step::create($requestData);

        toastr()->success(__('settings.created_success'));

        return redirect('admin/steps');
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
        $data = Step::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.steps.show', compact('data', 'backUrl', 'locale'));
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
        $data = Step::findOrFail($id);

        $backUrl = $request->get('back_url');
        return view('admin.steps.edit', compact('data', 'backUrl', 'locale'));
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
            'name' => 'required|max:255|unique:steps,name,' . $id,
        ]);

        $data = Step::findOrFail($id);

        $requestData = $request->all();


        $data->update($requestData);

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/steps');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Step::destroy($id);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/steps');
    }
}
