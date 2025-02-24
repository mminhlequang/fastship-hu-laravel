@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('notifications.notification') }}
@endsection
@section('contentheader_title')
    {{ __('notifications.notification') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/notifications') }}">{{ __('notifications.notification') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/notifications') }}" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th> {{ trans('notifications.title') }} </th>
                    <td> {{ $data->title }} </td>
                </tr>
                <tr>
                    <th> {{ trans('notifications.image') }} </th>
                    <td>
                        {!! ($data->image != null) ? '<img width="40" height="40" src="'.asset($data->image).'">' : '' !!}
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('notifications.description') }} </th>
                    <td>{{ $data->description }}</td>
                </tr>
               {{--  <tr>
                    <th> {{ trans('notifications.user') }} </th>
                    <td>{!! optional($data->user)->name !!}</td>
                </tr>
                --}}
                <tr>
                    <th> {{ trans('theme::categories.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($data->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection