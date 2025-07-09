<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class TeamController extends Controller
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

        $players = \DB::table('customers')->where('type', 4)->pluck('name', 'id');
        $players->prepend(_('--Choose member--'),'');

        $data = Team::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%");
        });

        $data = $data->latest()->paginate($perPage);

        return view('admin.teams.index', compact('keywords', 'locale', 'data', 'players'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.teams.create');
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
            'name' => 'required|max:255|unique:driver_teams,name',
        ]);

        $requestData = $request->all();


        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image'))
                $requestData['logo_url'] = Team::uploadAndResize($request->file('image'));
            Team::create($requestData);
        });



        toastr()->success(__('settings.created_success'));

        return redirect('admin/teams');
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
        $data = Team::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.teams.show', compact('data', 'backUrl', 'locale'));
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
        $data = Team::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.teams.edit', compact('data', 'backUrl', 'locale'));
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
            'name' => 'required|max:255|unique:driver_teams,name,'.$id,
        ]);

        $data = Team::findOrFail($id);

        $requestData = $request->all();

        \DB::transaction(function () use ($request, $requestData, $data) {
            if ($request->hasFile('image'))
                $requestData['logo_url'] = Team::uploadAndResize($request->file('image'));
            $data->update($requestData);
        });


        toastr()->success(__('settings.updated_success'));

        return redirect('admin/teams');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Team::destroy($id);
        toastr()->success(__('settings.deleted_success'));
        return redirect('admin/teams');
    }
}
