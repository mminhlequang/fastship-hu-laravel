<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function changeLocale(Request $request)
    {
        \Session::put('language', $request->get('locale_client'));

        return redirect()->back();
    }
}
