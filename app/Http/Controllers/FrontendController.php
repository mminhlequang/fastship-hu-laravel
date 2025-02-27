<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Old;
use App\Models\Province;
use Illuminate\Http\Request;
use Validator;

class FrontendController extends Controller
{


    public function getPage($slug, Request $request)
    {
        switch ($slug) {
            default:
                return view("frontends.404");
        }

    }

}
