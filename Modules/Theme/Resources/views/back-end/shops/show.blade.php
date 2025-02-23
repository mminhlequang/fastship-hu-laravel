@extends('adminlte::layouts.app')
@php($typeId = (int)\Request::get('type'))
@section('htmlheader_title')
    {{ trans('theme::shops.title') }}
@endsection
@section('contentheader_title')
    {{ trans('theme::shops.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/shops') }}">{{ trans('theme::shops.title') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/shops') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('ShopController@update')
                    <a href="{{ url('/shops/' . $shop->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('ShopController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/shops/', $shop->id],
                        'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span class="hidden-xs">'.__('message.delete').'</span>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'title' => __('message.delete'),
                            'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                    ))!!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th> {{ trans('theme::shops.name') }} </th>
                    <td> {{ $shop->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::shops.address') }} </th>
                    <td> {{ $shop->address }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::shops.phone') }} </th>
                    <td> {{ $shop->phone }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::shops.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($shop->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
