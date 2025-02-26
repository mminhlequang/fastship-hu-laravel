<div class="box-body">
    <tr class="row {{ $errors->has('store_id') ? 'has-error' : '' }}">
        <td class="col-md-4 col-lg-3">
            {!! Form::label('store_id', trans('stores.name'), ['class' => 'control-label','title'=>'Vui lòng chọn danh mục trùng với danh mục thương hiệu của sản phẩm']) !!}
        </td>
        <td class="col-md-8 col-lg-9">
            {!! Form::select('store_id', $stores ?? [], null, ['class' => 'form-control input-sm  select2', 'id' => 'category']) !!}
            {!! $errors->first('store_id', '<p class="help-block">:message</p>') !!}
        </td>
    </tr>
    <table class="table table-layout">
        <tr class="row {{ $errors->has('code') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('code', trans('discount.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('code', null, ['class' => 'form-control input-sm ']) !!}
                {!! $errors->first('code', '<p class="help-block text-red">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('discount.name1'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm ']) !!}
                {!! $errors->first('name', '<p class="help-block text-red">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('type') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', trans('discounts.type'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('type', \App\Models\Discount::$TYPE, null,['class' => 'form-control input-sm  select2', 'id' => 'typeee',]) !!}
                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}

            </td>
        </tr>
        <tr class="row {{ $errors->has('value') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('value', trans('discount.value'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('value', null, ['class' => 'form-control input-sm ', 'id'=>'price11',]) !!}
                {!! $errors->first('value', '<p class="help-block text-red">:message</p>') !!}
            </td>
        </tr>
        <tr class="row   form-content{{ $errors->has('sale_maximum') ? 'has-error' : '' }} ">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('sale_maximum', trans('discount.sale_maximum'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('sale_maximum', isset($discounts) && !empty($discounts->sale_maximum) ? number_format($discounts->sale_maximum) : null, ['class' => 'form-control input-sm ', 'id'=>'price2']) !!}
                {!! $errors->first('sale_maximum', '<p class="help-block text-red">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('cart_value') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('cart_value', trans('discount.cart_value'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('cart_value', isset($discounts) && !empty($discounts->cart_value) ? number_format($discounts->cart_value) : null, ['class' => 'form-control input-sm ', 'id'=>'price1']) !!}
                {!! $errors->first('cart_value', '<p class="help-block text-red">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('start_date') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('start_date', trans('discount.start_date'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy">
                    {!! Form::text('start_date', isset($start_date)?$start_date:null, ['class' => 'form-control input-sm datepicker',  'placeholder' => 'dd/mm/yyyy','autocomplete' => 'off', 'onkeyup' => 'date_reformat_dd(this);', 'onkeypress' => 'date_reformat_dd(this);', 'onpaste' => 'date_reformat_dd(this);', 'data-date-format' => 'dd/mm/yyyy' ]) !!}
                    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('expiry_date', trans('discount.exipiry_date'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy">
                    {!! Form::text('expiry_date', isset($date)?$date:null, ['class' => 'form-control input-sm datepicker',  'placeholder' => 'dd/mm/yyyy','autocomplete' => 'off', 'onkeyup' => 'date_reformat_dd(this);', 'onkeypress' => 'date_reformat_dd(this);', 'onpaste' => 'date_reformat_dd(this);', 'data-date-format' => 'dd/mm/yyyy' ]) !!}
                    {!! $errors->first('expiry_date', '<p class="help-block">:message</p>') !!}
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('theme::products.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div>
                    <div class="input-group inputfile-wrap ">
                        <input type="text" class="form-control input-sm" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger ">
                                <i class=" fa fa-upload"></i>
                                {{ __('message.upload') }}
                            </button>
                            {!! Form::file('image', array_merge(['id' => 'image', 'class' => 'form-control input-sm', 'accept' => 'image/*'])) !!}
                        </div>
                    </div>
                    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    <div class="clearfix"></div>

                    <div class="imgprev-wrap" style="display:{{ !empty($discounts->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($discounts->image)?asset($discounts->image):'' }}"
                             alt="{{ trans('theme::categories.avatar') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('description') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', trans('theme::categories.description'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm', 'rows' => 5]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::categories.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('active', 1, (isset($discounts) && $discounts->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                </div>
                {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>

</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/discounts') }}" class="btn btn-default"><i
                class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>

    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script>CKFinder.config({connectorPath: '/ckfinder/connector'});</script>
    <script>
        CKEDITOR.replace('description', {
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });

    </script>
    <script>

        $("#typeee").change(function () {
            var val = $(this).val();
            if (val == 1) {
                $(".form-content").show();
            } else {
                $(".form-content").hide();
            }
        });
    </script>
    <script>
        $("#checkbox").click(function () {
            if ($("#checkbox").is(':checked')) {
                $("#cause > option").prop("selected", "selected");
                $("#cause").trigger("change");

            } else {
                $("#cause > option").prop("selected", "");
                $("#cause").trigger("change");

            }
        });
        $("#checkbox1").click(function () {
            if ($("#checkbox1").is(':checked')) {
                $("#category > option").prop("selected", "selected");
                $("#category").trigger("change");
            } else {
                $("#category > option").prop("selected", "");
                $("#category").trigger("change");
            }
        });
    </script>
    <script type="text/javascript">
        $(function () {
            let date = new Date();
            date.setDate(date.getDate());
            $('.datepicker').datepicker({
                autoclose: true,
                language: '{{ app()->getLocale() }}',
                format: 'dd/mm/yyyy',
                todayHighlight: true,
            });
        });
    </script>
    <script>
        $(function () {
            $('#icon').change(function () {
                var preview1 = document.querySelector('img.img-preview1');
                var file1 = document.querySelector('#icon').files[0];
                var reader1 = new FileReader();

                if (/\.(jpe?g|png|gif)$/i.test(file1.name)) {

                    reader1.addEventListener("load", function () {
                        preview1.src = reader1.result;
                        $('.imgprev-wrap1').css('display', 'block');
                        $('.inputfile-wrap1').find('input[type=text]').val(file1.name);
                    }, false);

                    if (file1) {
                        reader1.readAsDataURL(file1);
                    }
                } else {
                    document.querySelector('#icon').value = '';
                    $('.imgprev-wrap1').find('input[type=text]').val('');
                }
            });
            $('.imgprev-wrap1 .fa-trash').click(function () {
                var preview1 = document.querySelector('img.img-preview1');

                if (confirm('Bạn muốn xóa hình ảnh này ?')) {
                    $('#icon').val('').attr('required', 'required');
                    $('.imgprev-wrap1').find('input[type=text]').val('');
                    preview1.src = '';
                    $('.imgprev-wrap1').css('display', 'none');
                    $('.inputfile-wrap1').find('input[type=text]').val('');
                }
            })
        });

        function changePrice(idObj) {
            idObj.addEventListener('keyup', function () {
                var n = parseInt(this.value.replace(/\D/g, ''), 10);
                idObj.value = Number.isNaN(n) ? 0 : n.toLocaleString('en');
            }, false);
        }

        changePrice(document.getElementById("price1"));
        changePrice(document.getElementById("price2"));
    </script>
    <script type="text/javascript">
        $(function () {
            $('#image').change(function () {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#image').files[0];
                var reader = new FileReader();

                if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                    reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $('.imgprev-wrap').css('display', 'block');
                        $('.inputfile-wrap').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                } else {
                    document.querySelector('#image').value = '';
                    $('.imgprev-wrap').find('input[type=text]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function () {
                var preview1 = document.querySelector('img.img-preview');

                if (confirm('Bạn muốn xóa hình ảnh này ?')) {
                    $('#image').val('').attr('required', 'required');
                    $('.imgprev-wrap').find('input[type=text]').val('');
                    preview1.src = '';
                    $('.imgprev-wrap').css('display', 'none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        });
    </script>
@endsection
