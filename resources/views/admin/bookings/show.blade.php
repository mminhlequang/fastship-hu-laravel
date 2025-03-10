@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::bookings.booking') }}
@endsection
@section('contentheader_title')
    {{ __('theme::bookings.booking') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/bookings/'.Route::input('module')) }}">{{ __('theme::bookings.booking') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/bookings') }}" title="{{ __('message.lists') }}"
                   class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span
                            class="hidden-xs"> {{ __('message.lists') }}</span></a>
                @can('BookingController@update')
                    <a href="{{ url('/admin/bookings/' . $booking->id . '/edit') }}"
                       class="btn btn-default btn-sm mr-1"><i class="far fa-edit"></i> <span
                                class="hidden-xs"> {{ __('message.edit') }}</span></a>
                @endcan
                @can('BookingController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/bookings', $booking->id],
                        'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="far fa-trash-alt"></i> <span class="hidden-xs"> '. __('message.delete') .'</span>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-default btn-sm',
                            'title' => 'Xoá',
                            'onclick'=>'return confirm("'. __('message.confirm_delete') .'?")'
                    ))!!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th colspan="2" class="text-danger">
                        {{ __('theme::bookings.customer_info') }}
                    </th>
                </tr>
                <tr>
                    <th> {{ trans('theme::bookings.customer') }} </th>
                    <td> {{ optional($booking->customer)->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::customers.phone') }} </th>
                    <td> {{ optional($booking->customer)->phone }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::customers.email') }} </th>
                    <td> {{ optional($booking->customer)->email }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::customers.address') }} </th>
                    <td> {{ optional($booking->customer)->address }} </td>
                </tr>
                <tr>
                    <th colspan="2" class="text-danger">
                        {{ __('theme::bookings.booking_info') }}
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="table table-hover table-bordered" style="margin-bottom: 0">
                            <thead>
                            <tr>
                                <th class="col-md-4">{{ __('theme::products.product') }}</th>
                                <th class="col-md-2">{{ __('theme::products.price') }}</th>
                                <th class="col-md-2">Variations</th>
                                <th class="col-md-2">Toppings</th>
                                <th class="col-md-2 text-center">{{ __('theme::products.amount') }}</th>
                                <th class="col-md-2 text-center">{{ __('theme::products.total_price') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($booking->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product['name'] ?? '' }}</td>
                                    <td>{{ number_format($item->product['price']) }}</td>
                                    <td>
                                        @if($item->variations != null)
                                            @foreach($item->variations as $itemV)
                                                {{ $itemV['name'] }}:{{$itemV['value']}}:{{number_format($itemV['price'])}}đ<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->toppings != null)
                                            @foreach($item->toppings as $itemT)
                                                {{ $itemT['name'] }}:{{number_format($itemT['price'])}}&nbsp;x{{ $itemT['quantity'] ?? 1 }} = {{number_format($itemT['price'] *$itemT['quantity'] ?? 1)}}&nbsp;đ<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ number_format($item->price) }} đ</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="5">{{ __('theme::products.total_price') }}</th>
                                <th class="text-center">{{ number_format($booking->total_price) }} đ</th>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('stores.name') }} </th>
                    <td> {{ optional($booking->store)->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::bookings.payment_type') }} </th>
                    <td> {{ $booking->payment_type }} </td>
                </tr>
                @if($booking->payment_type != 1)
                    <tr>
                        <th> {{ trans('theme::bookings.place_delivery') }} </th>
                        <td> {{ optional($booking->address)->address. " ".optional(optional($booking->address)->provinces)->name. " ".optional(optional($booking->address)->districts)->name." ". optional(optional($booking->address)->wards)->name }} </td>
                    </tr>
                @endif
                <tr>
                    <th> {{ trans('theme::bookings.payment_method') }} </th>
                    <td> {{ $booking->payment_method }} </td>
                </tr>
                <tr>
                    <th> {{ trans('message.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($booking->created_at)->format(config('settings.format.date')) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::bookings.approved') }} </th>
                    <td>
                        <span class="{{ optional($booking->approve)->color }}">{{ optional($booking->approve)->name }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection