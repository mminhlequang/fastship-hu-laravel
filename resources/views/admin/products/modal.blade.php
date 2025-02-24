<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Tạo mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'url' => '', 'role' => 'newsletter', 'id' => 'newsletter']) !!}
                <table class="table table-bordered table-condensed">
        <tr class="row {{ $errors->has('name_vi') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_vi', trans('theme::products.meta_title_vi'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_vi', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_vi', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @if (isset($products))
            <tr class="row {{ $errors->has('slug_vi') ? 'has-error' : '' }}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('slug_vi', trans('theme::news.slug'), ['class' => 'control-label']) !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug_vi', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('slug_vi', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>

        @endif
        <tr class="row {{ $errors->has('name_en') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title_en', trans('theme::products.meta_title_en'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_en', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @if (isset($news))
            <tr class="row {{ $errors->has('slug_en') ? 'has-error' : '' }}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('slug_en', trans('theme::news.slug'), ['class' => 'control-label']) !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug_en', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('slug_en', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>

        @endif
      

   
     

        <tr class="row {{ $errors->has('image') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('theme::products.image'), ['class' => 'control-label']) !!}
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
                            {!! Form::file('image', array_merge(['id' => 'image', 'class' => 'form-control input-sm', 'accept' => 'image/*'])) !!}
                        </div>
                    </div>
                    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    <div class="clearfix"></div>
                    <div class="imgprev-wrap" style="display:{{ !empty($product->image) ? 'block' : 'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview" src="{{ !empty($product->image) ? asset($product->image) : '' }}"
                            alt="{{ trans('theme::products.image') }}" />
                        <i class="fa fa-trash text-danger"></i>
                        <i class="fa fa-trash text-danger" onclick="return deleteFile(this)"></i>

                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('keywords') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('keywords', trans('theme::products.keywords'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('keywords', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('keywords', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_vi') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_vi', trans('theme::products.meta_description'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_vi', null, ['class' => 'form-control input-sm required', 'rows' => 5]) !!}
                {!! $errors->first('description_vi', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_en') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_en', trans('theme::products.meta_description_en'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_en', null, ['class' => 'form-control input-sm required', 'rows' => 5]) !!}
                {!! $errors->first('description_en', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        {{-- <tr class="row {{ $errors->has('content') ? 'has-error' : ''}}">
        <td class="col-md-4 col-lg-3">
            {!! Form::label('content', trans('theme::products.content'), ['class' => 'control-label'])
            !!}
        </td>
        <td class="col-md-8 col-lg-9 form-content">
            {!! Form::textarea('content', null, ['id' => 'text', 'class' => 'form-control input-sm required']) !!}
            {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
        </td>
        </tr> --}}
        {{-- Giá sản phẩm --}}
        <tr class="row {{ $errors->has('price') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price', trans('theme::products.price'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('price', isset($product) && !empty($product->price) ? number_format($product->price) : null, ['class' => 'form-control input-sm ', 'required' => 'required', 'id' => 'price']) !!}
                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        {{-- Size sản phẩm --}}
        <tr class="row {{ $errors->has('price') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('size', trans('theme::products.size'), ['class' => 'control-label']) !!}
                <br>
                <small class="text-red">{{ trans('theme::products.note') }}</small>
                {{ trans('theme::products.note_title') }}
            </td>
            <td class="col-md-8 col-lg-9">
                <table class="table " id="dynamicTable" style="background:transparent !important">
                    <tr>
                        <th>{{ trans('theme::products.size') }}</th>
                        <th>{{ trans('theme::products.price_size') }}</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="sizes[name][]" placeholder="Enter name size" class="form-control">
                        </td>
                        <td><input type="text" name="sizes[price_size][]" placeholder="Enter your Price"
                                class="form-control"></td>
                        <td><button type="button" name="add" id="add"
                                class="btn btn-success">{{ trans('theme::products.addmore') }}</button></td>
                    </tr>
                    @if (!empty($product->sizes))
                        @foreach ($product->sizes as $file)
                            <tr class="tr-add-{{ $file->id }}">
                                <td>
                                    {!! Form::text('sizes[name][]', isset($product->sizes) ? $file->name : [], ['class' => 'form-control input-sm', 'placeholder' => trans('theme::products.size')]) !!}
                                    {!! $errors->first('sizes[name][]', '<p class="help-block">:message</p>') !!}

                                </td>
                                <td>
                                    {!! Form::hidden('sizes[id][]', isset($product->sizes) ? $file->id : [], ['class' => 'form-control input-sm', 'placeholder' => trans('theme::products.price_size')]) !!}
                                    {!! Form::text('sizes[price_size][]', isset($product->sizes) ? $file->price_size : [], ['class' => 'form-control input-sm', 'placeholder' => trans('theme::products.price_size')]) !!}
                                    {!! $errors->first('sizes[price_size][]', '<p class="help-block">:message</p>') !!}

                                </td>

                                <td><button type="button" class="btn btn-danger remove-tr"
                                        data-id="{{ $file->id }}">{{ trans('theme::products.remove') }}</button>
                                </td>
                        @endforeach

                    @endif
        </tr>
    </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="sumit" class="btn btn-primary" id="saveBtn">Lưu</button>
                </div>
                {!! Form::close() !!}

            </div>
            <span class="text-danger alert-mail"></span>

        </div>
    </div>
</div>