<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;

class NotificationController extends Controller
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
        $locale = app()->getLocale();
        $userId = $request->get('user_id') ?? '';

        $users = \DB::table('customers')->whereNull('deleted_at')->pluck('name', 'id');
        $users->prepend(__('--Choose-user--'), '')->all();

        $data = Notification::with('user')
            ->when($keyword, function ($query) use($keyword) {
            $query->where('title', 'like', "%$keyword%")
                ->orWhere('description', "%$keyword%");
        })->when($userId != '', function ($query) use ($userId){
            $query->where('user_id', $userId);
        });

        $data = $data->where('is_all', 1)->latest()->paginate($perPage);

        return view('admin.notifications.index', compact('data', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = \DB::table('customers')->whereNull('deleted_at')->pluck('name', 'id');

        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $requestData = $request->all();
        \DB::transaction(function () use ($request, $requestData) {
            try {
                if ($request->hasFile('image')) {
                    $requestData['image'] = Notification::uploadAndResize($request->file('image'));
                }
                if(isset($requestData['user_id']) && !empty($requestData['user_id']))
                    $requestData['user_id'] = implode(",", $requestData['user_id']);

                //Create Notification User
                Notification::insertNotificationAll($requestData);
            }catch (\Exception $e){
                toastr()->error($e->getMessage());
            }
        });


        toastr()->success(__('notifications.created_success'));

        return redirect('admin/notifications');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Notification::findOrFail($id);
        return view('admin.notifications.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = \DB::table('customers')->whereNull('deleted_at')->pluck('name', 'id');

        $data = Notification::findOrFail($id);

        return view('admin.notifications.edit', compact('data', 'users'));
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
        $notifications = Notification::findOrFail($id);

        $requestData = $request->all();

        \DB::transaction(function () use ($request, $requestData, $notifications) {
            if ($request->hasFile('image')) {
                $requestData['image'] = Notification::uploadAndResize($request->file('avatar'));
            }
            if(isset($requestData['user_id']) && !empty($requestData['user_id']))
                $requestData['user_id'] = implode(",", $requestData['user_id']);
            $notifications->update($requestData);
        });

        toastr()->success(__('notifications.updated_success'));
        return redirect('admin/notifications');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        if (!empty($notification->image)) {
            \File::delete($notification->image);
        }
        Notification::destroy($id);

        toastr()->success(__('notifications.deleted_success'));

        return redirect('admin/notifications');
    }
}
