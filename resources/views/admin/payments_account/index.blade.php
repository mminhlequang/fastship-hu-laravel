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
    {{ __('payments_account.title') }}
@endsection
@section('contentheader_title')
    {{ __('payments_account.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('payments_account.title') }}
            </h5>
        </div>
        <div class="box-header">
            <div class="box-tools" style="display: flex;">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/payments_account', 'class' => 'pull-left', 'role' => 'search'])  !!}
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
                    <th class="text-left">{{ trans('message.index') }}</th>
                    <th class="text-left">{{ __('payments_account.account_name') }}</th>
                    <th class="text-left">{{ __('payments_account.account_number') }}</th>
                    <th class="text-left">{{ __('payments_account.bank_name') }}</th>
                    <th class="text-left">{{ __('payments_account.is_verified') }}</th>
                    <th width="15%" class="text-left">@sortablelink('updated_at',__('payments_account.updated_at'))</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td class="text-left">
                            <input type="checkbox" name="chkId" id="chkId" value="{{ $item->id }}"
                                   data-id="{{ $item->id }}"/>
                        </td>
                        <td class="text-left" style="width:5%">{{ ++$index }}</td>
                        <td class="text-left">{{ $item->account_name }}</td>
                        <td class="text-left">{{ $item->account_number }}</td>
                        <td class="text-left">{{ $item->bank_name }}</td>
                        <td class="text-left">{!! $item->is_verified == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                        <td class="text-left">{{ Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
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
                <div id="btn-act">
                    @can('PaymentAccountController@update')
                        <a href="javascript:;" id="activePayments" data-action="activePayments" class="btn-act btn btn-success btn-sm"
                           title="{{ __('message.approved') }}">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </a>
                    @endcan
                </div>
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
        $('#btn-act').on('click', '.btn-act', function (e) {
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
                case 'activePayments':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteNews':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                    title:
                        'Bạn có muốn ' + actTxt +
                        ' tài khoản này không?',
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
                                .catch((error) => {
                                })
                        }
                    });
            } else {
                swal("Lỗi!", 'Vui lòng chọn tin tức để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection