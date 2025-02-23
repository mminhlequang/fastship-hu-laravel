@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::menus.menu') }}
@endsection
@section('contentheader_title')
    {{ __('theme::menus.menu') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::menus.menu') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/menus', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 300px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('SysMenuController@store')
                    <a href="{{ url('/admin/menus/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center" style="width: 3.1%;">
                        <input type="checkbox" name="chkAll" id="chkAll" />
                    </th>
                    <th>@sortablelink('title',trans('theme::menus.title'))</th>
                    <th>{{ trans('theme::menus.slug') }}</th>
                    <th>@sortablelink('arrange',trans('theme::menus.arrange'))</th>
                    <th>{{ trans('theme::menus.type_id') }}</th>
                    <th>@sortablelink('updated_at',trans('theme::menus.updated_at'))</th>
                    <th style="width:9%;"></th>
                </tr>
                @php
                    $listMenus = new \App\Models\SysMenu();
                    $listMenus->showListMenus($menus);
                @endphp
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="row">
                <div id="btn-act" class="col-sm-6 text-left">
                    @can('SysMenuController@destroy')
                        <a href="#" id="deleteMenus" data-action="deleteMenus" class="btn-act btn btn-danger btn-sm" title="{{ __('message.delete') }}">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts-footer')
    <script type="text/javascript">
        $(function() {
            $('#chkAll').on('click', function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });
        $('#btn-act').on('click', '.btn-act', function(e){
            e.preventDefault();
            let action = $(this).data('action');
            ajaxCategory(action);
        });
        function ajaxCategory(action){
            let chkId = $("input[name='chkId']:checked");
            let actTxt = '', successAlert = '', classAlert = '';
            switch (action) {
                case 'activeMenus':
                    actTxt = 'duyệt';
                    successAlert = '{{ trans('theme::menus.updated_success') }}';
                    classAlert = 'alert-success';
                    break;
                case 'deleteMenus':
                    actTxt = 'xóa';
                    successAlert = '{{ trans('theme::menus.deleted_success') }}';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0){
                let notificationConfirm = 'Bạn có muốn '+actTxt+' hồ sơ này không?';
                let notification = confirm(notificationConfirm);
                if (notification){
                    var arrId = '';
                    $("input[name='chkId']:checked").map((val,key) => {
                        arrId += key.value + ',';
                    });
                    axios.get('{{url('/ajax')}}/'+action, {
                        params: {
                            ids: arrId
                        }
                    })
                        .then((response) => {
                            if (response.data.success === 'ok'){
                                $('#alert').html('<div class="alert '+classAlert+'">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                                    successAlert +
                                    ' </div>');
                                location.reload(true);
                            }
                        })
                        .catch((error) => {
                        })
                }
            }else{
                let notificationAlert = 'Vui lòng chọn menu để '+actTxt+'!';
                toastr.error(notificationAlert);
            }
        }
    </script>
@endsection
