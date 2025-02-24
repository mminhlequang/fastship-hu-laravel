@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('theme::categories.category') }}
@endsection
@section('contentheader_title')
@endsection
@section('contentheader_description')

@endsection

@section('main-content')
<div class="box">
    <div class="box-header">
        <h5 class="float-left">{{ __('message.detail') }}</h5>
        <div class="box-tools">
            <a href="{{ url('/admin/categories') }}" title="{{ __('message.lists') }}" class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span class="hidden-xs"> {{ __('message.lists') }}</span></a>
            @can('CategoryProductController@update')
            <a href="{{ url('/admin/categories/' . $discounts->id . '/edit') }}" class="btn btn-default btn-sm mr-1"><i class="far fa-edit"></i> <span class="hidden-xs"> {{ __('message.edit') }}</span></a>
            @endcan
            @can('CategoryProductController@destroy')
            {!! Form::open([
                'method' => 'DELETE',
                'url' => ['/admin/categories', $discounts->id],
                'style' => 'display:inline'
            ]) !!}
                {!! Form::button('<i class="far fa-trash-alt"></i> <span class="hidden-xs"> '. __('message.delete') .'</span>', array(
                        'type' => 'submit',
                        'class' => 'btn btn-default btn-sm',
                        'title' => 'Xoá',
                        'onclick'=>'return confirm("'. __('message.confirm_delete') .'?")'
                ))!!}
            {!! Form::close() !!}
            @endcan
        </div>
    </div>
    
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th>{{ trans('discounts.name') }}</th>
                    <td>{{ $discounts->{'name'} }} </td>
                </tr>
                <tr>
                    <th>{{ trans('discounts.image') }}</th>
                    <td>
                        @if(!empty($discounts->image))
                        <img width="100" src="{{ asset($discounts->image) }}" alt="anh" />
                        @endif
                    </td>
                </tr>
                <tr >
                    <th > {{ trans('discounts.active') }} </th>
                    <td>{!! $discounts->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                </tr>
                <tr>
                    <th>{{ trans('discounts.description') }}</th>
                    <td>{!! $discounts->description !!}</td>
                </tr>
                <tr>
                    <th>{{ trans('discounts.updated_at') }}</th>
                    <td>{{ Carbon\Carbon::parse($discounts->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection