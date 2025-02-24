@extends('adminlte::layouts.app')
@section('style')
    @toastr_css
    <style>
        .select2 {
            width: 250px;
        }

        ul.pagination {
            float: right !important;
        }

    </style>
@endsection
@section('htmlheader_title')
{{ __('address_delivery.name') }}
@endsection
@section('contentheader_title')
    {{ __('address_delivery.name') }}
  
@endsection
@section('contentheader_description')
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('address_delivery.name') }}
            </h5>
            @can('AddressDeliveryController@store')
                <div class="aa" style="float:right;">
                    <a href="{{ url('/admin/address_delivery/create') }}" class="btn btn-default float-right"
                        title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                            {{ __('message.new_add') }}</span>
                    </a>
                </div>
            @endcan
        </div>
        <div class="box-header">
            <div class="box-tools">

                {!! Form::open(['method' => 'GET', 'url' => '/admin/address_delivery', 'class' => 'pull-left', 'role' => 'search'])
                !!}
                <div class="input-group" style="margin-right: 5px; display:flex;">
    
                    <div class="select-group" style="margin-right: 10px; width:240px;">
                        {!! Form::select('province_id', $provinces ?? [], \Request::get('province_id'), ['class' =>
                        'form-control input-sm select2','id' => 'province_id']) !!}
                    </div>

                    <input type="text" value="{{ \Request::get('search') }}" class="form-control input-sm" name="search"
                        placeholder="{{ __('message.search_keyword') }}" style="width: 50px; margin-right: 5px;">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>
                {!! Form::close() !!}
    
            </div>
           
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-fw fa-check"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }} <br>
                @endforeach
            </div>
        @endif
        @php($index = ($address_delivery->currentPage() - 1) * $address_delivery->perPage())
        <div class="box-body  no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr >
                        <th class="text-center">{{ trans('message.index') }}</th>
                        <th class="text-left" >{{ trans('address_delivery.name1') }}</th>
                        <th class="text-center" >{{ trans('address_delivery.phone') }}</th>
                        <th class="text-left" >{{ trans('address_delivery.address') }}</th>
                        <th class="text-left" >{{ trans('address_delivery.province') }}</th>
                        <th class="text-left" >{{ trans('address_delivery.districts') }}</th>
                        <th class="text-left" >{{ trans('address_delivery.wards') }}</th>
                        <th class="text-center" >{{ trans('address_delivery.active') }}</th>
                        <th class="text-center" >
                            @sortablelink('updated_at',trans('theme::products.updated_at'))
                        </th>
                        <th width="7%"></th>
                    </tr>
                    @foreach ($address_delivery as $item)
                        <tr>
                            <td class="text-center" style="width: 3.5%">{{ ++$index }}</td>
                            <td class="text-left">{{ $item->name }}</td>
                            <td class="text-left">{{ $item->phone }}</td>
                            <td class="text-left">{{ $item->address }}</td>
                     
                            <td class="text-left">{{ optional($item->provinces)->{'name'} }}</td>
                            <td class="text-left">{{ optional($item->districts)->{'name'} }}</td>
                            <td class="text-left">{{ optional($item->wards)->{'name'} }}</td>
                            <td class="text-center">{!! $item->is_default == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                            <td class="text-center">{{ Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</td>

                            <td class="dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fal fa-tools"></i>
                                </button>
                                <div class="dropdown-menu p-0">
                                    @can('AddressDeliveryController@show')
                                        <a href="{{ url('/admin/address_delivery/' . $item->id) }}"
                                            title="{{ __('message.user.view_user') }}"><button
                                                class="btn btn-info btn-sm dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('message.view') }}</button></a>
                                    @endcan
                                    @can('AddressDeliveryController@update')
                                        <a href="{{ url('/admin/address_delivery/' . $item->id . '/edit') }}"
                                            title="{{ __('message.user.edit_user') }}"><button
                                                class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                                    aria-hidden="true"></i> {{ __('message.edit') }}</button></a>
                                    @endcan
                                    @can('AddressDeliveryController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'url' => ['/admin/address_delivery', $item->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                                                                {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> ' . __('message.delete'), [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-sm dropdown-item show_confirm',
                                            'title' => __('message.user.delete_user'),
                                            // 'onclick' => 'return confirm("' . __('message.confirm_delete') . '")',
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($address_delivery->count() == 0)
                        <tr>
                            <td class="text-center" colspan="9"> {{ __('message.no-item') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="box-footer clearfix">
                <div id="btn-act">
                    {!! $address_delivery->appends(\Request::except('page'))->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
<script type="text/javascript">
 
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `
                    Bạn có chắc chắn muốn xóa bản ghi này không?`,
              text: "Nếu bạn xóa nó, nó sẽ biến mất vĩnh viễn.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
</script>
    <script type="text/javascript">
        $("#province").change(function() {
            let id = $(this).val();
            $("#export-province").val(id);
        });
        $("#type_business_id").change(function() {
            let id = $(this).val();
            $("#export-province").val(id);
        });
        
    </script>
    <script>
        $(function() {
            $('#file_task').filer({
                limit: 1,
                maxSize: 5,
                extensions: ['xlsx', 'xlsm', 'xml', 'xls'],
                showThumbs: true,
                changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Kéo hoặc thả tệp vào đây</h3> <span style="display:inline-block; margin: 15px 0"><br>(hoặc nhấp để tải lên...)</span></div></div></div>',
                dragDrop: {
                    dragEnter: null,
                    dragLeave: null,
                    drop: null,
                },
                addMore: true,

            });


        });
    </script>
  
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
            case 'activeAddress':
                actTxt = 'mặc định';
                classAlert = 'alert-success';
                break;
            case 'deleteAddress':
                actTxt = 'xóa';
                classAlert = 'alert-danger';
                break;
        }
        if (chkId.length != 0) {
            swal({
              title: 
              'Bạn có muốn ' + actTxt +
                    ' địa chỉ giao hàng này không?',
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
                axios.get('{{ url('/admin/ajax') }}/' + action, {
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
            swal("Lỗi!", 'Vui lòng chọn địa chỉ giao hàng để  ' + actTxt + '!', "error")
        }
    }
</script>
@endsection
