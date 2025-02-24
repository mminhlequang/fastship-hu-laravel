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
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('stores.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div>
                    <div class="input-group inputfile-wrap ">
                        <input type="text" class="form-control input-sm" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class=" fa fa-upload"></i>
                                {{ __('message.upload') }}
                            </button>
                            {!! Form::file('image', array_merge(['id'=>'image', 'class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                        </div>
                        {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="imgprev-wrap" style="display:{{ !empty($data->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($data->image)?asset($data->image):'' }}" alt="{{ trans('theme::news.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
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
        <tr class="row {{ $errors->has('province_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('province_id', trans('stores.province'), ['class' => 'control-label  label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('province_id', $provinces, isset($province_id) ? $province_id : null, ['class' => 'form-control input-sm select2', 'required' => 'required', 'id' => 'city_id']) !!}
                {!! $errors->first('province_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('district_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('district_id', trans('stores.district'), ['class' => 'control-label  label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('district_id', $districts, isset($district_id) ? $district_id : null, ['class' => 'form-control input-sm select2', 'id' => 'district_id', 'required' => 'required']) !!}
                {!! $errors->first('district_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('ward_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('ward_id', trans('stores.ward'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('ward_id', $wards, null, ['class' => 'form-control input-sm select2', 'id' => 'ward_id', 'required' => 'required']) !!}
                {!! $errors->first('ward_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('stores.content'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm required']) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/stores') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
    <script>CKFinder.config({ connectorPath: '/ckfinder/connector' });</script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });

    </script>
    @include('ckfinder::setup')
    <script type="text/javascript">
        $(function(){
            $('#image').change(function () {
                var preview = document.querySelector('img.img-preview');
                var file    = document.querySelector('#image').files[0];
                var reader  = new FileReader();

                if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {

                    reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $('.imgprev-wrap').css('display','block');
                        $('.inputfile-wrap').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }else{
                    document.querySelector('#image').value = '';
                    $('.imgprev-wrap').find('input[type=hidden]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    preview.src = '';
                    $('.imgprev-wrap').css('display','none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
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