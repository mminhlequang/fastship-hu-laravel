@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('customers.title') }}
@endsection
@section('contentheader_title')
    {{ __('customers.title') }}
@endsection
@section('contentheader_description')

@endsection

@section('main-content')

    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/customers') }}" title="{{ __('message.lists') }}"
                   class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span
                            class="hidden-xs"> {{ __('message.lists') }}</span></a>
                @can('CustomerController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/customers', auth('api')->id()],
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
                    <th>{{ __('customers.name') }}</th>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.email') }}</th>
                    <td>{{ $customer->email }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.phone') }}</th>
                    <td>{{ $customer->phone }}</td>
                </tr>
                <tr>
                    <th> {{ __('message.created_at') }} </th>
                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->format(config('settings.format.datetime')) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
