<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ModuleInfo;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Modules\Booking\Entities\BookingProperty;
use Session;

class ModulesController extends Controller
{
	use Authorizable;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		$modules = \Module::all();
		return view('settings.modules.index', compact('modules'));
	}
	public function active($module,Request $request){
		$module = \Module::find($module);
		if(!$module) abort(404);
		if($module->active){
			$module->disable();
		}else{
			$module->enable();
		}
		if($request->wantsJson()){
			return response()->json($module);
		}
		return back();
	}
}
