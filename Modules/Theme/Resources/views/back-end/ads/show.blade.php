@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::ads.ads') }}
@endsection
@section('contentheader_title')
    {{ __('theme::ads.ads') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/ads') }}">{{ __('theme::ads.ads') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/ads') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('AdsController@update')
                    <a href="{{ url('/ads/' . $ads->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('AdsController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['ads', $ads->id],
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
                    <th> {{ trans('theme::ads.name') }} </th>
                    <td> {{ $ads->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::ads.link') }} </th>
                    <td> <a href="{{ url($ads->link) }}">{{ url($ads->link) }}</a></td>
                </tr>
                <tr>
                    <th> {{ trans('theme::ads.image') }} </th>
                    <td>
                        @if(!empty($ads->image))
                            <img width="100" src="{{ $ads->image }}" alt="anh"/>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::ads.active') }} </th>
                    <td>{!! $ads->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('theme::ads.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($ads->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection