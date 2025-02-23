@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::menus.menu') }}
@endsection
@section('contentheader_title')
    {{ __('theme::menus.menu') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/menus') }}">{{ __('theme::menus.menu') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/menus') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('SysMenuController@update')
                    <a href="{{ url('/admin/menus/' . $menu->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('SysMenuController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['admin/menus', $menu->id],
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
                    <th> {{ trans('theme::menus.title') }} </th>
                    <td> {{ $menu->title }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::menus.url') }} </th>
                    <td> <a href="{{ url($menu->slug) }}{{ $menu->type_id == 1 ? '.html' : '' }}">{{ url($menu->slug) }}{{ $menu->type_id == 1 ? '.html' : '' }}</a> </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::menus.arrange') }} </th>
                    <td> {{ $menu->arrange }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::menus.type_id') }} </th>
                    <td> {{ $typeMenu[$menu->type_id] }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::menus.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($menu->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection