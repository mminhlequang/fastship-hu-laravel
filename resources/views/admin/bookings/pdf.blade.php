<html>
<head>
    <meta http-equiv="content-type" content="text-html; charset=utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>In Bill {{ $booking->code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>

        .text-right {
            text-align: right;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }
        * {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            font-size: 18px;
        }

        .center{
            text-align: center;
        }
        .size15 {
            font-size: 25px !important;
        }

        .size13 {
            font-size: 25px !important;
        }

    </style>
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <div class="center mb-2">
        <h1 style="text-transform: uppercase" class="size15">Hóa đơn thanh toán</h1>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="{{ url('images/logoFB.png') }}"
                 alt="logo" height="80" width="80">
            <h1 style="margin-bottom: 5px;" class="size13"><small>
                    Tên khách hàng:</small>
                <b>&nbsp;{{ optional($booking->customer)->name ?? ""}}</b></h1>
            <h1 style="margin-bottom: 5px;" class="size13"><small>
                    Số điện thoại khách hàng:</small>
                <b>&nbsp;{{ optional($booking->customer)->phone ?? ""}}</b></h1>
            <p style="margin-bottom: 5px;">Mã đơn: <strong>{{ $booking->code }}</strong></p>
            <p style="margin-bottom: 5px;">Địa chỉ giao hàng: <strong>{{ optional($booking->address)->address }}</strong></p>
            <p style="margin-bottom: 5px;">Ngày đặt:
                <strong>{{ Carbon\Carbon::parse($booking->creadted_at)->format('H:i d/m/Y') }}</strong></p>
        </div>

    </div>
    <div class="col-md-12 text-center font-weight-bold h4 my-2">THÔNG TIN CHI TIẾT ĐƠN HÀNG</div>
    <div style="display: flex; justify-content: center; width: 100%;">
        <table class="table table-bordered border" style="width: 900px;" >
            <thead>
            <tr>
                <th class="text-center" style="width: 0.1%">STT</th>
                <th class="text-left" style="width: 0.5%">Sản phẩm</th>
                <th class="text-center" style="width: 0.1%">Số lượng</th>
                <th class="text-center" style="width: 0.2%">Đơn giá</th>
                <th class="text-center" style="width: 0.5%">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @php($i = 1)
            @foreach($products as $item)
                <tr>
                    <td class="text-center" style="width: 0.1%">{{ $i++ }}</td>
                    <td class="text-left" style="width: 0.5%">{{ $item->product->name }}</td>
                    <td class="text-center" style="width: 0.1%">{{ $item->quantity }}</td>
                    <td class="text-center" style="width: 0.2%">{{ number_format($item->product->price) }}</td>
                    <td class="text-center" style="width: 0.5%">
                        {{ number_format($item->product->price * $item->quantity) }} đ
                    </td>
                </tr>
            @endforeach
            <tr class="bg-gray">
                <td colspan="3" class="font-weight-bold">
                    TẠM TÍNH
                </td>
                <td></td>
                <td class="font-weight-bold text-center">{{ number_format($booking->total_price) }} đ</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6">
                    <div class="font-weight-bold">GHI CHÚ:</div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="order_info center">
        <div class="justify-between" style="margin-bottom: 5px;margin-top: 5px;">
            <strong class="fs-1rem">TỔNG CỘNG:&nbsp;</strong>
            <strong class="fs-1rem text-left">{{ number_format($booking->total_price) }} đ</strong>
        </div>
        <div class="justify-between" style="margin-bottom: 5px">
            <span class="fs-1rem font-weight-bold">{!! __('Hình thức giao hàng') !!}:&nbsp;</span>
            @if($booking->payment_type == 1)
                <strong class="fs-1rem font-weight-bold">Giao hàng tận nơi</strong>
            @else
                <strong class="fs-1rem font-weight-bold">Đến cửa hàng lấy</strong>
            @endif
        </div>
        <div class="justify-between" style="margin-bottom: 5px">
            <span class="fs-1rem font-weight-bold">{!! __('Hình thức thanh toán') !!}:&nbsp;</span>
            @if($booking->payment_method == 1)
                <strong class="fs-1rem font-weight-bold">Tiền mặt</strong>
            @else
                <strong class="fs-1rem font-weight-bold">Chuyển khoản</strong>
            @endif
        </div>
        <div class="right"><small class="fs-1rem">(Giá đã bao gồm VAT)</small></div>
    </div>
    <hr style="margin: 5px 0 5px 0">
    <div class="center" style="margin-top: 5px;">
        <p class="font-weight-bold mb-2">Quý khách vui lòng kiểm tra lại hàng hóa trước
            khi nhận hàng</p>
        <p class="font-weight-bold">Cám ơn quý khách - Hẹn gặp lại !</p>

    </div>
    <hr style="margin-top:10px">
</div>
</body>
<script>
    setTimeout(function () {
        {
            window.print();
            window.close();
        }
    }, 1000);
</script>
</html>

