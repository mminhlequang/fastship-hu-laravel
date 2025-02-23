@extends('adminlte::layouts.app')
@php($typeId = (int)\Request::get('type'))
@section('htmlheader_title')
    {{ trans('theme::partners.title') }}
@endsection
@section('contentheader_title')
    {{ trans('theme::partners.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/partners') }}">{{ trans('theme::partners.title') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/partners') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('PartnerController@update')
                    <a href="{{ url('/partners/' . $partner->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('PartnerController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/partners/', $partner->id],
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
                @empty(!($partner->image))
                <tr>
                    <th> {{ trans('theme::partners.image') }} </th>
                    <td>
                        <img width="400" src="{{ asset($partner->image) }}" alt="anh"/>
                    </td>
                </tr>
                @endempty
                <tr>
                    <th> {{ trans('theme::partners.name') }} </th>
                    <td> {{ $partner->name }} </td>
                </tr>
                @empty(!$partner->link)
                <tr>
                    <th> {{ trans('theme::partners.link') }} </th>
                    <td> {{ $partner->link }} </td>
                </tr>
                @endempty
                <tr>
                    <th> {{ trans('theme::partners.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($partner->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
