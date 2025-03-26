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
    {{ __('theme::products.product') }}
@endsection
@section('contentheader_title')
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('theme::products.product') }}
            </h5>
            @can('ProductController@store')
                <a href="{{ url('/admin/products/create') }}" class="btn btn-default float-right"
                   title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                      {{ __('message.new_add') }}</span>
                </a>
            @endcan
        </div>
        <div class="box-header">

            <div class="box-tools">

                {!! Form::open(['method' => 'GET', 'url' => '/admin/products', 'class' => 'pull-left', 'role' => 'search'])
                !!}
                <div class="input-group" style="margin-right: 5px; display:flex;">
                    <div class="select-group" style="margin-right: 45px; width:206px;">
                        {!! Form::select('store_id', $stores ?? [], \Request::get('store_id'), ['class' =>
                        'form-control input-sm  select2','id' => 'store_id']) !!}
                    </div>
                    &nbsp;
                    <div class="select-group" style="margin-right: 45px; width:206px;">
                        {!! Form::select('category_id', $categories ?? [], \Request::get('category_id'), ['class' =>
                        'form-control input-sm  select2','id' => 'category_id']) !!}
                    </div>
                    &nbsp;
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
                    <input type="text" value="{{ \Request::get('search') }}" class="form-control input-sm" name="search"
                           placeholder="{{ __('message.search_keyword') }}" style="width: 50px; margin-right: 5px;">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
        <!-- Button trigger modal -->

        @php($index = ($product->currentPage()-1)*$product->perPage())
        <div class="box-body no-padding">

            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th class="text-left">{{ trans('message.index') }}</th>
                    <th class="text-left">{{ trans('theme::products.image') }}</th>
                    <th class="text-left">@sortablelink('name',trans('theme::products.name'))</th>
                    <th class="text-left">{{ trans('theme::products.price') }}</th>
                    <th class="text-left">@sortablelink('category_id',trans('theme::products.category'))
                    </th>
                    <th class="text-left">{{ trans('theme::products.store') }}</th>
                    <th class="text-left">{{ trans('theme::products.active') }}</th>
                    <th class="text-left">@sortablelink('updated_at',trans('theme::products.updated_at'))
                    </th>
                    <th width="7%"></th>
                </tr>
                @foreach($product as $item)
                    <tr>
                        <td class="text-left" style="width: 3.5%">{{ ++$index }}</td>
                        <td class="text-left">
                            @if($item->image != NULL)
                                <img width="100" height="80"
                                     src="{{ url($item->image) }}"
                                     alt="FastShip"/>
                            @endif
                        </td>
                        <td class="text-left">{{ $item->name }}</td>
                        <td class="text-left">{{ number_format($item->price) }}</td>
                        <td class="text-left">{{ optional($item->category)->getNameByLocale() ?? "" }}</td>
                        <td class="text-left">{{ optional($item->store)->name ?? "" }}</td>
                        <td class="text-left">{!! $item->active == config('settings.active') ? '<i
                            class="fa fa-check text-primary"></i>' : '' !!}</td>
                        <td class="text-left">{{ Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('ProductController@show')
                                    <a href="{{ url('/admin/products/' . $item->id) }}"
                                       title="{{ __('message.user.view_user') }}">
                                        <button class="btn btn-info btn-sm dropdown-item"><i
                                                    class="fas fa-eye"></i> {{ __('message.view') }}</button>
                                    </a>
                                @endcan
                                @can('ProductController@update')
                                    <a href="{{ url('/admin/products/' . $item->id . '/edit') }}"
                                       title="{{ __('message.user.edit_user') }}">
                                        <button class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                                                                                aria-hidden="true"></i> {{ __('message.edit') }}
                                        </button>
                                    </a>
                                @endcan
                                @can('ProductController@destroy')
                                    {!! Form::open([
                                    'method' => 'DELETE',
                                    'url' => ['/admin/products', $item->id],
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
                @if($product->count() == 0)
                    <tr>
                        <td class="text-left" colspan="9">{{ trans('theme::products.noItem') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div class="box-footer clearfix">
                {!! $product->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
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
        $(function () {
            let from = moment().subtract(29, 'days');
            @if (!empty(Request::get('from')))
                from = moment('{{ Request::get('from') }}');
            @endif
            let to = moment();
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
                        '{{ __('reports.this_year') }}': [moment().startOf('year'), moment().endOf(
                            'year')],
                        '{{ __('reports.last_year') }}': [moment().subtract(1, 'year').startOf('year'),
                            moment().subtract(1, 'year').endOf('year')
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
    @toastr_js
    @toastr_render
@endsection