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
    <table class="table  table-condensed">
        <tr class="row {{ $errors->has('title') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title', trans('theme::news.title'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('title', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('category_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('category_id', trans('theme::news.category'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('category_id', $news_cate, null, ['class' => 'form-control input-sm select2','required' => 'required']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('theme::news.image'), ['class' => 'control-label']) !!}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($news->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($news->image)?asset($news->image):'' }}" alt="{{ trans('theme::news.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        {{-- <tr class="row {{ $errors->has('video_url') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('video_url', trans('theme::news.video_url'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('video_url', null, ['class' => 'form-control input-sm']) !!}
            </td>
        </tr> --}}
        {{-- <tr class="row {{ $errors->has('keywords') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('keywords', trans('theme::news.keywords'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('keywords', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
            </td>
        </tr> --}}
        <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', trans('theme::news.description'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('theme::news.content'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm required']) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('is_focus') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_focus', trans('theme::news.focus'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('is_focus', 1, (isset($news) && $news->is_focus===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::news.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('active', 1, (isset($news) && $news->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-info mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/news') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
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
@endsection