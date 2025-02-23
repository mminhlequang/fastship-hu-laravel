<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use Session;

class PermissionsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return void
	 */
	public function index(Request $request)
	{
		$keyword = $request->get('search');
		$perPage = 15;

		if (!empty($keyword)) {
			$permissions = Permission::where('name', 'LIKE', "%$keyword%")->orWhere('label', 'LIKE', "%$keyword%")
			                         ->paginate($perPage);
		} else {
			$permissions = Permission::paginate($perPage);
		}

		return view('admin.permissions.index', compact('permissions'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return void
	 */
	public function create()
	{
		return view('admin.permissions.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return void
	 */
	public function store(Request $request)
	{
		$this->validate($request, ['name' => 'required']);

		Permission::create($request->all());

		Session::flash('flash_message', 'Permission added!');

		return redirect('admin/permissions');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return void
	 */
	public function show($id)
	{
		$permission = Permission::findOrFail($id);

		return view('admin.permissions.show', compact('permission'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return void
	 */
	public function edit($id)
	{
		$permission = Permission::findOrFail($id);

		return view('admin.permissions.edit', compact('permission'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return void
	 */
	public function update($id, Request $request)
	{
		$this->validate($request, ['name' => 'required']);

		$permission = Permission::findOrFail($id);
		$permission->update($request->all());

		Session::flash('flash_message', 'Permission updated!');

		return redirect('admin/permissions');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 *
	 * @return void
	 */
	public function destroy($id)
	{
		Permission::destroy($id);

		Session::flash('flash_message', 'Permission deleted!');

		return redirect('admin/permissions');
	}
	/**
	 * Hiển thị danh sách tất cả các action của Controller có khai báo trong route
	 *
	 * @return void
	 */
	public function getInitPermissions()
	{
		$actionControllers = self::getActionControllers();
		$actionOK = Permission::whereIn('name',$actionControllers)->pluck('label','name');

		return view('admin.permissions.init', compact('actionControllers', 'actionOK'));
	}

	/**
	 * Lưu permissions khi khởi tạo
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postInitPermissions(Request $request){
		$requestData = $request->all();
		$actionControllers = self::getActionControllers();
		$actionOK = Permission::get();
		foreach ($actionOK as $p){
			if(isset($requestData["select"][$p->name])){
				if($requestData["label"][$p->name] != $p->label)
					$p->fill(['label' => $requestData["label"][$p->name]]);
				unset($requestData["select"][$p->name]);
			}else{
				$p->delete();
			}
		}
		if(count($requestData["select"])>0){
			foreach ($requestData["select"] as $name=>$ok){
				Permission::create([
					'name' => $name,
					'label' => $requestData["label"][$name]
				]);
			}
		}
		return back();
	}

	/**
	 * Lấy danh sách tất cả action của controller có khai báo trong route
	 * @return array
	 */
	private function getActionControllers(){
		$routes = \Route::getRoutes()->getRoutes();
		$actionControllers = [];
		foreach ($routes as $route)
		{
			$action = $route->getAction();
			if (array_key_exists('controller', $action))
			{
				$ar = explode("\\", $action['controller']);
				$item = end($ar);
				$actionControllers[$item] = $item;
			}
		}
		return array_values($actionControllers);
	}
}
