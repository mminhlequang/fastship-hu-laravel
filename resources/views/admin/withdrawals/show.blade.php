@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('withdrawals.name') }}
@endsection
@section('contentheader_title')
    {{ __('withdrawals.name') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('admin/withdrawals') }}">    {{ __('withdrawals.name') }}
            </a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-name">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('admin/withdrawals') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                                                                                           aria-hidden="true"></i>
                    <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>{{ __('withdrawals.user_id') }}</th>
                    <td>{{ optional($data->user)->name }} </td>
                </tr>
                <tr>
                    <th>{{ __('withdrawals.price') }}</th>
                    <td>{{ number_format($data->amount) }} </td>
                </tr>
                <tr>
                    <th>{{ __('withdrawals.status') }}</th>
                    <td>{{ $data->status }} </td>
                </tr>
                <tr>
                    <th>{{ __('Payment method') }}</th>
                    <td>{{ $data->payment_method }} </td>
                </tr>
                <tr>
                    <th>{{ trans('withdrawals.request_date') }}</th>
                    <td>{{ Carbon\Carbon::parse($data->request_date)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
                <tr>
                    <th>{{ trans('withdrawals.processed_date') }}</th>
                    <td>{{ ($data->processed_date != null) ? Carbon\Carbon::parse($data->processed_date)->format(config('settings.format.datetime')):null }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection