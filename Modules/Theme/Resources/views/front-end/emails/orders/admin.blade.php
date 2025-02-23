<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }
</style>
<div class="content-mail" style="padding: 20px;">
    <div style="width: 600px; background-color: #fff; margin: 0 auto; padding: 15px;">
        <p style="font-size: 15px;">
            Kính chào Admin,<br/>
            Có khách hàng {{ optional($booking->customer)->name }} đã sử dụng dịch vụ của quý {{ $settings['company_website'] }}<br/>
        </p>
        <h4 style="color: #1ba100; font-weight: bold; margin-top: 20px !important; font-size: 16px; border-bottom: 1px solid #ccc; margin-bottom: 15px; padding-bottom: 5px;">
            THÔNG TIN ĐƠN HÀNG #{{ $booking->code }} <span style="font-size: 14px; color: #999;">Ngày {{ \Carbon\Carbon::parse($booking->created_at)->format(config('settings.format.datetime')) }}</span>
        </h4>
        <div style="border:none;">
            <ul style="padding-left: 0px;margin-top: 0px;margin-bottom: 0px;">
                <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                    <span style="font-weight:bold;width: 38%;display: inline-block;">Họ và tên</span>
                    <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ optional($booking->customer)->name }}</span>
                </li>
                <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                    <span style="font-weight:bold;width: 38%;display: inline-block;">Địa chỉ giao hàng</span>
                    <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ optional($booking->customer)->address }}</span>
                </li>
                <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                    <span style="font-weight:bold;width: 38%;display: inline-block;">Số điện thoại</span>
                    <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ optional($booking->customer)->phone }}</span>
                </li>
                <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                    <span style="font-weight:bold;width: 38%;display: inline-block;">Email</span>
                    <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ optional($booking->customer)->email }}</span>
                </li>
            </ul>
        </div>
        <h4 style="color: #1ba100; font-weight: bold; margin-top: 20px !important; font-size: 16px; border-bottom: 1px solid #ccc; margin-bottom: 15px; padding-bottom: 5px;">
            CHI TIẾT ĐƠN HÀNG
        </h4>
        <table style="font-family:Arial, sans-serif; width:100%; max-width:600px; border-collapse:collapse" align="left" border="1" bordercolor="#211551" cellpadding="10" cellspacing="0">
            <thead>
            <tr>
                <th bgcolor="#1ba100" style="color:#fff; text-align: left;">Sản phẩm</th>
                <th bgcolor="#1ba100" style="color:#fff; text-align: center;">Giá</th>
                <th  bgcolor="#1ba100"  style="text-align:center;  color:#fff; vertical-align:middle">Số lượng</th>
                <th bgcolor="#1ba100" style="text-align:right; color:#fff; vertical-align:middle">Tổng tạm</th>
            </tr>
            </thead>
            <tbody>
                @php($total = 0)
                @foreach($order as $item)
                    @php($total += $item['item']['price'] * $item['quantity'])
                    <tr>
                        <td style="text-align:left; vertical-align:middle">{{ $item['item']['name'] }}</td>
                        <td style="text-align:center; vertical-align:middle">{{ number_format($item['item']['price']) }}&nbsp;đ</td>
                        <td style="text-align:center; vertical-align:middle">{{ $item['quantity'] }}</td>
                        <td style="text-align:right; vertical-align:middle">{{ number_format($item['quantity'] * $item['item']['price']) }}&nbsp;đ</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: left">Tổng giá trị đơn hàng</th>
                    <th style="text-align: right">{{ number_format($total) }}&nbsp;VNĐ</th>
                </tr>
            </tfoot>
        </table>
        <p>&nbsp;</p>
        <p style="text-align: right; color: #1ba100; font-size: 16px; margin-bottom: 0;">{{ $settings['company_website'] }}</p>
    </div>
</div>
</body>
</html>