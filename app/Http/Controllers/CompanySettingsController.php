<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\CompanySetting;
use App\Traits\Authorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class CompanySettingsController extends Controller
{
	use Authorizable;
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function getProfile(){
        $user = \Auth::user();

        $company = CompanySetting::whereIn('key', array_column(Config('company_settings.company_key'), 'key'))->pluck('value','key');

        return view('company-settings.profile',compact('user','company'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postProfile(Request $request){

        $arr_key = array_column(Config('company_settings.company_key'), 'key');
        $arr_val = array_column(array_column(Config('company_settings.company_key'), 'data'),'validate');
        $this->validate($request, array_combine($arr_key,$arr_val));
        $requestData = $request->only($arr_key);
        foreach (Config('company_settings.company_key') as $key => $default){
            $company = CompanySetting::where('key',$default['key'])->first();

            if($default['type'] === 'file') {
	            if ( $request->hasFile( $default['key'] ) ) {
		            if (!empty($company) && $company->value) {
			            \Storage::delete( $company->value );
		            }
		            $requestData[$default['key']] = CompanySetting::saveLogo( $request->file( 'logo' ) );
	            } elseif (!empty($company) && $company->value && \Storage::exists($company->value)) {
		            $requestData[$default['key']] = $company->value;
	            }
            }
            $value = $requestData[$default['key']] ?? '';
            if($company) $company->update(['value' => $value ]);
            else CompanySetting::create(['key' => $default['key'], 'value' => $value]);
        }
        Session::flash('flash_message', __('companies.updated_success'));

        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
    	//abort_if(!auth()->user()->company_id, 404);

        $setting = CompanySetting::whereIn('key', array_keys(CompanySetting::$key))->pluck('value', 'key');
        return view('company-settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {

        $this->validate($request, CompanySetting::$key_validate);
        $requestData = $request->only(array_keys(CompanySetting::$key));

        foreach (CompanySetting::$key as $key => $default){
	        $setting = CompanySetting::where('key', $key)->first();
	        $value = $requestData[$key] ?? '';
	        if(isset(CompanySetting::$key_type[$key]) && CompanySetting::$key_type[$key] === 'checkbox' && !isset($requestData[$key]))
		        $value = 0;
	        if($setting) $setting->update(['value' => $value ]);
	        else CompanySetting::create(['key' => $key, 'value' => $value]);
        }

        Session::flash('flash_message', __('companies.settings_updated'));

        return back();
    }
}
