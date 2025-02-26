@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::categories.category') }}
@endsection
@section('contentheader_title')
    {{ __('theme::categories.category') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::categories.category') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('CategoryController@store')
                <a href="{{ url('/admin/categories/create') }}" class="btn btn-default float-right"
                   title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                      {{ __('message.new_add') }}</span>
                </a>
            @endcan
        </div>
        <div class="box-header">
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/categories', 'class' => 'pull-left', 'role' => 'search']) !!}
                <div class="input-group" style="margin-right: 5px; display:flex;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                           placeholder="{{ __('message.search_keyword') }}" style="width: 250px; margin-right: 5px;">
                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
        @php($index = ($categories->currentPage()-1)*$categories->perPage())
        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th class="text-left" style="width: 3.5%;">
                        <input type="checkbox" name="chkAll" id="chkAll"/>
                    </th>
                    <th class="text-left" style="width: 3.5%">{{ trans('message.index') }}</th>
                    <th class="text-left">{{ trans('theme::categories.image') }}</th>
                    <th>@sortablelink('title',trans('theme::categories.title'))</th>
                    <th class="text-left">{{ trans('theme::categories.description_index') }}</th>
                    <th class="text-left">{{ trans('stores.name') }}</th>
                    <th class="text-left">{{ trans('theme::categories.active') }}</th>
                    <th class="text-left">@sortablelink('updated_at',trans('theme::categories.updated_at'))</th>
                    <th style="width: 7%;"></th>
                </tr>
                @foreach($categories as $item)
                    <tr>
                        <td class="text-left">
                            <input type="checkbox" name="chkId" id="chkId" value="{{ $item->id }}"
                                   data-id="{{ $item->id }}"/>
                        </td>
                        <td class="text-left">{{ ++$index }}</td>
                        <td class="text-left">
                            @if($item->image != NULL)
                                <img width="100" height="80"
                                     src="{{ url($item->image) }}"
                                     alt="FastShip"/>
                            @endif
                        </td>
                        <td class="text-left">{{ $item->getNameByLocale() }}</td>
                        <td class="text-left">{!! $item->description !!}</td>
                        <td class="text-left">{!! optional($item->store)->name !!}</td>
                        <td class="text-left">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                        <td class="text-left">{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}</td>
                        <td class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('CategoryController@show')
                                    <a href="{{ url('/admin/categories/' . $item->id) }}"
                                       title="{{ __('message.user.view_user') }}">
                                        <button class="btn btn-info btn-sm dropdown-item"><i
                                                    class="fas fa-eye"></i> {{ __('message.view') }}</button>
                                    </a>
                                @endcan
                                @can('CategoryController@update')
                                    <a href="{{ url('/admin/categories/' . $item->id . '/edit') }}"
                                       title="{{ __('message.user.edit_user') }}">
                                        <button class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                                                                                aria-hidden="true"></i> {{ __('message.edit') }}
                                        </button>
                                    </a>
                                @endcan
                                @can('CategoryController@destroy')
                                    {!! Form::open([
                                    'method' => 'DELETE',
                                    'url' => ['/admin/categories', $item->id],
                                    'style' => 'display:inline'
                                    ]) !!}
                                    {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> ' . __('message.delete'), array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm dropdown-item show_confirm',
                                    'title' => __('message.user.delete_user'),
                                    // 'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                                    )) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('CategoryController@destroy')
                    <a href="#" id="deleteCate" data-action="deleteCate" class="btn-act btn btn-danger btn-sm"
                       title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('CategoryController@active')
                    <a href="#" id="activeCate" data-action="activeCate" class="btn-act btn btn-success btn-sm"
                       title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            {!! $categories->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
@endsection
@section('scripts-footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#chkAll').on('click', function() {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });
        $('#btn-act').on('click', '.btn-act', function(e) {
            e.preventDefault();
            let action = $(this).data('action');
            console.log(action);
            ajaxCategory(action);
        });

        function ajaxCategory(action) {
            let chkId = $("input[name='chkId']:checked");
            let actTxt = '',
                successAlert = '',
                classAlert = '';
            switch (action) {

                case 'deleteCate':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
                case 'activeCate':
                    actTxt = 'Duyệt';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                    title: 'Bạn có muốn ' + actTxt +
                        ' danh mục này không?',
                    text: "Nếu bạn xóa nó, nó sẽ biến mất vĩnh viễn.",
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
                                    ids: arrId
                                }
                            })
                                .then((response) => {
                                    if (response.data.success === 'ok') {
                                        location.reload(true);
                                    }
                                })
                                .catch((error) => {})
                        }
                    });
            } else {
                swal("Lỗi!", 'Vui lòng chọn danh mục để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection