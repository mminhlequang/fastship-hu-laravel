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
        <li class="active">{{ trans('theme::partners.title') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/partners?type=' . $typeId, 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 200px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('PartnerController@store')
                    <a href="{{ url('/partners/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        @php($index = ($partners->currentPage()-1)*$partners->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>{{ trans('theme::partners.name') }}</th>
                    <th class="col-md-3">{{ trans('theme::partners.image') }}</th>
                    <th class="text-center">{{ trans('theme::partners.active') }}</th>
                    <th class="text-center">{{ trans('theme::partners.arrange') }}</th>
                    <th class="text-center">{{ trans('theme::partners.updated_at') }}</th>
                    <th class="text-center"></th>
                </tr>
                @foreach($partners as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>
                            <a href="{{ empty($item->link) ? '#' : $item->link }}">{{ $item->name }}</a>
                        </td>
                        <td>
                            {!! !empty($item->image) ? '<img style="object-fit: cover" width="80" src="'.asset($item->image).'">' : '' !!}
                        </td>
                        <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                        <td class="text-center">{{ $item->arrange }}</td>
                        <td class="text-center">{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}</td>
                        <td class="text-center">
                            @can('PartnerController@show')
                                <a href="{{ url('/partners/' . $item->id) }}" title="{{ __('message.view') }}" title="{{ __('message.view') }}"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                            @endcan
                            @can('PartnerController@update')
                                <a href="{{ url('/partners/' . $item->id . '/edit') }}" title="{{ __('message.edit') }}" title="{{ __('message.edit') }}">
                                    <button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                </a>
                            @endcan
                            @can('PartnerController@destroy')
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/partners', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ', array(
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
                {!! $partners->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
