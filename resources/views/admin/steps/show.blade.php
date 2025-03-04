@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('steps.name') }}
@endsection
@section('contentheader_title')
    {{ __('steps.name') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/steps') }}" title="{{ __('message.lists') }}"
                   class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span
                            class="hidden-xs"> {{ __('message.lists') }}</span></a>
                @can('StepController@update')
                    <a href="{{ url('/admin/steps/' . $data->id . '/edit') }}" class="btn btn-default btn-sm mr-1"><i
                                class="far fa-edit"></i> <span class="hidden-xs"> {{ __('message.edit') }}</span></a>
                @endcan
                @can('StepController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/steps', $data->id],
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
                    <th> {{ trans('steps.name') }} </th>
                    <td> {{ $data->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('steps.content') }} </th>
                    <td> {!! $data->content !!} </td>
                </tr>
                <tr>
                    <th> {{ trans('steps.content_pending') }} </th>
                    <td> {!! $data->content_pending !!} </td>
                </tr>
                <tr>
                    <th> {{ trans('steps.arrange') }} </th>
                    <td> {{ $data->arrange }} </td>
                </tr>
                <tr>
                    <th> {{ trans('steps.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($data->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection