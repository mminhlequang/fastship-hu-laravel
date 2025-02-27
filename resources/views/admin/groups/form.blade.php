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
        <tr class="row {{ $errors->has('name_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_vi', trans('groups.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_vi', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_vi', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_en') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_en', trans('groups.name_en'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_en', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_zh') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_zh', trans('groups.name_zh'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_zh', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_zh', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_hu') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_hu', trans('groups.name_hu'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_hu', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name_hu', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('topping_ids') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('topping_ids', trans('groups.topping_ids'), ['class' => 'control-label','title'=>'Vui lòng chọn danh mục trùng với danh mục thương hiệu của sản phẩm']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('topping_ids', $toppings ?? [], isset($data) ? $data->toppings : null, ['class' => 'form-control input-sm  select2', 'multiple' => true]) !!}
                {!! $errors->first('topping_ids', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('status') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('status', trans('groups.active'), ['class' => 'control-label']) !!}
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/groups') }}" class="btn btn-default"><i
                class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
