<div class="box-body">
    @if ($errors->any())
	    <div class="alert alert-danger">
	        @foreach ($errors->all() as $error)
	            <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
	        @endforeach
	    </div>
	@endif

        @foreach(Config('company_settings.company_key') as $key => $default)
            <div class="form-group {{ $errors->has($default['key']) ? 'has-error' : ''}}">
                {!! Form::label('key', trans('companies.'.$default['key']), $default['data']['label_attr']) !!}
                <div class="col-md-6">
                    @if($default['type'] === 'file')
                        {{--{!! Form::file($default['key'], ['class' => 'form-control input-sm', 'id' => $default['key']]) !!}--}}
                        {{--<p style="margin-bottom: 0"><img src="{{ !empty($company[$default['key']])?asset(Storage::url($company[$default['key']])): null }}" id="image-review"></p>--}}
                        <div class="input-group inputfile-wrap ">
                            <input type="text" class="form-control input-sm" readonly>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-danger btn-sm">
                                    <i class=" fa fa-upload"></i>
                                    {{ __('message.upload') }}
                                </button>
                                {!! Form::file($default['key'], array_merge(['class' => 'form-control input-sm', 'id' => 'image', "accept" => "image/*"])) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="imgprev-wrap" style="display:{{ !empty($company[$default['key']])?'block':'none' }}">
                            <img class="img-preview" src="{{ !empty($company[$default['key']])?asset(\Storage::url($company[$default['key']])):'' }}"/>
                            <i class="fa fa-trash text-danger"></i>
                        </div>
                    @elseif($default['type'] === 'email')
                        {!! Form::email($default['key'], isset($company[$default['key']])?$company[$default['key']]:null, $default['data']['input_attr']) !!}
                    @else
                        {!! Form::text($default['key'], isset($company[$default['key']])?$company[$default['key']]:null, $default['data']['input_attr']) !!}
                    @endif
                    {!! $errors->first($default['key'], '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        @endforeach
</div>
<div class="box-footer">
    {!! Form::submit(__('message.update'), ['class' => 'btn btn-primary']) !!}
</div>
@section('scripts-footer')
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}" ></script>
    <script type="text/javascript">
        $(function(){
            $('.datepicker').datepicker({
                autoclose: true,
                language: '{{ app()->getLocale() }}',
                format: '{{ config('settings.format.date_js') }}'
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
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
                    $('.imgprev-wrap').find('input[type=text]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    //$('#avatar').val('').attr('required', 'required');
                    $('.imgprev-wrap').find('input[type=text]').val('');
                    preview.src = '';
                    $('.imgprev-wrap').css('display','none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        })
    </script>
@endsection