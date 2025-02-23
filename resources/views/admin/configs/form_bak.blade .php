@section('style')
    <style>
        #selectUser {
            width: 95%;
        }
        .select2 {
            min-width: 174px!important;
        }
    </style>
@endsection
<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    <table class="table table-striped">
        <tr class="row {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', trans('configs.user'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('user_id', $users ?? [], null,['class' => 'form-control input-sm select2', 'id' => 'selectUser']) !!}
                {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('label') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('label', trans('Label'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('label',  null,['class' => 'form-control input-sm']) !!}
                {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('promotion_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('promotion_id', trans('promotions.name'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('promotion_id', $promotions ?? [], null,['class' => 'form-control input-sm select2', 'required' => 'required']) !!}
                {!! $errors->first('promotion_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
    @if(isset($inputs))
        <table id="dynamicTable" class="table table-striped">
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Họ tên') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Email') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Số điện thoại') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Ngày sinh') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Date') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Tỉnh/Thành phố') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Select') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Quận/Huyện') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Select') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Xã/Phường') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Select') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Địa chỉ') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>
            </tr>
            @foreach($inputs as $key => $itemI)
                <tr class="row tr-add">
                    <td class="col-md-4 col-lg-3"></td>
                    <td>
                        <div>
                            @can('ConfigController@store')
                                <input type="text" name="input[{{ $key }}][text]" class="form-control input-sm"
                                       placeholder="Tên trường" value="{{ $itemI->text }}" required>
                            @else
                                <input type="text" name="input[{{ $key }}][text]" class="form-control input-sm"
                                       placeholder="Tên trường" value="{{ $itemI->text }}" readonly>
                            @endcan
                        </div>
                    </td>
                    <td>
                        <div class="{{ $errors->has('year_id') ? ' has-error' : ''}}">
                            <select name="input[{{ $key }}][type]" class="form-control input-sm select2"
                                    required>
                                @foreach(\App\Models\Config::$TYPE as $keyY => $valueY)
                                    <option value="{{ $keyY }}" {{ ($keyY == $itemI->type) ? 'selected' : '' }}>{{ $valueY }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <input class="checkbox flat-blue" type="checkbox"
                               name="input[{{ $key }}][active]" {{ ($itemI->active == 1) ? 'checked': '' }}>
                    </td>
                    @can('ConfigController@store')
                        <td>
                            @if($key == 0)
                                <a class="btn btn-md btn-info addYearBtn" href="javascript:;" title="Thêm">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                &nbsp;
                                <a class="trash-item remove"><i class="fa fa-times fa-fw"></i></a>
                            @else
                                <a class="trash-item remove"><i class="fa fa-times fa-fw"></i></a>
                            @endif
                        </td>
                    @endcan

                </tr>
            @endforeach
        </table>

    @else
        <table id="dynamicTable" class="table table-striped">
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Họ tên') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Email') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Số điện thoại') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Ngày sinh') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Date') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Tỉnh/Thành phố') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Select') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Quận/Huyện') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Select') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Xã/Phường') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Select') }}" readonly></td>

            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td><input type="text" class="form-control input-sm"
                           value="{{ __('Địa chỉ') }}" readonly></td>
                <td>
                    <input type="text" class="form-control input-sm"
                           value="{{ __('Text') }}" readonly></td>
            </tr>
            <tr class="row">
                <td class="col-md-4 col-lg-3"></td>
                <td>
                    <div class="{{ $errors->has('name') ? ' has-error' : ''}}">
                        {!! Form::text('input[0][text]', null, ['class' => 'form-control input-sm', 'placeholder' => 'Tên trường','required' => 'required']) !!}
                    </div>
                </td>
                <td>
                    <div class="{{ $errors->has('year_id') ? ' has-error' : ''}}">
                        {!! Form::select('input[0][type]', \App\Models\Config::$TYPE ?? [], null, ['class' => 'form-control input-sm select2', 'required' => 'required']) !!}
                    </div>
                </td>
                <td>
                    <input class="checkbox flat-blue" type="checkbox" name="input[0][active]" value="1" checked>
                </td>
                <td>
                    <a class="btn btn-md btn-info addYearBtn" href="javascript:;" title="Thêm">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
    @endif
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-info mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/configs') }}" class="btn btn-default"><i
                class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>

@section('scripts-footer')
    @if(!\Auth::user()->isAdminCompany())
        <style>
            .select2-container--default .select2-selection--single {
                pointer-events: none;
                user-select: none;
                touch-action: none;
            }
        </style>
    @endif
    <script type="text/javascript">
        let index = {{ isset($inputs) ? array_key_last($inputs) : 0 }};
        $(".addYearBtn").click(function () {
            ++index;
            $("#dynamicTable").append(
                '<tr class="row tr-add" >' +
                '<td class="col-md-4 col-lg-3"></td><td><input type="text" name="input[' + index + '][text]" class="form-control input-sm" placeholder="Tên trường" required></td>' +
                '<td><select name="input[' + index + '][type]" class="form-control input-sm select2" required>@foreach(\App\Models\Config::$TYPE as $keyY => $valueY)<option value="{{ $keyY }}">{{ $valueY }}</option>@endforeach</select></td>' +
                '<td><input class="checkbox flat-blue" type="checkbox" name="input[' + index + '][active]" checked></td>' +
                '<td><a class="trash-item remove" ><i class="fa fa-times fa-fw"></i></a></td>' +
                '</tr>'
            );
            $("input.flat-blue").iCheck({
                checkboxClass: "icheckbox_square-blue",
                radioClass: "iradio_square-blue",
                increaseArea: "20%",
            });
            $(".select2").select2();
        });

        $(document).on('click', '.remove', function () {
            $(this).parents('tr.tr-add').remove();
        });
    </script>
@endsection