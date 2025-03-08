@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('transactions.name') }}
@endsection
@section('contentheader_title')
    {{ __('transactions.name') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('admin/transactions') }}">    {{ __('transactions.name') }}
            </a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-name">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('admin/transactions') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                                                                                            aria-hidden="true"></i>
                    <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
{{--                &nbsp--}}
{{--                @can('TransactionController@update')--}}
{{--                    <a href="{{ url('admin/transactions/' . $data->id . '/edit') }}" class="btn btn-primary btn-sm"><i--}}
{{--                                class="fa fa-pencil-square-o" aria-hidden="true"></i> <span--}}
{{--                                class="hidden-xs">{{ __('message.edit') }}</span></a>--}}
{{--                @endcan--}}
{{--                &nbsp--}}
{{--                @can('TransactionController@destroy')--}}
{{--                    {!! Form::open([--}}
{{--                    'method'=>'DELETE',--}}
{{--                    'url' => ['admin/transactions', $data->id],--}}
{{--                    'style' => 'display:inline'--}}
{{--                    ]) !!}--}}
{{--                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span--}}
{{--                        class="hidden-xs">'.__('message.delete').'</span>', array(--}}
{{--                    'type' => 'submit',--}}
{{--                    'class' => 'btn btn-danger btn-sm',--}}
{{--                    'name' => __('message.delete'),--}}
{{--                    'onclick'=>'return confirm("'.__('message.confirm_delete').'")'--}}
{{--                    ))!!}--}}
{{--                    {!! Form::close() !!}--}}
{{--                @endcan--}}
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>{{ __('transactions.user_id') }}</th>
                    <td>{{ optional($data->user)->name }} </td>
                </tr>
                <tr>
                    <th>{{ __('transactions.type') }}</th>
                    <td>{{ $data->type }} </td>
                </tr>
                <tr>
                    <th>{{ __('transactions.transaction_type') }}</th>
                    <td>{{ $data->transaction_type }} </td>
                </tr>
                <tr>
                    <th>{{ __('transactions.price') }}</th>
                    <td>{{ number_format($data->price) }} </td>
                </tr>
                <tr>
                    <th>{{ __('transactions.description') }}</th>
                    <td>{{ $data->description }} </td>
                </tr>
                <tr>
                    <th>{{ __('transactions.status') }}</th>
                    <td>{{ $data->status }} </td>
                </tr>
                <tr>
                    <th>{{ trans('transactions.created_at') }}</th>
                    <td>{{ Carbon\Carbon::parse($data->transaction_date)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection