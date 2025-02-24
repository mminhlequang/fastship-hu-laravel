@extends('adminlte::layouts.app')

@section('htmlheader_title')
{{ __("message.detail") }}
@endsection
@section('contentheader_title')
@endsection

@section('contentheader_description')

@endsection
@section('css')
    <style>
        .gallery{
            position: relative;
            display: block;
            overflow: hidden;
            padding-bottom: 60%;
            margin-bottom: 10px;
        }span.label.label-success.mr-5 {
    margin-right: 5rem !important;
}
        .gallery img{
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
        }
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/admin/products') }}">    {{ __('address_delivery.list-fcl') }}
    </a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h5 class="float-left">{{ __('message.detail') }}</h5>
        <div class="box-tools">
            <a href="{{ url('/admin/address_delivery') }}" title="{{ __('message.lists') }}" class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span class="hidden-xs"> {{ __('message.lists') }}</span></a>
            @can('AddressDeliveryController@update')
            <a href="{{ url('/admin/address_delivery/' . $address_delivery->id . '/edit') }}" class="btn btn-default btn-sm mr-1"><i class="far fa-edit"></i> <span class="hidden-xs"> {{ __('message.edit') }}</span></a>
            @endcan
            @can('AddressDeliveryController@destroy')
            {!! Form::open([
                'method' => 'DELETE',
                'url' => ['/admin/address_delivery', $address_delivery->id],
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
  
        <table class="table table-striped">
            <tbody>
                <tr >
                    <th > {{ trans('address_delivery.name') }} </th>
                    <td > {{ $address_delivery->name }} </td>
                </tr>
         
                <tr >
                    <th > {{ trans('address_delivery.phone') }} </th>
                    <td > {{ $address_delivery->phone }} </td>
                </tr> 
                  <tr >
                    <th > {{ trans('address_delivery.address') }} </th>
                    <td > {{ $address_delivery->address }} </td>
                </tr>
             
                <tr >
                    <th > {{ trans('address_delivery.customer') }} </th>
                    <td > -{{ optional($address_delivery->customer)->name ?? ""}}
                        <br>
                        - {{ optional($address_delivery->customer)->phone ?? ""}}
                        <br>
                        -{{ optional($address_delivery->customer)->address ?? ""}}
                    </td>
                </tr>
                <tr >
                    <th > {{ trans('address_delivery.province') }} </th>
                    <td > {{ optional($address_delivery->provinces)->name }} </td>

                </tr>
                <tr >
                    <th > {{ trans('address_delivery.districts') }} </th>
                    <td > {{ optional($address_delivery->districts)->name }} </td>
                </tr>
                <tr >
                    <th > {{ trans('address_delivery.wards') }} </th>
                    <td > {{ optional($address_delivery->wards)->name }} </td>
                </tr>
                <tr >
                    <th > {{ trans('address_delivery.created_at') }} </th>
                    <td > {{ Carbon\Carbon::parse($address_delivery->created_at)->format('d/m/Y H:i') }} </td>
                </tr>
                <tr >
                    <th > {{ trans('address_delivery.updated_at') }} </th>
                    <td > {{ Carbon\Carbon::parse($address_delivery->updated_at)->format('d/m/Y H:i') }} </td>
                </tr>
            </tbody>
        </table>
</div>
@endsection