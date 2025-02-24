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
        <li class="active">{{ __('notifications.notification') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('Thông báo') }}
            </h5>
            @can('NotificationController@store')
                <div class="aa" style="float:right;">
                    <a href="{{ url('/admin/notifications/create') }}" class="btn btn-default  float-right"
                       title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                          {{ __('message.new_add') }}</span>
                    </a>
                </div>
            @endcan
        </div>
        <div class="box-header">

            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/notifications', 'class' => 'pull-left', 'role' => 'search'])
                !!}
                <div class="input-group" style="margin-right: 5px; display:flex;">

                    <div class="select-group" style="margin-right: 10px; width:206px;">
                        {!! Form::select('user_id', $users ?? [], \Request::get('user_id'), ['class' =>
                        'form-control input-sm  select2','id' => 'user_id']) !!}
                    </div>
                    <input type="text" value="{{ \Request::get('search') }}" class="form-control input-sm"
                           name="search"
                           placeholder="{{ __('message.search_keyword') }}" style="width: 50px; margin-right: 5px;">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>

                </div>
                {!! Form::close() !!}


            </div>
        </div>
        @php($index = ($data->currentPage()-1)*$data->perPage())
        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th class="text-center" style="width: 3.5%">{{ trans('message.index') }}</th>
                    <th>{!! trans('notifications.user') !!}</th>
                    <th>@sortablelink('title',trans('notifications.title'))</th>
                    <th>{!! trans('notifications.description') !!}</th>
                    <th>@sortablelink('updated_at',trans('notifications.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td title="{{ optional($item->user)->name ?? "" }}">{{ Str::limit(optional($item->user)->name ?? "", 25, '...') }}</td>
                        <td>{{ $item->title }}</td>
                        <td title="{{ $item->description }}">{!! Str::limit($item->description, 50, '...') !!}</td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('NotificationController@show')
                                    <a href="{{ url('/admin/notifications/' . $item->id) }}"
                                       title="{{ __('message.user.view_user') }}">
                                        <button
                                                class="btn btn-info btn-sm dropdown-item"><i class="fas fa-eye"></i>
                                            {{ __('message.view') }}</button>
                                    </a>
                                @endcan
                                @can('NotificationController@update')
                                    <a href="{{ url('/admin/notifications/' . $item->id . '/edit') }}"
                                       title="{{ __('message.user.edit_user') }}">
                                        <button
                                                class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                                                                                aria-hidden="true"></i> {{ __('message.edit') }}
                                        </button>
                                    </a>
                                @endcan
                                @can('NotificationController@destroy')
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'url' => ['/admin/notifications', $item->id],
                                        'style' => 'display:inline',
                                    ]) !!}
                                    {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> ' . __('message.delete'), [
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-sm dropdown-item',
                                        'title' => __('message.user.delete_user'),
                                        'onclick' => 'return confirm("' . __('message.confirm_delete') . '")',
                                    ]) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($data->count() == 0)
                    <tr>
                        <td class="text-center" colspan="11">{{ trans('theme::bookings.no_item') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection