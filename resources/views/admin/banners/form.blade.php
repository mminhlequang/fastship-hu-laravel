<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <table class="table ">
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('sliders.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('news.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div>
                    <div class="input-group inputfile-wrap ">
                        <input type="text" class="form-control input-sm" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger">
                                <i class=" fa fa-upload"></i>
                                {{ __('message.upload') }}
                            </button>
                            {!! Form::file('image', array_merge(['id'=>'image', 'class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                        </div>
                        {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="imgprev-wrap" style="display:{{ !empty($banners->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($banners->image)?asset($banners->image):'' }}" alt="{{ trans('news.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('type') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', trans('sliders.type'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('type', \App\Models\Banner::$TYPE, null, ['class' => 'form-control input-sm select2', 'id' => 'selectType']) !!}
                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('type') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('reference_id', trans('sliders.reference_id'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('reference_id', [], null, ['class' => 'form-control input-sm select2', 'id' => 'selectReference']) !!}
                {!! $errors->first('reference_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('arrange') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('arrange', trans('sliders.arrange'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('arrange', isset($banners) ? $banners->arrange : $arrange, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('arrange', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('sliders.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('active', 1, isset($banners) && $banners->active === 1 ? true : false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                </div>
                {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/banners') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#selectType').on('change', function () {
                const selectedValue = $(this).val();
                fetch("{{ url('ajax/getReferenceByType') }}?type=" + selectedValue)
                    .then(response => response.json())
                    .then(data => {
                        const $selectReference = $('#selectReference');
                        $selectReference.empty().append('<option value="">--Choose select--</option>');

                        for (const key in data) {
                            $selectReference.append(new Option(data[key], key));
                        }

                        $selectReference.trigger('change');
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            });
        });
    </script>

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
@endsection
