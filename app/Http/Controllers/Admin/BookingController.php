<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Approve;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\AddressDelivery;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $locale = app()->getLocale();
        $keyword = $request->get('search');

        $perPage = config('settings.perpage');

        $status_id = $request->query('payment_status') ?? '';
        $total = \DB::table('orders')->sum('total_price');
        $from = $request->query('from');
        $to = $request->query('to');

        $bookings = Order::when($keyword, function ($query) use ($keyword) {
            $query->where('code', 'like', "%$keyword%")
                ->orWhereHas('customer', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%$keyword%");
                });
        })->when($status_id != '', function ($query) use ($status_id) {
            $query->where('payment_status', $status_id);
        })->when($from != '' && $to != '', function ($query) use ($from, $to) {
            $query->whereBetween('updated_at', [$from, $to]);
        });
        $bookings = $bookings->latest()->paginate($perPage);

        return view('admin.bookings.index', compact('bookings', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $locale = app()->getLocale();
        $booking = new Order();

        $allProducts = \DB::table('products')->where('active', 1)->pluck('name_' . $locale, 'id');
        $allProducts->prepend(__('--Chọn sản phẩm--'), '')->all();

        $approves = Approve::pluck('name_'.$locale, 'id');
        $customers = Customer::all()->pluck('name', 'id');
        $customers->prepend(__('message.please_select'), '')->all();

        return view('admin.bookings.create', compact('booking', 'approves', 'allProducts', 'customers'));
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
            $booking = Order::create([
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $locale = app()->getLocale();
        $booking = Order::findOrFail($id);

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.bookings.show', compact('booking', 'backUrl', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locale = app()->getLocale();
        $booking = Order::findOrFail($id);
        $approves = Approve::pluck('name_'.$locale, 'id');
        $allProducts = Product::pluck('name_' . $locale, 'id');
        $allProducts = $allProducts->prepend("-- " . trans('theme::products.product') . " --", '');
        $productItems = OrderItem::where('booking_id', $booking->id)->get();

        $products = [];
        foreach ($productItems as $item) {
            $products[] = (object)[
                'product' => Product::with('category')->where('id', $item->product_id)->first(),
                'quantity' => $item->quantity
            ];
        }
        $customers = Customer::all()->pluck('name', 'id');
        $customers->prepend(__('message.please_select'), '')->all();

        $address = AddressDelivery::where('id', $booking->address_id)->pluck('address', 'id');

        return view('admin.bookings.edit', compact('booking', 'products', 'locale', 'approves', 'allProducts', 'customers', 'address'));
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

        $booking = Order::findOrFail($id);

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
            // $booking = Order::update();
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::destroy($id);

        Alert::success(__('theme::business.deleted_success'));

        return redirect('admin/bookings');
    }
}
