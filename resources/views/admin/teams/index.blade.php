@extends('adminlte::layouts.app')
@section('style')
    @toastr_css
    <style>
        .select2 {
            width: 100% !important;
        }

        ul.pagination {
            float: right;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('teams.name') }}
@endsection
@section('contentheader_title')
    {{ __('teams.name') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('teams.name') }}
            </h5>
            @can('TeamController@store')
                <a href="{{ url('/admin/teams/create') }}" class="btn btn-default float-right"
                   title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                      {{ __('message.new_add') }}</span>
                </a>
            @endcan
            <button type="button" class="btn btn-success mr-2 float-right" data-toggle="modal" data-target="#modalInsert">
                <i class="fa fa-plus"></i>&nbsp;Add Member
            </button>
            &nbsp;
        </div>
        <div class="box-header">
            <div class="box-tools" style="display: flex;">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/teams', 'class' => 'pull-left', 'role' => 'search'])  !!}
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
                    <th class="text-left">{{ __('teams.name') }}</th>
                    <th class="text-left">{{ __('teams.logo_url') }}</th>
                    <th class="text-left">{{ __('teams.description') }}</th>
                    <th class="text-center"><i class="far fa-user-tag" aria-hidden="true"></i></th>
                    <th class="text-center"><i class="far fa-user-tag" aria-hidden="true"></i></th>
                    <th width="15%" class="text-left">@sortablelink('updated_at',__('Ngày cập nhật'))</th>
                    <th width="7%"></th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td class="text-left" style="width:5%">{{ ++$index }}</td>
                        <td class="text-left">{{ $item->name }}</td>
                        <td class="text-left">
                            @if($item->logo_url != NULL)
                                <img width="100" height="80"
                                     src="{{ url($item->logo_url) }}"
                                     alt="FastShip"/>
                            @endif
                        </td>
                        <td class="text-left">{!! str_limit($item->description, 150, '...')  !!}</td>
                        <td class="text-center">
                            <a href="javascript:;"
                               class="btn btn-info btn-sm btnInsertPlayer" title="{{ __('Add member') }}"
                               data-id="{{ $item->id }}">
                                <i class="far fa-user-tag" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="javascript:;"
                               class="btn btn-danger btn-sm btnInsertDriver" title="{{ __('Add driver') }}"
                               data-id="{{ $item->id }}">
                                <i class="far fa-user-tag" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="text-left">{{ Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
                        <td class="dropdown text-left">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-tools"></i>
                            </button>
                            <div class="dropdown-menu p-0">
                                @can('TeamController@show')
                                    <a href="{{ url('/admin/teams/' . $item->id) }}" title="{{ __('Xem') }}">
                                        <button class="btn btn-info dropdown-item"><i
                                                    class="fas fa-eye"></i> {{ __('Xem') }}</button>
                                    </a>
                                @endcan
                                @can('TeamController@update')
                                    <a href="{{ url('/admin/teams/' . $item->id . '/edit') }}" title="{{ __('Sửa') }}">
                                        <button class="btn btn-primary dropdown-item"><i class="far fa-edit"
                                                                                         aria-hidden="true"></i> {{ __('Sửa') }}
                                        </button>
                                    </a>
                                @endcan
                                @can('TeamController@destroy')
                                    {!! Form::open([
                                    'method' => 'DELETE',
                                    'url' => ['/admin/teams', $item->id],
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
    @include('admin.teams.modal')
    @include('admin.teams.modal_member')
    @include('admin.teams.modal_insert')
@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
    <script type="text/javascript">
        $(document).ready(function () {
            $('#form-insert-customer').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ url('ajaxPost/insertDriver') }}",
                    method: "POST",
                    data: formData,
                    beforeSend: function () {
                    },
                    success: function (response) {
                        if (response.status) {
                            toastr.success(response.message);
                            $('#modalInsert').modal('hide');
                            $('#form-insert-customer')[0].reset();
                            location.reload();
                        } else {
                            toastr.error('Error Server');
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        $.each(errors, function (key, value) {
                            errorMessages += value + '\n';
                        });
                        toastr.error(errorMessages);
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $('body').on('click', '.remove', function () {
            let id = $(this).data('id');
            let type = $(this).data('type') ?? 1;
            $.ajax({
                url: "{{ url('ajax/deletePlayer') }}",
                type: "GET",
                data: {id: id, type: type},
                dataType: "json",
                success: function (res) {
                    if(res.status){
                        $('tr.tr-add-'+id).remove();
                        toastr.success(res.message);
                    }else
                        toastr.error(res.message);
                }
            });
        });

        $('body').on('click', '.btnInsertDriver', function () {
            let id = $(this).data('id');
            $('#inputDriver').val(id);
            $.ajax({
                url: "{{ url('ajax/loadViewModalDriverClub') }}",
                type: "GET",
                data: {id: id},
                dataType: "json",
                success: function (data) {
                    $('#bodyDrivers').html(data.view);
                    loadSelect2(2);
                }
            });
            $('#modalDrivers').modal('show');

        });
        var indexD = 0;
        $('body').on('click', '.addYearBtnD', function () {
            ++indexD;
            $(".dynamicTableD").append(
                '<tr class="tr-add" >' +
                '<td><select name="players[' + indexD + '][player_id]" class="form-control input-sm select2 selectPlayer" required>@foreach($drivers as $keyY => $valueY)<option value="{{ $keyY }}">{{ $valueY }}</option>@endforeach</select></td>' +
                '<td><select name="players[' + index + '][type]" class="form-control input-sm select2" required>@foreach(\App\Models\Team::$TYPE as $keyX => $valueX)<option value="{{ $keyX }}">{{ $valueX }}</option>@endforeach</select></td>' +
                '</tr>'
            );
            loadSelect2();
        });

        $('body').on('click', '.btnInsertPlayer', function () {
            let id = $(this).data('id');
            $('#inputClub').val(id);
            $.ajax({
                url: "{{ url('ajax/loadViewModalPlayerClub') }}",
                type: "GET",
                data: {id: id},
                dataType: "json",
                success: function (data) {
                    $('#bodyPlayers').html(data.view);
                    loadSelect2();
                }
            });
            $('#modalPlayers').modal('show');

        });
        var index = 0;
        $('body').on('click', '.addYearBtn', function () {
            ++index;
            $(".dynamicTable").append(
                '<tr class="tr-add" >' +
                '<td><select name="players[' + index + '][player_id]" class="form-control input-sm select2 selectPlayer" required>@foreach($players as $keyY => $valueY)<option value="{{ $keyY }}">{{ $valueY }}</option>@endforeach</select></td>' +
                '</tr>'
            );
            loadSelect2();
        });

        let path = "{{ url('ajax/autocompleteSearch') }}";

        function loadSelect2(type = 4) {
            $(".selectPlayer").select2({
                ajax: {
                    url: path,
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            query: params.term,
                            type: type
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                let avatar = '{{ url('') }}' + '/' + item.avatar;
                                return {
                                    text: item.name + ' - ' + item.phone,
                                    avatar: avatar,
                                    id: item.id,
                                }
                            })
                        };
                    }
                },
                formatResult: function (data, term) {
                    return data;
                },
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: function (data, container) {
                    $(data.element).attr('data-custom-attribute', data.customValue);
                    return data.text;
                }
            });
        }

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }
            let $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                "</div>" +
                "</div>" +
                "</div>"
            );
            $container.find(".select2-result-repository__title").text(repo.text);

            return $container;
        }

        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }
    </script>
@endsection