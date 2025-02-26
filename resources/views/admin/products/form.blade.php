<div class="box-body">
    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    <table class="table table-layout">
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
        <tr class="row {{ $errors->has('category_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('category_id', trans('message.categories'), ['class' => 'control-label','title'=>'Vui lòng chọn danh mục trùng với danh mục thương hiệu của sản phẩm']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('category_id', $categories ?? [], isset($categories1) ? $categories1 : null, ['class' => 'form-control input-sm  select2', 'id' => 'category']) !!}
                {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($product->image) ? 'block' : 'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($product->image) ? asset($product->image) : '' }}"
                             alt="{{ trans('theme::products.image') }}"/>
                        <i class="fa fa-trash text-danger " onclick="return deleteFile(this)"></i>

                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('price') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price', trans('theme::products.price'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('price', isset($product) && !empty($product->price) ? number_format($product->price) : null, ['class' => 'form-control input-sm ', 'id' => 'price1']) !!}
                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('description') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', trans('theme::products.meta_description'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm required', 'rows' => 5]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('theme::products.content'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm required', 'rows' => 5]) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::news.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('active', 1, isset($product) && $product->active === 1 ? true : false, ['class' => 'flat-blue', 'id' => 'active']) !!}
            </td>
        </tr>

    </table>

    <div class="box-footer">
        {!! Form::button('<i class="fa fa-check-circle"></i> ' . ($text = isset($submitButtonText) ? $submitButtonText : __('message.save')), ['class' => 'btn btn-success mr-2', 'type' => 'submit']) !!}
        <a href="{{ !empty($backUrl) ? $backUrl : url('admin/products') }}" class="btn btn-default"><i
                    class="fas fa-times"></i> {{ __('message.close') }}</a>
    </div>
    @section('scripts-footer')
        <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
        <script>CKFinder.config({connectorPath: '/ckfinder/connector'});</script>
        <script>
            CKEDITOR.replace('content', {
                filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
            });
        </script>
        <script>
            $(document).ready(function () {
                $("#multiple_files").on("change", function (e) {
                    var multiple_files = e.target.files;
                    filesLength = multiple_files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = multiple_files[i];
                        var fileReader = new FileReader();
                        fileReader.onload = (function (e) {
                            var file = e.target;
                            $("<span class=\"pip\">" +
                                "<img class=\"imageThumb\" src=\"" + e.target.result +
                                "\" title=\"" + file.name + "\"/>" +
                                "<br/><span class=\"img-delete\">Xoá</span>" +
                                "</span>").insertAfter("#multiple_files");
                            $(".img-delete").click(function () {
                                $(this).parent(".pip").remove();
                            });
                        });
                        fileReader.readAsDataURL(f);
                    }
                });
            });
        </script>
        <script>

            function changePrice(idObj) {
                idObj.addEventListener('keyup', function () {
                    var n = parseInt(this.value.replace(/\D/g, ''), 10);
                    idObj.value = Number.isNaN(n) ? 0 : n.toLocaleString('en');
                }, false);
            }

            changePrice(document.getElementById("price1"));
        </script>

        <script type="text/javascript">
            $(function () {
                $('#show-image .fa-trash').click(function () {
                    var preview = document.querySelector('img.img-preview');
                    if (confirm('Bạn muốn xóa hình ảnh này ?')) {
                        $('#image').val('').attr('required', 'required');
                        preview.src = '';
                        $('.imgprev-wrap').css('display', 'none');
                        $('.inputfile-wrap').find('input[type=text]').val('');
                    }
                })
            });
            $('#image').change(function () {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#image').files[0];
                var reader = new FileReader();

                if (/\.(jpe?g|png|gif|mp4)$/i.test(file.name)) {

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
                    $('.imgprev-wrap').find('input[type=hidden]').val('');
                }
            });
        </script>
        <script>
            $(document).ready(function () {
                $('.imgprev-wrap .fa-trash').click(function () {
                    var preview = document.querySelector('img.img-preview');

                    if (confirm('{{ __('message.confirm_delete') }}')) {
                        preview.src = '';
                        $('.imgprev-wrap').css('display', 'none');
                        $('.inputfile-wrap').find('input[type=text]').val('');
                    }
                });
            });
        </script>

@endsection
