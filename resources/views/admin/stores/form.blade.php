<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <table class="table table-condensed">
        <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', __('stores.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @foreach (['avatar_image' => trans('stores.avatar_image'), 'facade_image' => trans('stores.facade_image'), 'contact_card_id_image_front' => trans('stores.contact_card_id_image_front'), 'contact_card_id_image_back' => trans('stores.contact_card_id_image_back')] as $name => $label)
            <tr class="row {{ $errors->has($name) ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label($name, $label, ['class' => 'control-label']) !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <div class="input-group inputfile-wrap" data-name="{{ $name }}">
                        <input type="text" class="form-control input-sm" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class=" fa fa-upload"></i> {{ __('message.upload') }}
                            </button>
                            {!! Form::file($name, ['id' => $name, 'class' => 'form-control input-sm file-input', 'accept' => 'image/*', 'data-name' => $name]) !!}
                        </div>
                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="imgprev-wrap" data-name="{{ $name }}"
                         style="display:{{ !empty($data->$name)?'block':'none' }}">
                        <input type="hidden" name="img-hidden[{{ $name }}]" value="">
                        <img class="img-preview" src="{{ !empty($data->$name)?asset($data->$name):'' }}"
                             alt="{{ $label }}"/>
                        <i class="fa fa-trash text-danger delete-preview" data-name="{{ $name }}"></i>
                    </div>
                </td>
            </tr>
        @endforeach
        <tr class="row {{ $errors->has('phone') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('phone', __('stores.phone'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('phone', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_type') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_type', __('stores.contact_type'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_type', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_type', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_full_name') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_full_name', __('stores.contact_full_name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_full_name', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_full_name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_company') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_company', __('stores.contact_company'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_company', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_company', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_company_address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_company_address', __('stores.contact_company_address'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_company_address', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_company_address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_phone') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_phone', __('stores.contact_phone'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_phone', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_email') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_email', __('stores.contact_email'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_email', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_email', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_card_id_issue_date') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_card_id_issue_date', __('stores.contact_card_id_issue_date'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::date('contact_card_id_issue_date', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_card_id_issue_date', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact_card_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact_card_id', __('stores.contact_card_id'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('contact_card_id', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('contact_card_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('address', __('stores.address'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('street') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('street', __('stores.street'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('street', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('street', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('zip') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('zip', __('stores.zip'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('zip', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('zip', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('city') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('city', __('stores.city'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('city', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('state') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('state', __('stores.state'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('state', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('country') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('country', __('stores.country'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('country', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('country_code') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('country_code', __('stores.country_code'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('country_code', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('country_code', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('stores.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('active',  1, (isset($data) && $data->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>

</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/stores') }}" class="btn btn-default"><i
                class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript">
        $(function () {
            $('.file-input').change(function () {
                var input = $(this);
                var name = input.data('name');
                var file = this.files[0];
                var previewImg = $('.imgprev-wrap[data-name="' + name + '"] .img-preview');
                var textInput = $('.inputfile-wrap[data-name="' + name + '"]').find('input[type=text]');

                if (file && /\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.attr('src', e.target.result);
                        $('.imgprev-wrap[data-name="' + name + '"]').css('display', 'block');
                        textInput.val(file.name);
                    };
                    reader.readAsDataURL(file);
                } else {
                    input.val('');
                    $('.imgprev-wrap[data-name="' + name + '"] input[type=hidden]').val('');
                }
            });

            $('.delete-preview').click(function () {
                var name = $(this).data('name');
                if (confirm('Are you sure you want to delete this photo?')) {
                    $('.imgprev-wrap[data-name="' + name + '"] .img-preview').attr('src', '');
                    $('.imgprev-wrap[data-name="' + name + '"]').hide();
                    $('.inputfile-wrap[data-name="' + name + '"]').find('input[type=text]').val('');
                    $('#' + name).val('');
                }
            });
        });

    </script>
    <script type="text/javascript">
        $('body').on('change', '#city_id', function () {
            $('#district_id').html('<option>{{ __('message.loading') }}</option>');
            $('#ward_id').html('<option value="0">{{ __('message.please_select') }}</option>');
            let city_id = $(this).val();
            $.ajax({
                url: '{{ url('ajax/getDistricts?id=') }}' + city_id,
                method: "GET",
                success: function (response) {
                    const data = response;
                    $('#district_id').html('<option>{{ __('message.please_select') }}</option>');
                    $.each(data, function (key, value) {
                        $('#district_id').append('<option value="' + key + '">' + value + '</option>');
                    })
                }
            });
        });

        $('body').on('change', '#district_id', function () {
            $('#ward_id').html('<option value="0">{{ __('message.loading') }}</option>');
            let district_id = $(this).val();
            $.ajax({
                url: '{{ url('ajax/getWards?id=') }}' + district_id,
                method: "GET",
                success: function (response) {
                    const data = response;
                    $('#ward_id').html('<option value="0">{{ __('message.please_select') }}</option>');
                    $.each(data, function (key, value) {
                        $('#ward_id').append('<option value="' + key + '">' + value + '</option>');
                    })
                }
            });
        });
    </script>
@endsection