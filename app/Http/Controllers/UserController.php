<?php

namespace App\Http\Controllers;
use App\Contact;
use App\Model\Category;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Modules\Theme\Resources\views\frontend\layouts\home;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getLogin()
    {
        return view('account.login');//return ra trang login để đăng nhập
    }

    public function postLogin(Request $request)
    {
        $arr = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        // dd($arr)
        if ($request->remember == trans('remember.Remember Me')) {
            $remember = true;
        } else {
            $remember = false;
        }
        //kiểm tra trường remember có được chọn hay không
        
        if (Auth::guard('loyal_customer')->attempt($arr)) {

       dd('login oke');
        } else {

            dd('tài khoản và mật khẩu chưa chính xác');
            //...code tùy chọn
            //đăng nhập thất bại hiển thị đăng nhập thất bại
        }
        
    }
    public function getRegister(){
        return view('account.register');//return ra trang login để đăng ký
    }
    public function postRegister(){
        $this->validate(request(), [
            'username' => 'required',
            'email' => 'required|email|unique:customers',
            'password' => 'required',
            'phone' => 'required|max:10',
            'address' => 'required',
            'name' => 'required',
    

        ]);


    //    $data = $this->all();

//
$customer = Customers::create(request(['name', 'email', 'password','phone','address','birthday','username']));;

        //    dd($customer);

        return back()->with('success','Bạn đã đăng kí thành công xin mời bạn đăng nhập');

    }

    public function getlogout(){
        Auth::guard('loyal_customer')->logout();
        return back()->with('You have been logged out.', 'Good bye!');
    }
    public function formcontact(){
        return view('account.sentcontact');
    }
    public function postcontact(Request $request){
        $data = $request->all();
        Contact::create($data);
        // dd('ok');
    }
}
