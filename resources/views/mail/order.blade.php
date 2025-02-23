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
<p>
    {{ trans('emails.header.p1') }}<br/>
    {{ trans('emails.header.p2').strtoupper( $company['name'] ) }}<br/>
    {{ trans('emails.header.p3') }}<br/>
    {{ trans('emails.header.p4') }}
</p>
<div>
    <h4 style="color: rgb(77, 146, 0); font-weight: bold; margin-top: 50px !important; font-size: 18px; padding-left: 5px;">{{ trans('emails.customer') }}</h4>
    <div style="border:none;">
        <ul style="padding-left: 0px;margin-top: 0px;margin-bottom: 0px;">
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.customer_name') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $customer->name }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.customer_address') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $customer->address }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.customer_phone') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $customer->phone }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.customer_email') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $customer->email }}</span>
            </li>
        </ul>
    </div>
    <h4 style="color: rgb(77, 146, 0); font-weight: bold; margin-top: 30px !important; font-size: 18px; padding-left: 5px;">{{ trans('emails.service.title') }}</h4>
    <div style="border:none;">
        <ul style="padding-left: 0px;margin-top: 0px;margin-bottom: 0px;">
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.tour.code') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $tour->prefix_code.$tour->code }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.tour.name') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $tour->name }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.booking.total_price') }}</span>
                <span style="font-size: 14px; color: #ee0000; display: inline-block;">{{ number_format($booking->total_price) }} {{ trans('emails.service.booking.unit') }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.booking.note') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ $booking->note }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.booking.create_date') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ Carbon\Carbon::parse($booking->created_at)->format(config('settings.format.date')) }}</span>
            </li>
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.booking.payment_method') }}</span>
                <span style="font-size: 14px; color: #5e6d77; display: inline-block;">{{ optional($booking->payment)->name }}</span>
            </li>
            @php($tDeposit = \Modules\Tour\Entities\TourDeposit::where('tour_id', $tour->id)->orderBy('value', 'ASC')->first())
            <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                <span style="font-weight:bold;width: 38%;display: inline-block;">{{ trans('emails.service.booking.status') }}</span>
                <span style="font-size: 14px; color: #ee0000; display: inline-block;">{{ optional($booking->status)->name }} {{ optional($booking->status)->id == 3 ? $tDeposit->value . '%' : '' }}</span>
            </li>
            @if(optional($booking->status)->id == 3 && (optional($booking->payment)->code == 'cash' || optional($booking->payment)->code == 'transfer'))
                <li style="padding:5px;margin:0px;list-style:none;border-bottom: 1px dashed #dae1e7;display:list-item">
                    <span style="font-weight:bold;width: 38%;display: inline-block;">Cần thanh toán</span>
                    <span style="font-size: 14px; color: #ee0000; display: inline-block;">{{ number_format(($booking->total_price*$tDeposit->value)/100) }} VNĐ</span>
                </li>
            @endif
        </ul>
    </div>
    <h4 style="color: rgb(77, 146, 0); font-weight: bold; margin-top: 50px !important; font-size: 18px; padding-left: 5px;">{{ trans('emails.service.payment.title') }}</h4>
    <div style="border: 1px solid #d7dce3; border-radius: 3px; padding-left:10px; padding-right:10px;">
        <p>{{ trans('emails.service.payment.p1', ['company_phone'=>$company['phone']]) }}</p>
        <p>{{ trans('emails.service.payment.p2', ['company_name'=>strtoupper($company['name']), 'company_name'=>strtoupper($company['name'])]) }}</p>
    </div>
    <p style="margin-top: 20px; font-weight:bold;"><span style="color: #ee0000; align-items: center;">*</span>{{ trans('emails.service.payment.note', ['company_name'=>$company['name']]) }}</p>
</div>
<h1 style="margin-top: 30px;"><span style="border-bottom: 1px dashed #000; padding-bottom: 5px; color: #eb7629; font-size: 24px;">{{strtoupper( $company['name'] )}}</span></h1>
<p>
    <b>{{ trans('emails.contact.address') }}</b> {{ $company['address'] }}<br/>
    <b>{{ trans('emails.contact.phone') }}</b> {{ $company['phone'] }}<br/>
    <b>{{ trans('emails.contact.email') }}</b> {{ $company['email'] }}<br/>
    <b>{{ trans('emails.contact.website') }}</b> {{ $company['website'] }}<br/>
</p>
</body>
</html>