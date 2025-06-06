@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('services.name') }}
@endsection
@section('contentheader_title')
{{ __('services.name') }}
@endsection
@section('contentheader_description')

@endsection

@section('main-content')
<div class="box">
    <div class="content-header border-bottom pb-5">
        <h5 class="float-left">
            {{ __('services.name') }}
        </h5>
        @can('ServiceController@store')
            <div class="aa" style="float:right;">

                <a href="{{ url('/admin/services/create') }}" class="btn btn-default float-right"
                    title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                        {{ __('message.new_add') }}</span>
                </a>
            </div>
        @endcan
    </div>
    <div class="box-header">
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/admin/services', 'class' => 'pull-left', 'role' => 'search']) !!}
            <div class="input-group" style="margin-right: 5px; display:flex;">
              
                <input type="text" value="{{ \Request::get('search') }}" class="form-control input-sm" name="search"
                placeholder="{{ __('message.search_keyword') }}" style="width: 250px; margin-right: 5px;">
            <button class="btn btn-default btn-sm" type="submit">
                <i class="fa fa-search"></i> {{ __('message.search') }}
            </button>
        </div>
            {!! Form::close() !!}
        </div>
    </div>
    @php($index = ($data->currentPage()-1)*$data->perPage())
    <div class="box-bodyno-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th class="text-left">{{ trans('message.index') }}</th>
                    <th>@sortablelink('title',trans('services.name'))</th>
                    <th>{{ trans('services.content') }}</th>
                    <th class="text-center">{{ trans('sliders.arrange') }}</th>
                    <th>@sortablelink('updated_at',trans('sliders.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($services as $item)
                <tr>
                    <td class="text-center">{{ ++$index }}</td>
                    <td>{{ str_repeat('--', $item->level) }} {{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</td>
                    <td>{!! str_limit(\App\Helper\LocalizationHelper::getNameByLocale($item, 'description'), 150, '...') !!}</td>
                    <td class="text-center">{{ $item->arrange }}</td>
                    <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                    <td class="dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fal fa-tools"></i>
                        </button>
                        <div class="dropdown-menu p-0">
                            @can('ServiceController@show')
                                <a href="{{ url('/admin/services/' . $item->id) }}"
                                    title="{{ __('message.user.view_user') }}"><button
                                        class="btn btn-info btn-sm dropdown-item"><i class="fas fa-eye"></i>
                                        {{ __('message.view') }}</button></a>
                            @endcan
                            @can('ServiceController@update')
                                <a href="{{ url('/admin/services/' . $item->id . '/edit') }}"
                                    title="{{ __('message.user.edit_user') }}"><button
                                        class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                            aria-hidden="true"></i> {{ __('message.edit') }}</button></a>
                            @endcan
                            @can('ServiceController@destroy')
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'url' => ['/admin/services', $item->id],
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
            </tbody>
        </table>
        <div class="box-footer clearfix">
            {!! $data->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection
@section('scripts-footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
@endsection