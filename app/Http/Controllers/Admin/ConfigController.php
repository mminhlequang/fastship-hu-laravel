<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Traits\Authorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ConfigController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keywords = $request->get('search') ?? '';
        $perPage = config('settings.perpage');

        $locale = app()->getLocale();


        if (\Auth::user()->isAdminCompany())
            $users = \DB::table('users')->pluck('name', 'id');
        else
            $users = \DB::table('users')->where('id', \Auth::id())->pluck('name', 'id');
        $users->prepend('--Chọn công ty---', '');

        $data = Config::when($keywords != '', function ($query) use ($keywords) {
            $query->whereHas('user', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            });
        });

        if (\Auth::user()->isAdminCompany())
            $data = $data->orderByDesc('updated_at')->paginate($perPage);
        else
            $data = $data->where('user_id', \Auth::id())->orderByDesc('updated_at')->paginate($perPage);

        return view('admin.configs.index', compact('data', 'locale', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->isAdminCompany())
            $promotions = \DB::table('promotions')->where('active', 1)->pluck('name', 'id');
        else
            $promotions = \DB::table('promotions')->where('active', 1)->where('creator_id', \Auth::id())->pluck('name', 'id');
        $promotions->prepend('--Chọn chương trình---', '');

        if (\Auth::user()->isAdminCompany())
            $users = \DB::table('users')->pluck('name', 'id');
        else
            $users = \DB::table('users')->where('id', \Auth::id())->pluck('name', 'id');
        $users->prepend('--Chọn công ty---', '');


        return view('admin.configs.create', compact('users', 'promotions'));
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

        $this->validate(
            $request,
            [
                'promotion_id' => 'required',
                'user_id' => 'required',
                'promotion_id' => [
                    function ($attribute, $value, $fail) use ($requestData) {
                        $data = \DB::table('configs_company')->where([['promotion_id', $value], ['user_id', $requestData['user_id']]])->value('id');
                        if ($data) {
                            return $fail('Chương trình này đã đc cấu hình');
                        }
                    },
                ],
            ],
            [
                'user_id.required' => 'Vui lòng chọn công ty!',
                'user_id.unique' => 'Công ty đã được cấu hình thông tin',
            ]
        );
        foreach ($requestData['input'] as $key =>  &$itemI) {
            $itemI['name'] = Config::convertText($itemI['text']);
            $itemI["active"] = isset($itemI['active']) ? 1 : 0;
            if (array_key_exists('text', $itemI) && is_null($itemI['text'])) {
                unset($requestData['input'][$key]);
            }
        }

        $requestData['input'] = json_encode($requestData['input']);

        Config::create($requestData);

        alert()->success(__('settings.created_success'));

        return redirect('admin/configs');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Config::findOrFail($id);
        return view('admin.configs.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Config::findOrFail($id);

        if (\Auth::user()->isAdminCompany())
            $promotions = \DB::table('promotions')->where('active', 1)->pluck('name', 'id');
        else
            $promotions = \DB::table('promotions')->where('active', 1)->where('creator_id', \Auth::id())->pluck('name', 'id');
        $promotions->prepend('--Chọn chương trình---', '');


        if (\Auth::user()->isAdminCompany())
            $users = \DB::table('users')->pluck('name', 'id');
        else
            $users = \DB::table('users')->where('id', \Auth::id())->pluck('name', 'id');
        $users->prepend('--Chọn công ty---', '');

        $inputs = json_decode($data->input);


        return view('admin.configs.edit', compact('data', 'users', 'inputs', 'promotions'));
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
        $requestData = $request->all();
        $this->validate(
            $request,
            [
                'promotion_id' => 'required',
                'user_id' => 'required',
                'promotion_id' => [
                    function ($attribute, $value, $fail) use ($requestData, $id) {
                        $data = \DB::table('configs_company')->where('id', '<>', $id)->where([['promotion_id', $value], ['user_id', $requestData['user_id']]])->value('id');
                        if ($data) {
                            return $fail('Chương trình này đã đc cấu hình');
                        }
                    },
                ],
            ],
            [
                'user_id.required' => ' Vui lòng chọn công ty!',
                'user_id.unique' => ' Công ty bạn chọn đã có cấu hình!',

            ]
        );
        $data = Config::findOrFail($id);

        if (!empty($requestData['input'])) {
            foreach ($requestData['input'] as &$itemI) {
                $itemI['name'] = Config::convertText($itemI['text']);
                $itemI["active"] = isset($itemI['active']) ? 1 : 0;
            }
            $requestData['input'] = json_encode($requestData['input']);
        } else
            $requestData['input'] = null;

        $data->update($requestData);

        Alert::success(__('settings.updated_success'));
        return redirect('admin/configs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Config::destroy($id);
        alert()->success(__('settings.deleted_success'));
        return redirect('admin/configs');
    }
}
