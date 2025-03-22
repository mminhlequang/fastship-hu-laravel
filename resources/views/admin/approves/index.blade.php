@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::approves.approves') }}
@endsection
@section('contentheader_title')
    {{ __('theme::approves.approves') }}
@endsection
@section('contentheader_description')
    
@endsection

@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ trans('theme::approves.approves') }}      </h5>
        @can('ApproveController@store')
            <div class="aa" style="float:right;">
    
                <a href="{{ url('/admin/approves/create') }}" class="btn btn-default float-right"
                    title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                        {{ __('message.new_add') }}</span>
                </a>
            </div>
        @endcan
       </div>
        <div class="box-header">
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/approves', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 200px;" style="margin-right: 3px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default " type="submit">
                            <i class="fa fa-search"></i>  {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
           
            </div>
        </div>
        @php($index = ($approves->currentPage()-1)*$approves->perPage())
        <div class="box-body  no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('name', trans('theme::approves.name'))</th>
                    <th  class="text-center">@sortablelink('number', trans('theme::approves.number'))</th>
                    <th>@sortablelink('color', trans('theme::approves.color'))</th>
                    <th class="text-center">@sortablelink('updated_at', trans('message.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($approves as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->name }}</td>
                        <td  class="text-center" >{{ $item->number }}</td>
                        <td><span class="label-color" style="background-color: {{ $item->color }}">{{ \App\Helper\LocalizationHelper::getNameByLocale($item, 'name') }}</span></td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('ApproveController@show')
                                <a href="{{ url('/admin/approves/' . $item->id) }}" title="{{ __('message.view') }}"><button class="btn btn-info btn-sm dropdown-item"><i class="fas fa-eye"></i> {{ __('message.view') }}</button></a>
                                @endcan
                                @can('ApproveController@update')
                                <a href="{{ url('/admin/approves/' . $item->id . '/edit') }}" title="{{ __('message.edit') }}"><button class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit" aria-hidden="true"></i> {{ __('message.edit') }}</button></a>
                                @endcan
                                @can('ApproveController@destroy')
                                {!! Form::open([
                                'method' => 'DELETE',
                                'url' => ['/admin/approves', $item->id],
                                'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> ' . __('message.delete'), array(
                                'type' => 'submit',
                                'class' => 'btn btn-danger btn-sm dropdown-item',
                                'title' => __('message.user.delete_user'),
                                'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                                )) !!}
                                {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                     
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="box-comment">
                <p style="padding: 8px 20px; margin-bottom: 0; border-top: 1px solid #f9f9f9"><span style="color: #f44336;">{{ trans('theme::approves.note') }}*:</span> {{ trans('theme::approves.note_text') }}</p>
            </div>
            <div class="box-footer clearfix">
                {!! $approves->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>

@endsection
