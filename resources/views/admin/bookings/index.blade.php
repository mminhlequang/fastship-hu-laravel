@extends('adminlte::layouts.app')
@section('style')
    @toastr_css

    <style>
        .select2 {
            width: 250px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

@endsection

@section('htmlheader_title')
    {{ __('theme::bookings.booking') }}
@endsection
@section('contentheader_title')
    {{ __('theme::bookings.booking') }}
@endsection
@section('contentheader_description')
    <style>
        .select2 {
            width: 150px;
        }
    </style>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::bookings.booking') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ trans('message.bookings') }}
                <p><small class="text-red">Doanh thu : {{ number_format($total) }} đ</small></p>

            </h5>

{{--            @can('BookingController@store')--}}
{{--                <div class="aa" style="float:right;">--}}

{{--                    <a href="{{ url('/admin/bookings/create') }}" class="btn btn-default float-right"--}}
{{--                       title="{{ __('message.new_add') }}">--}}
{{--                        <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">--}}
{{--                    {{ __('message.new_add') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            @endcan--}}
        </div>
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/bookings', 'class' => 'pull-left', 'role' => 'search']) !!}
                <div class="input-group" style="margin-right: 3px; display:flex;">
                    <div class="select-group" style="margin-right: 5px;">
                        {!! Form::select('payment_status', \App\Models\Order::$STATUS ?? [], \Request::get('payment_status'), ['class' => 'form-control input-sm select2', 'id' => 'district']) !!}
                    </div>
                    <div class="input-group1" style="margin-right:5px">
                        <button type="button" class="btn btn-default" id="daterange-btn">
                            @if(empty(Request::get('from')))
                                <span>
                            <i class="far fa-calendar-alt"></i> {{ __('theme::business.date') }}
                        </span>
                            @else
                                <span>
                        {{Carbon\Carbon::parse(Request::get('from'))->format('d/m/Y')}} - {{Carbon\Carbon::parse(Request::get('to'))->format('d/m/Y')}}
                        </span>
                            @endif
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <input type="hidden" name="from"
                               value="{{ empty(Request::get('from')) ? null : Request::get('from') }}"/>
                        <input type="hidden" name="to"
                               value="{{ empty(Request::get('to')) ? null : Request::get('to') }}"/>
                    </div>
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                           placeholder="{{ __('message.search_keyword') }}" style="width: 250px; margin-right: 3px;">
                    <button class="btn btn-default " type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
        @php($index = ($bookings->currentPage()-1)*$bookings->perPage())
        <div class="box-body  no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th width="5%" class="text-center">{{ trans('message.index') }}</th>
                    <th>{{ trans('theme::bookings.code') }}</th>
                    <th class="text-left">{{ trans('theme::bookings.approve') }}</th>
                    <th>@sortablelink('customer.name',trans('customers.name'))</th>
                    <th class="text-center">{{ trans('customers.phone') }}</th>
                    <th class="text-center">{{ trans('theme::bookings.payment_type') }}</th>
                    <th class="text-center">{{ trans('theme::bookings.payment_method') }}</th>
                    <th>{{ trans('theme::bookings.total_price') }}</th>
                    <th>{{ trans('stores.name') }}</th>
                    <th>@sortablelink('created_at',trans('message.created_at'))</th>
                    <th style="width: 3.5%"></th>
                </tr>
                @foreach($bookings as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->code }}</td>
                        <td class="text-left"><span class="btn btn-sm btn-info" id="status-{{ $item->id }}"
                                                    data-id="{{ $item->id }}"
                                                    style="width: 100px;">{{ $item->payment_status }}</span>
                        </td>
                        <td class="text-left">
                            {{ optional($item->customer)->name  ?? ""}}
                            @if(!empty($item->note))
                                <small class="label label-warning" data-toggle="tooltip" data-placement="top"
                                       title="{{ $item->note }}"><i class="fa fa-comment-o"></i></small>
                            @endif
                        </td>
                        <td class="text-center">{{ optional($item->customer)->phone ?? ""}}</td>
                        <td class="text-center">{{ $item->payment_type  }}</td>
                        <td class="text-center">{{ $item->payment_method  }}</td>
                        <td class="text-bold text-danger">{{ number_format($item->total_price) ?? 0 }} đ</td>
                        <td>{{ optional($item->store)->name  ?? " "}}</td>
                        <td>{{ ($item->created_at == null) ? "" : \Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}</td>
                        <td class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('BookingController@show')
                                    <a href="{{ url('/admin/bookings/' . $item->id) }}"
                                       title="{{ __('message.user.view_user') }}">
                                        <button
                                                class="btn btn-info btn-sm dropdown-item"><i class="fas fa-eye"></i>
                                            {{ __('message.view') }}</button>
                                    </a>
                                @endcan
                                @can('BookingController@destroy')
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'url' => ['/admin/bookings', $item->id],
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
                </tbody>
                @if($bookings->count() == 0)
                    <tr>
                        <td class="text-center" colspan="9">{{ trans('theme::bookings.no_item') }}</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="page-footer pull-right">
                {!! $bookings->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
    <script type="text/javascript" src="{{ asset('plugins/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.filer@1.3.0/css/jquery.filer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.filer@1.3.0/css/themes/jquery.filer-dragdropbox-theme.css
    ">
    <script src="{{ asset('plugins/jquery.filer.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">

        $('.show_confirm').click(function (event) {
            var form = $(this).closest("form");
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
        $('body').on('click', '.status-btn', function () {
            $('#modal-status-js').modal('show');
            var id = $(this).data("id");
            axios.get('/ajax/getModalStatus?id=' + id)
                .then(function (res) {
                    var data = res.data.data;
                    $("input[name=book_id]").val(data.id);
                    $("select[name='approve']").val(data.approve_id);
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        $(".btn-change-status").click(function (e) {
            e.preventDefault();
            var id = $("input[name=book_id]").val();
            var approve = $("select[name='approve']").val();
            $.ajax({
                type: "POST",
                url: "{{ url('ajaxPost/updateModalStatus/') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    'id': id,
                    'approve_id': approve
                },
                success: function (res) {
                    var arr = res.approve;
                    var color = arr.color;
                    $('#status-' + id).css("background-color", "" + color).text(arr.name);
                    $('#modal-status-js').modal('hide');
                    swal("Thành công!", 'Cập nhập trạng thái đơn hàng thành công !', "success")
                },
                error: function (res) {
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(function () {
            let from = moment().startOf('year');
            @if (!empty(Request::get('from')))
                from = moment('{{ Request::get('from') }}');
            @endif
            let to = moment().endOf(
                'year');
            @if (!empty(Request::get('to')))
                to = moment('{{ Request::get('to') }}');
            @endif
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        '{{ __('reports.last_7_days') }}': [moment().subtract(6, 'days'), moment()],
                        '{{ __('reports.last_30_days') }}': [moment().subtract(29, 'days'), moment()],
                        '{{ __('reports.this_month') }}': [moment().startOf('month'), moment().endOf(
                            'month')],
                        '{{ __('reports.last_month') }}': [moment().subtract(1, 'month').startOf('month'),
                            moment().subtract(1, 'month').endOf('month')
                        ],
                        '{{ __('reports.three_month') }}': [moment().subtract(3, 'months'), moment()],

                        '{{ __('reports.six_month') }}': [moment().subtract(6, 'months'), moment()],

                        '{{ __('reports.this_year') }}': [moment().startOf('year'), moment().endOf(
                            'year')],
                        '{{ __('reports.last_year') }}': [moment().subtract(1, 'year').startOf('year'),
                            moment().subtract(1, 'year').endOf(
                                'year')
                        ],

                    },
                    startDate: from,
                    endDate: to,
                    "locale": {
                        "format": "{{ strtoupper(config('settings.format.date_js')) }}",
                        "separator": " - ",
                        "applyLabel": "{{ __('reports.apply') }}",
                        "cancelLabel": "{{ __('reports.close') }}",
                        "fromLabel": "{{ __('reports.from') }}",
                        "toLabel": "{{ __('reports.to') }}",
                        "customRangeLabel": "{{ __('reports.custom') }}",
                        "daysOfWeek": [
                            "{{ __('reports.Su') }}",
                            "{{ __('reports.Mo') }}",
                            "{{ __('reports.Tu') }}",
                            "{{ __('reports.We') }}",
                            "{{ __('reports.Th') }}",
                            "{{ __('reports.Fr') }}",
                            "{{ __('reports.Sa') }}"
                        ],
                        "monthNames": [
                            "{{ __('reports.january') }}",
                            "{{ __('reports.february') }}",
                            "{{ __('reports.march') }}",
                            "{{ __('reports.april') }}",
                            "{{ __('reports.may') }}",
                            "{{ __('reports.june') }}",
                            "{{ __('reports.july') }}",
                            "{{ __('reports.august') }}",
                            "{{ __('reports.september') }}",
                            "{{ __('reports.october') }}",
                            "{{ __('reports.november') }}",
                            "{{ __('reports.december') }}"
                        ],
                        "firstDay": 1
                    }
                },
                function (start, end) {
                    $('#daterange-btn span').html(start.format(
                        '{{ strtoupper(config('settings.format.date_js')) }}') + ' - ' + end.format(
                        '{{ strtoupper(config('settings.format.date_js')) }}'));
                    $('[name=from]').val(start.format('YYYY-MM-DD'));
                    $('[name=to]').val(end.format('YYYY-MM-DD'));
                }
            )
        });
    </script>

@endsection