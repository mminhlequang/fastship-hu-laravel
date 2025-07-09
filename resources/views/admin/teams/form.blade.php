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
                    {!! Form::label('name', __('Tên'), ['class' => 'control-label label-required']) !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('logo_url') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('logo_url', trans('teams.logo_url'), ['class' => 'control-label']) !!}
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
                        <div class="imgprev-wrap" style="display:{{ !empty($data->logo_url)?'block':'none' }}">
                            <input type="hidden" value="" name="img-hidden"/>
                            <img class="img-preview" src="{{ !empty($data->logo_url)?asset($data->logo_url):'' }}" alt="{{ trans('theme::news.image') }}"/>
                            <i class="fa fa-trash text-danger"></i>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('description', trans('teams.description'), ['class' => 'control-label']) !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::textarea('description', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
                </td>
            </tr>
        </table>

</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success'])
    !!}
    &nbsp
    <a href="{{ url('admin/teams') }}" class="btn btn-secondary">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
    <script>CKFinder.config({ connectorPath: '/ckfinder/connector' });</script>
    <script>
        CKEDITOR.replace('description', {
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

                if ( /\.(jpe?g|png|gif|webp)$/i.test(file.name) ) {

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