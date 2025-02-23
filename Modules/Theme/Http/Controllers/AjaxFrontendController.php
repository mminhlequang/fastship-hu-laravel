<?php

namespace Modules\Theme\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use App\Events\MailContactEvent;
use Illuminate\Routing\Controller;
use Modules\Contact\Entities\Contact;
use Modules\Product\Entities\CategoryProduct;
use Modules\Product\Entities\Product;
use Modules\Review\Entities\Review;
use Modules\Newsletter\Entities\Newsletter;

class AjaxFrontendController extends Controller
{
    public function postNewsletter(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email'
            ],
            [
                'email.required' => 'Email không được để trống !',
                'email.unique' => 'Email đã được đăng ký !',
                'email.email' => 'Email không hợp lệ !'
            ]
        );
        if ($validator->passes()){
            $newsletter = new Newsletter();
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
            Review::create($request->all());
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()]);
    }
    //Pagination Reviews
    public function ajaxPagination(Request $request)
    {
        $listReviews = Review::with('product')->where(['product_id' => $request->id, 'active' => config('settings.active')])->paginate(5);
        $settings = Setting::allConfigsKeyValue();
        return view('theme::front-end.products.reviewajax',compact('listReviews', 'settings'));
    }
    //Filter Product
    public function filterProduct(Request $request){
       
        $settings = Setting::allConfigsKeyValue();
        $menuProductCategories = CategoryProduct::all();
        $products = new Product();
        $products = $products->with('category');
        $idCategory = $request->get('category_id');
        if (!empty($idCategory)){
            $products = $products->where('category_id',$idCategory);
        }
        $order = $request->get('order');
        switch ($order) {
            case 'price':
                $products = $products->orderBy('price', 'ASC');
                break;
            case 'price-desc':
                $products = $products->orderBy('price', 'DESC');
                break;
            default:
                $products = $products->orderBy('updated_at','DESC');
        }
        $products = $products->paginate(config('settings.paginate.page12'));
        return view('theme::front-end.products.filterajax', compact('products', 'settings', 'menuProductCategories'));
    }

}
