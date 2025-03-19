<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <table class="table ">
        <tr class="row {{ $errors->has('name_vi') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_vi', trans('services.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_vi', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_vi', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_en') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_en', trans('services.name_en'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_en', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_zh') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_zh', trans('services.name_zh'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_zh', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_zh', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_hu') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_hu', trans('services.name_hu'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_hu', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_hu', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('parent_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('parent_id', trans('services.parent_id'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('parent_id', \App\Models\Service::getSelect() ?? [], null, ['class' => 'form-control input-sm select2']) !!}
                {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_vi', trans('services.description_vi'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_vi', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_en') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_en', trans('services.description_en'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_en', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_zh') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_zh', trans('services.description_zh'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_zh', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description_hu') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description_hu', trans('services.description_hu'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description_hu', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('type') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', trans('services.type'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('type', \App\Models\Service::$TYPE ?? [], null, ['class' => 'form-control input-sm select2']) !!}
                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('arrange') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('arrange', trans('sliders.arrange'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('arrange', isset($data) ? $data->arrange : $arrange, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('arrange', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/steps') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>

