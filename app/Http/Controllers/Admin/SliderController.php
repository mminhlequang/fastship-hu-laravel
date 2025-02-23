<?php

namespace App\Http\Controllers\Admin;

use App\Traits\Authorizable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
class SliderController extends Controller
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

        $sliders = Slider::sortable();

        if (!empty($keyword)){
            $sliders = Slider::where('name','LIKE',"%$keyword%");
        }

        $sliders = $sliders->paginate($perPage);
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrange = Slider::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;
        return view('admin.sliders.create', compact('arrange'));
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
            if (!empty($request->hasFile('image'))) {          
                $requestData['image'] = Slider::uploadAndResize($request->file('image'));        
            }
            Slider::create($requestData);

        });
        toastr()->success(__('theme::sliders.created_success'));

        return redirect('admin/sliders');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit', compact('slider'));
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
        $this->validate($request, [
            'image' => 'required'
        ]);
        $slider = Slider::findOrFail($id);

        $requestData = $request->all();
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }
        \DB::transaction(function () use ($request, $requestData, $slider){
            if($request->hasFile('image')) {
				\Storage::delete($slider->avatar);
                $requestData['image'] = Slider::uploadAndResize($request->file('image'));
            }
            $slider->update($requestData);
        });
        toastr()->success(__('theme::sliders.updated_success'));
        return redirect('admin/sliders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Slider::destroy($id);

        toastr()->success(__('theme::sliders.deleted_success'));
        return redirect('sliders');
    }
}
