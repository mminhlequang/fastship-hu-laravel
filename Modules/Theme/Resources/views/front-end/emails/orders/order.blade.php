<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        <div class="content-mail" style="padding: 15px;">
            <div style="width: 600px; margin: 0 auto;">
                <p style="font-size: 12px;">Tổng giá trị đơn hàng là {{ number_format($booking->total_price) }} ₫</p>
                <div style="background-color: #fff;">
                    <div style="text-align: center; border-bottom: 3px solid #1ba100; padding: 5px;">
                        <img src="{{ asset(Storage::url($settings['company_logo'])) }}" alt="{{ $settings['company_website'] }}" width="80px"/>
                    </div>
                    <div style="padding: 15px;">
                        <p style="font-size: 15px;">
                            {{ __('message.emails.header1') }}<br/>
                            Cám ơn quý khách {{ optional($booking->customer)->name }} đã sử dụng dịch vụ {{ $settings['company_website'] }}<br/>
                            {{ $settings['company_website'] }} rất vui thông báo đơn hàng #{{ $booking->code }} của quý khách đã được tiếp nhận và đang trong quá trình xử lý. {{ $settings['company_website'] }} sẽ thông báo đến quý khách ngay khi hàng chuẩn bị được giao.
                        </p>
                        <h4 style="color: #1ba100; font-weight: bold; margin-top: 20px !important; font-size: 16px; border-bottom: 1px solid #ccc; margin-bottom: 15px; padding-bottom: 5px;">
                            THÔNG TIN ĐƠN HÀNG #{{ $booking->code }} <span style="font-size: 14px; color: #999; font-weight: 500;">(Ngày {{ \Carbon\Carbon::parse($booking->created_at)->format(config('settings.format.datetime')) }})</span>
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
                                <tr>
                                    <th colspan="3" style="text-align: left">Tổng giá trị đơn hàng</th>
                                    <th style="text-align: right">{{ number_format($total) }}&nbsp;VNĐ</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding: 15px;">
                        <p>&nbsp;</p>
                        <p style="font-size: 14px; margin-top: 10px;">
                            Bạn cần được hỗ trợ ngay? Chỉ cần email {{ $settings['company_email_support'] }}, hoặc gọi số điện thoại {{ $settings['company_hotline'] }} (8-21h cả T7,CN). Đội ngũ {{ $settings['company_website'] }} luôn sẵn sàng hỗ trợ bạn bất kì lúc nào.
                        </p>
                        <p style="font-size: 14px;"><b>Một lần nữa {{ $settings['company_website'] }} cảm ơn quý khách.</b></p>
                        <p style="text-align: right; color: #1ba100; font-size: 18px; margin-bottom: 0;">{{ $settings['company_website'] }}</p>
                    </div>
                </div>
                <p style="font-size: 12px; color: #4b8da5; margin-top: 10px; margin-bottom: 0;">
                Quý khách nhận được email này vì đã mua hàng tại {{ $settings['company_website'] }}.<br/>
                Để chắc chắn luôn nhận được email thông báo, xác nhận mua hàng từ {{ $settings['company_website'] }}, quý khách vui lòng thêm địa chỉ {{ $settings['company_email_support'] }} vào số địa chỉ (Address Book, Contacts) của hộp email.<br/>
                Địa chỉ {{ $settings['company_website'] }}: {{ $settings['company_address'] }}
                </p>
            </div>
        </div>
    </body>
</html>