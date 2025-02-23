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
    <li class="active">{{ __('theme::ads.ads') }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ __('message.lists') }}</h3>
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/ads', 'class' => 'pull-left', 'role' => 'search']) !!}
            <div class="input-group" style="width: 200px;">
                <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                    placeholder="{{ __('message.search_keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
            </div>
            {!! Form::close() !!}
            @can('AdsController@store')
            <a href="{{ url('/ads/create') }}" class="btn btn-success btn-sm"
                title="{{ __('message.new_add') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
            </a>
            @endcan
        </div>
    </div>
    @php($index = ($ads->currentPage()-1)*$ads->perPage())
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th class="text-center">{{ trans('theme::ads.image') }}</th>
                    <th>@sortablelink('title',trans('theme::ads.name'))</th>
                    <th>{{ trans('theme::ads.link') }}</th>
                    <th>{{ trans('theme::ads.postion') }}</th>
                    <th class="text-center">{{ trans('theme::ads.active') }}</th>
                    <th>@sortablelink('updated_at',trans('theme::ads.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($ads as $item)
                <tr>
                    <td class="text-center">{{ ++$index }}</td>
                    <td class="text-center">{!! $item->image ? '<img width="40" height="40" src="'.$item->image.'">' : '' !!}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->link }}</td>
                    <td>{{ $postion[$item->postion] }}</td>
                    <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                    <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                    <td>
                        @can('AdsController@show')
                        <a href="{{ url('/ads/' . $item->id) }}" title="{{ __('message.view') }}"><button
                                class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i>
                               </button></a>
                        @endcan
                        @can('AdsController@update')
                        <a href="{{ url('/ads/' . $item->id . '/edit') }}"
                            title="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button></a>
                        @endcan
                        @can('AdsController@destroy')
                        {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/ads', $item->id],
                        'style' => 'display:inline'
                        ]) !!}
                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ',
                        array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'title' => __('message.delete'),
                        'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                        )) !!}
                        {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="box-footer clearfix">
            {!! $ads->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>
@endsection