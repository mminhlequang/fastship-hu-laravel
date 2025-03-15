<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Events\MailContactEvent;
use Illuminate\Routing\Controller;

class AjaxPostFrontEntController extends Controller
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

    public function postNewsletter(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:contacts,email'
        ],
            [
                'email.required' => 'Email không được để trống !',
                'email.unique' => 'Email đã được đăng ký !',
                'email.email' => 'Email không hợp lệ !'
            ]
        );
        if ($validator->passes()){
            $newsletter = new Contact();
            $newsletter->email = $request->email;
            $newsletter->save();
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()->all()]);
    }
    public function postContact(Request $request){
        $validator = \Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        if ($validator->passes()){
            $contact = new Contact();
            $contact->fullname = $request->fullname;
            $contact->email = $request->email;
            $contact->address = !empty($request->address) ? $request->address : '';
            $contact->phone = !empty($request->phone) ? $request->phone : '';
            $contact->message = $request->message;
            $contact->save();
            //Send mail
            event(new MailContactEvent($contact));
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()->all()]);
    }
    //Review
    public function review(Request $request){
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'review' => 'required',
        ],[
            'name.required' => 'Vui lòng nhập họ tên!',
            'review.required' => 'Vui lòng nhập đánh giá!'
        ]);
        if ($validator->passes()){
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()]);
    }


}
