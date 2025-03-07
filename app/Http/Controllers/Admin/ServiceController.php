<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class ServiceController extends Controller
{
    use Authorizable;

    public function index(Request $request)
    {
        $keywords = $request->input('keywords', '');
        $perPage = 20;  // Adjust this per your needs

        // Fetch paginated data based on keywords and order by 'arrange'
        $data = Service::when($keywords != '', function ($query) use ($keywords) {
            $query->where('name', 'like', "%$keywords%");
        })
            ->whereNull('deleted_at')
            ->orderBy('arrange')
            ->paginate($perPage);  // Use paginate to fetch data

        // Convert the paginated data to a hierarchical structure
        $services = $this->getServicesTree($data->items());

        // Return the paginated result with the hierarchical data
        return view('admin.services.index', compact('services', 'data'));
    }

    public function getServicesTree($services, $parent_id = null, $level = 0)
    {
        $result = [];

        foreach ($services as $service) {
            if ($service->parent_id == $parent_id) {
                $service->level = $level;
                $result[] = $service;

                // Recursively get children services
                $result = array_merge($result, $this->getServicesTree($services, $service->id, $level + 1));
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
        $arrange = Service::max('arrange');
        $arrange = empty($arrange) ? 1 : $arrange + 1;

        return view('admin.services.create', compact('arrange'));
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

        Service::create($requestData);

        toastr()->success(__('settings.created_success'));

        return redirect('admin/services');
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
        $data = Service::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.services.show', compact('data', 'backUrl', 'locale'));
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
        $data = Service::findOrFail($id);

        $backUrl = $request->get('back_url');
        return view('admin.services.edit', compact('data', 'backUrl', 'locale'));
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
        $data = Service::findOrFail($id);

        $requestData = $request->all();


        $data->update($requestData);

        toastr()->success(__('settings.updated_success'));

        return redirect('admin/services');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service::destroy($id);

        toastr()->success(__('settings.deleted_success'));

        return redirect('admin/services');
    }
}
