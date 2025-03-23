@extends('adminlte::layouts.app')
@section('style')
    @toastr_css
    <style>
        .select2 {
            width: 250px;
        }

        ul.pagination {
            float: right;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('payments.title') }}
@endsection
@section('contentheader_title')
    {{ __('payments.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('payments.title') }}
            </h5>
        </div>
        <div class="box-header">
            <div class="box-tools" style="display: flex;">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/payments', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                           placeholder="{{ __('message.search_keyword') }}" width="200">
                    <span class="input-group-btn">
                        <button class="btn btn-default " type="submit">
                            <i class="fa fa-search"></i>  {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
        @php($index = ($data->currentPage()-1)*$data->perPage())
        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th class="text-left">{{ trans('message.index') }}</th>
                    <th class="text-left">{{ __('payments.icon_url') }}</th>
                    <th class="text-left">{{ __('payments.name') }}</th>
                    <th class="text-left">{{ __('payments.is_active') }}</th>
                    <th width="15%" class="text-left">@sortablelink('updated_at',__('payments.updated_at'))</th>
                    <th width="7%"></th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td class="text-left" style="width:5%">{{ ++$index }}</td>
                        <td class="text-left">
                            @if($item->icon_url != NULL)
                                <img width="100" height="80"
                                     src="{{ url($item->icon_url) }}"
                                     alt="FastShip"/>
                            @endif
                        </td>
                        <td class="text-left">{{ $item->name }}</td>
                        <td class="text-left">{!! $item->is_active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                        <td class="text-left">{{ Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
                        <td class="dropdown text-left">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('PaymentController@show')
                                    <a href="{{ url('/admin/payments/' . $item->id) }}" title="{{ __('Xem') }}">
                                        <button class="btn btn-info dropdown-item"><i
                                                    class="fas fa-eye"></i> {{ __('Xem') }}</button>
                                    </a>
                                @endcan
                                @can('PaymentController@update')
                                    <a href="{{ url('/admin/payments/' . $item->id . '/edit') }}"
                                       title="{{ __('message.user.edit_user') }}">
                                        <button class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                                                                                aria-hidden="true"></i> {{ __('message.edit') }}
                                        </button>
                                    </a>
                                @endcan
                                @can('PaymentController@destroy')
                                    {!! Form::open([
                                    'method' => 'DELETE',
                                    'url' => ['/admin/payments', $item->id],
                                    'style' => 'display:inline'
                                    ]) !!}
                                    {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> ' . __('Xóa'), array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger dropdown-item',
                                    'title' => __('message.user.delete_user'),
                                    'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                                    )) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if($data->count() == 0)
                    <tr>
                        <td class="text-center" colspan="9"> {{ __('message.no-item') }}
                    </tr>
                @endif
                </tbody>
            </table>
            <div class="box-footer clearfix">
                {!! $data->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
@endsection