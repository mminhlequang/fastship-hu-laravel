<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
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
        <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('toppings.name'), ['class' => 'control-label label-required']) !!}
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
                        <img class="img-preview" src="{{ !empty($data->image)?asset($data->image):'' }}"
                             alt="{{ trans('toppings.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('price') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price', trans('toppings.price'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('price', isset($data) ? number_format($data->price) : 0, ['class' => 'form-control input-sm', 'required' => 'required', 'id' => 'price']) !!}
                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('status') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('status', trans('stores.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('status',  1, (isset($data) && $data->status===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>

</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/toppings') }}" class="btn btn-default"><i
                class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript">
        function changePrice(idObj) {
            idObj.addEventListener('keyup', function () {
                var n = parseInt(this.value.replace(/\D/g, ''), 10);
                idObj.value = Number.isNaN(n) ? 0 : n.toLocaleString('en');
            }, false);
        }

        changePrice(document.getElementById("price"));
        $(function () {
            $('#image').change(function () {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#image').files[0];
                var reader = new FileReader();

                if (/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {

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

            $('.imgprev-wrap .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview');

                if (confirm('{{ __('message.confirm_delete') }}')) {
                    preview.src = '';
                    $('.imgprev-wrap').css('display', 'none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        });
    </script>

@endsection