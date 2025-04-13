<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use App\Models\Order;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class AjaxPostController extends Controller
{

    /**
     * Gọi ajax: sẽ gọi đến hàm = tên $action
     * @param Request $action
     * @param Request $request
     * @return mixed
     */
    public function index($action, Request $request)
    {
        return $this->{$action}($request);
    }


}