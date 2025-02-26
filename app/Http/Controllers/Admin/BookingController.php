<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookingItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Approve;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Customer;
use App\Models\AddressDelivery;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Check and save customer info
     * @param $custom_id
     * @param $customerInfo
     *
     * @return Customer
     */
    private function saveCustomer($custom_id, $customerInfo)
    {
        //If exits customer_id: Đã có id khách hàng
        if ($custom_id && $customer = Customer::find($custom_id)) {
            $customer->update($customerInfo);
        } else {
            //Check if customer is empty (kiểm tra xem dữ liệu KH đã có hay ko có nhập)
            $isCustomerEmpty = true;
            foreach ($customerInfo as $key => $value) {
                if ((is_array($value) && !empty($value)) || (is_string($value) && trim($value) !== '')) {
                    $isCustomerEmpty = false;
                    break;
                }
            }
            //If customer is not empty: save data
            if (!$isCustomerEmpty) {
                //Find customer by phone or email: kiểm tra xem có KH trùng với số phone hoặc email không
                if (!empty(trim($customerInfo['phone'])) || !empty(trim($customerInfo['email']))) {
                    $customer = new Customer();
                    if (!empty(trim($customerInfo['phone']))) $customer = $customer->where('phone', trim($customerInfo['phone']));
                    if (!empty($customerInfo['email'])) {
                        if (!empty(trim($customerInfo['email']))) $customer = $customer->orWhere('email', trim($customerInfo['email']));
                    }

                    if ($customer = $customer->first()) {
                        //Nếu có KH trung email hoặc phone thì cập nhật lại thông tin KH - xóa đi các trường rỗng trc khi cập nhật
                        //remove empty data and updated customer
                        $customer->update(array_where($customerInfo, function ($value, $key) {
                            return (is_array($value) && !empty($value)) || (is_string($value) && trim($value) !== '');
                        }));
                    } else {
                        $customer = Customer::create($customerInfo);
                    }
                } else {
                    $customer = Customer::create($customerInfo);
                }
            }
        }
        return $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $keyword = $request->get('search');

        $perPage = config('settings.perpage');
    
        $status = Approve::pluck('name', 'id');
        $status = $status->prepend("-- " . trans('theme::approves.approves') . " --", '');

        $status_id  = $request->query('approve_id');
        $total = \DB::table('bookings')->sum('total_price');
        $from  = $request->query('from');
        $to  = $request->query('to');
        $bookings = Booking::when($keyword, function ($query, $keyword) {
            $query->where('name', 'like', "%$keyword%")
                ->orWhere('code', 'like', "%$keyword%");
        })->when($status_id, function ($query) use ($status_id) {
            $query->where('approve_id', $status_id);
        })->when($from != '' && $to != '', function ($query) use($from,$to) {
            $query->whereBetween('updated_at', [$from, $to]);
        });
        $bookings = $bookings->orderByDesc('created_at')->paginate($perPage);
        return view('admin.bookings.index', compact('bookings', 'status','total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $locale = app()->getLocale();
        $booking = new Booking();

        $allProducts = \DB::table('products')->where('active', 1)->pluck('name_'.$locale, 'id');
        $allProducts->prepend(__('--Chọn sản phẩm--'), '')->all();

        $approves = Approve::pluck('name', 'id');
        $customers =  Customer::all()->pluck('name', 'id');
        $customers->prepend(__('message.please_select'), '')->all();

        return view('admin.bookings.create', compact('booking', 'approves', 'allProducts', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $requestData = $request->all();
        \DB::transaction(function () use ($requestData, $request) {
            //Kiểm tra trạng thái, mặc định trạng thái đầu tiên
            if (!isset($requestData['approve_id'])) {
                $requestData['approve_id'] = Approve::orderBy('number')->first()->value('id');
            }

            //Save booking
            $voucher_value = 0;
            $amount = array(
                'total_price' => $requestData['amount'],
                'voucher_value' => $voucher_value
            );
            $booking = Booking::create([
                'total_price' => $requestData['amount'],
                'note' => $requestData['note'] ?? '',
                'customer_id' => $requestData['customer_id'],
                'amount' => json_encode($amount),
                'discount_id' => $requestData['voucher_id'] ?? '',
                'payment_type' => $requestData['payment_type'],
                'approve_id' => $requestData['approve_id']
            ]);

            foreach ($requestData['product'] as $key => $val) {
                $booking->bookingItem()->attach($key, ['quantity' => $val['quantity']]);
            }
        });


        Alert::success(__('theme::bookings.created'));

        return redirect('admin/bookings');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $locale = app()->getLocale();
        $booking = Booking::findOrFail($id);

        $productItems = BookingItem::where('booking_id', $booking->id)->get();
        $products = [];
        foreach ($productItems as $item) {
            $products[] = (object)[
                'product' => Product::with('category')->where('id', $item->product_id)->first(),
                'quantity' => $item->quantity
            ];
        }
        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.bookings.show', compact('booking', 'products', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locale = app()->getLocale();
        $booking = Booking::findOrFail($id);
        $approves = Approve::pluck('name', 'id');
        $allProducts = Product::pluck('name_'.$locale, 'id');
        $allProducts = $allProducts->prepend("-- " . trans('theme::products.product') . " --", '');
        $productItems = BookingItem::where('booking_id', $booking->id)->get();

        $products = [];
        foreach ($productItems as $item) {
            $products[] = (object)[
                'product' => Product::with('category')->where('id', $item->product_id)->first(),
                'quantity' => $item->quantity
            ];
        }
        $customers =  Customer::all()->pluck('name', 'id');
        $customers->prepend(__('message.please_select'), '')->all();

        $address = AddressDelivery::where('id', $booking->address_id)->pluck('address', 'id');

        return view('admin.bookings.edit', compact('booking', 'products', 'locale', 'approves', 'allProducts', 'customers', 'address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $booking = Booking::findOrFail($id);

        $requestData = $request->all();

        \DB::transaction(function () use ($request, $requestData, $booking) {
            //Kiểm tra trạng thái, mặc định trạng thái đầu tiên
            if (!isset($requestData['approve_id'])) {
                $requestData['approve_id'] = Approve::orderBy('number')->first()->value('id');
            }

            $voucher_value = 0;

            $amount = array(
                'total_price' => $requestData['amount'],
                'voucher_value' => $voucher_value
            );
            // $booking = Booking::update();
            $booking->update([
                'total_price' => $requestData['amount'],
                'note' => $requestData['note'] ?? '',
                'customer_id' => $requestData['customer_id'],
                'amount' => json_encode($amount),
                'discount_id' => $requestData['voucher_id'] ?? '',
                'payment_type' => $requestData['payment_type'],
                'approve_id' => $requestData['approve_id']
            ]);

        });


        Alert::success(__('theme::bookings.updated'));

        return redirect('admin/bookings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Booking::destroy($id);

        Alert::success(__('theme::business.deleted_success'));

        return redirect('admin/bookings');
    }
}
