<?php

namespace App\Http\Controllers\Admin;

use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Approve;

class ApproveController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = Config("settings.perpage");

        $approves = Approve::sortable(['number' => 'asc']);
        if (!empty($keyword)) {
            $approves = $approves->where('name', 'LIKE', "%$keyword%");
        }
        $approves = $approves->paginate($perPage);
        return view('admin.approves.index', compact('approves'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $number = Approve::max('number');
        $number = !empty($number) ? $number+1 : 1;
        return view('admin.approves.create', compact('number'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'number' => 'required|numeric|unique:approves',
            'color' => 'nullable',
        ]);
        $requestData = $request->all();

        Approve::create($requestData);
        toastr()->success(__('theme::approves.approve_added'));
        return redirect('admin/approves');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $approve = Approve::findOrFail($id);
        return view('admin.approves.show', compact('approve'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $approve = Approve::findOrFail($id);
        return view('admin.approves.edit', compact('approve'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
//        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'number' => 'required|numeric|max:20',
            'color' => 'nullable',
        ]);
        $requestData = $request->all();

        $approve = Approve::findOrFail($id);
        $approve->update($requestData);

        toastr()->success(__('theme::approves.approve_updated'));

        return redirect('admin/approves');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        Approve::destroy($id);

        toastr()->success(__('theme::approves.approve_deleted'));

        return redirect('admin/approves');
    }
}
