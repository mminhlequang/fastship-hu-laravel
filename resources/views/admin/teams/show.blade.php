@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('message.contacts') }}
@endsection
@section('contentheader_title')
{{ __('message.contacts') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('admin/teams') }}">   {{ __('teams.name') }}
    </a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('admin/teams') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                    &nbsp
            @can('TeamController@update')
            <a href="{{ url('admin/teams/' . $data->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            &nbsp
            @can('TeamController@destroy')
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['admin/teams', $data->id],
            'style' => 'display:inline'
            ]) !!}
            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span
                class="hidden-xs">'.__('message.delete').'</span>', array(
            'type' => 'submit',
            'class' => 'btn btn-danger btn-sm',
            'name' => __('message.delete'),
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
                    <th>{{ __('teams.name') }}</th>
                    <td>{{ $data->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('teams.logo_url') }} </th>
                    <td>
                        @if(!empty($data->logo_url))
                            <img width="100" src="{{ asset($data->logo_url) }}" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('teams.description') }}</th>
                    <td>{!! $data->description !!} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::news.updated_at') }} </th>
                    <td>{{ Carbon\Carbon::parse($data->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection