@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('message.stores') }}
@endsection
@section('contentheader_title')
{{ __('message.stores') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('admin/stores') }}">    {{ __('message.stores') }}
    </a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('admin/stores') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                    &nbsp
            @can('StoreController@update')
            <a href="{{ url('admin/stores/' . $data->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            &nbsp
            @can('StoreController@destroy')
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['admin/stores', $data->id],
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
                    <th>{{ __('stores.name') }}</th>
                    <td>{{ $data->name }} </td>
                </tr>
                <tr>
                    <th>{{ __('stores.address') }}</th>
                    <td>{{ $data->address }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.phone') }}</th>
                    <td>{{ $data->phone }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_type') }}</th>
                    <td>{{ $data->contact_type }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_full_name') }}</th>
                    <td>{{ $data->contact_full_name }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_company') }}</th>
                    <td>{{ $data->contact_company }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_company_address') }}</th>
                    <td>{{ $data->contact_company_address }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_phone') }}</th>
                    <td>{{ $data->contact_phone }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_email') }}</th>
                    <td>{{ $data->contact_email }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_card_id') }}</th>
                    <td>{{ $data->contact_card_id }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_card_id_issue_date') }}</th>
                    <td>{{ $data->contact_card_id_issue_date }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_card_id_image_front') }}</th>
                    <td>
                        @if (!empty($data->contact_card_id_image_front))
                            <img src="{{ url($data->contact_card_id_image_front) }}" alt="Front ID" style="max-height:100px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_card_id_image_back') }}</th>
                    <td>
                        @if (!empty($data->contact_card_id_image_back))
                            <img src="{{ url($data->contact_card_id_image_back) }}" alt="Back ID" style="max-height:100px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('stores.contact_tax') }}</th>
                    <td>{{ $data->contact_tax }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.avatar_image') }}</th>
                    <td>
                        @if (!empty($data->avatar_image))
                            <img src="{{ url($data->avatar_image) }}" alt="Avatar" style="max-height:100px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('stores.facade_image') }}</th>
                    <td>
                        @if (!empty($data->facade_image))
                            <img src="{{ url($data->facade_image) }}" alt="Facade" style="max-height:100px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('stores.street') }}</th>
                    <td>{{ $data->street }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.zip') }}</th>
                    <td>{{ $data->zip }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.city') }}</th>
                    <td>{{ $data->city }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.state') }}</th>
                    <td>{{ $data->state }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.country') }}</th>
                    <td>{{ $data->country }}</td>
                </tr>
                <tr>
                    <th>{{ __('stores.country_code') }}</th>
                    <td>{{ $data->country_code }}</td>
                </tr>
                <tr>
                    <th>{{ trans('stores.updated_at') }}</th>
                    <td>{{ Carbon\Carbon::parse($data->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection