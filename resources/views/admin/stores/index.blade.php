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
    {{ __('message.stores') }}
@endsection
@section('contentheader_title')
    {{ __('message.stores') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.stores') }}
            </h5>
            @can('StoreController@store')
                <a href="{{ url('/admin/stores/create') }}" class="btn btn-default float-right"
                   title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                      {{ __('message.new_add') }}</span>
                </a>
            @endcan
        </div>
        <div class="box-header">
            <div class="box-tools" style="display: flex;">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/stores', 'class' => 'pull-left', 'role' => 'search'])  !!}
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
                    <th class="text-left" style="width: 3.5%;">
                        <input type="checkbox" name="chkAll" id="chkAll"/>
                    </th>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th class="text-left">{{ trans('stores.image') }}</th>
                    <th class="text-left">@sortablelink('name',__('stores.name'))</th>
                    <th class="text-left">{{ __('stores.address') }}</th>
                    <th class="text-center">{{ __('stores.active') }}</th>
                    <th class="text-center">@sortablelink('updated_at',__('Ngày cập nhật'))</th>
                    <th width="7%"></th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td class="text-left">
                            <input type="checkbox" name="chkId" id="chkId" value="{{ $item->id }}"
                                   data-id="{{ $item->id }}"/>
                        </td>
                        <td class="text-left" style="width:5%">{{ ++$index }}</td>
                        <td class="text-left">
                            @if($item->avatar_image != NULL)
                                <img width="100" height="80"
                                     src="{{ url($item->avatar_image) }}"
                                     alt="FastShip"/>
                            @endif
                        </td>
                        <td class="text-left">{{ $item->name }}</td>
                        <td class="text-left">{{ $item->address }}</td>
                        <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                        <td class="text-center">{{ Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
                        <td class="dropdown text-center">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('StoreController@show')
                                    <a href="{{ url('/admin/stores/' . $item->id) }}" title="{{ __('Xem') }}">
                                        <button class="btn btn-info dropdown-item"><i
                                                    class="fas fa-eye"></i> {{ __('Xem') }}</button>
                                    </a>
                                @endcan
                                @can('StoreController@update')
                                    <a href="{{ url('/admin/stores/' . $item->id . '/edit') }}" title="{{ __('Sửa') }}">
                                        <button class="btn btn-primary dropdown-item"><i class="far fa-edit"
                                                                                         aria-hidden="true"></i> {{ __('Sửa') }}
                                        </button>
                                    </a>
                                @endcan
                                @can('StoreController@destroy')
                                    {!! Form::open([
                                    'method' => 'DELETE',
                                    'url' => ['/admin/stores', $item->id],
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
                @can('NewsController@destroy')
                    <a href="javascript:;" id="deleteTable" data-action="deleteTable" class="btn-act btn btn-danger btn-sm"
                       title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('NewsController@active')
                    <a href="javascript:;" id="activeTable" data-action="activeTable" class="btn-act btn btn-success btn-sm"
                       title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
                {!! $data->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
@section('scripts-footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    @toastr_js
    @toastr_render
    <script type="text/javascript">
        $(function () {
            $('#chkAll').on('click', function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });
        $('body').on('click', '.btn-act', function (e) {
            e.preventDefault();
            let action = $(this).data('action');
            console.log(action);
            ajaxCategory(action);
        });

        function ajaxCategory(action) {
            let chkId = $("input[name='chkId']:checked");
            let actTxt = '',
                classAlert = '';
            switch (action) {
                case 'activeTable':
                    actTxt = 'Active';
                    classAlert = 'alert-success';
                    break;
                case 'deleteTable':
                    actTxt = 'De active';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                    title:
                        'Do you want to ' + actTxt +
                        ' This store does not?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var arrId = '';
                            $("input[name='chkId']:checked").map((val, key) => {
                                arrId += key.value + ',';
                            });
                            axios.get('{{ url('/ajax') }}/' + action, {
                                params: {
                                    table: 'stores',
                                    ids: arrId
                                }
                            })
                                .then((response) => {
                                    if (response.data.success === 'ok') {
                                        location.reload(true);
                                    }
                                })
                                .catch((error) => {
                                })
                        }
                    });
            } else {
                swal("Error!", 'Please select a store to  ' + actTxt + '!', "error")
            }
        }
    </script>

@endsection