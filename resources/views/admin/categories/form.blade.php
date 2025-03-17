<div class="box-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p><i class="fa fa-fw fa-times"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif
    <table class="table table-condensed">
        <tr class="row {{ $errors->has('store_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('store_id', trans('stores.name'), ['class' => 'control-label','title'=>'Vui lòng chọn danh mục trùng với danh mục thương hiệu của sản phẩm']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('store_id', $stores ?? [], null, ['class' => 'form-control input-sm  select2', 'id' => 'category']) !!}
                {!! $errors->first('store_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_vi', trans('theme::news.title'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_vi', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_vi', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_en') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_en', trans('theme::news.title_en'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_en', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_zh') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_zh', trans('theme::news.title_zh'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_zh', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_zh', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_hu') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_hu', trans('theme::news.title_hu'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_hu', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_hu', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('parent_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('parent_id', trans('theme::categories.parent'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('parent_id', $categories, null, ['class' => 'form-control input-sm select2']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('theme::categories.avatar'), ['class' => 'control-label']) !!}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($category->avatar)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview" src="{{ !empty($category->avatar)?asset($category->avatar):'' }}" alt="{{ trans('theme::categories.avatar') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_vi', trans('theme::categories.description_vi'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_vi', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_en') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_en', trans('theme::categories.description_en'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_en', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_zh') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_zh', trans('theme::categories.description_zh'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_zh', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_hu') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_hu', trans('theme::categories.description_hu'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_hu', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::news.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('active', 1, (isset($category) && $category->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . ($text = isset($submitButtonText) ? $submitButtonText : __('message.save')), ['class' => 'btn btn-success mr-2', 'type' => 'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/categories') }}" class="btn btn-default"><i
                class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
    <script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script>

    <script type="text/javascript">
    $(function() {
        $('#image').change(function() {
            var preview = document.querySelector('img.img-preview');
            var file = document.querySelector('#image').files[0];
            var reader = new FileReader();

            if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                reader.addEventListener("load", function() {
                    preview.src = reader.result;
                    $('.imgprev-wrap').css('display', 'block');
                    $('.inputfile-wrap').find('input[type=text]').val(file.name);
                }, false);

                if (file) {
                    reader.readAsDataURL(file);
                }
            } else {
                document.querySelector('#image').value = '';
                $('.imgprev-wrap').find('input[type=hidden]').val('');
            }
        });

        $('.imgprev-wrap .fa-trash').click(function() {
            var preview = document.querySelector('img.img-preview');

            if (confirm('Bạn muốn xóa hình ảnh này không?')) {
                preview.src = '';
                $('.imgprev-wrap').css('display', 'none');
                $('.inputfile-wrap').find('input[type=text]').val('');
            }
        })
    });
</script>
@endsection