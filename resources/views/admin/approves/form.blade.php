<div class="box-body">
    
    <table class="table  table-condensed">
        <tr class="row {{ $errors->has('name_vi') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_vi', trans('theme::approves.name_vi'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_vi', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_vi', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_en') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_en', trans('theme::approves.name_en'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_en', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_zh') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_zh', trans('theme::approves.name_zh'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_zh', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_zh', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name_hu') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name_hu', trans('theme::approves.name_hu'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name_hu', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name_hu', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('number') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('number', trans('theme::approves.number'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('number', isset($approve) ? $approve->number : $number++, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('color') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('color', trans('theme::approves.color'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::color('color',isset($approve) ? $approve->color : '#ff0000',['class' => 'form-control', 'id' => 'color']) !!}
                {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary', 'style' => 'margin-right: 5px']) !!}
    <a href="{{ url('/admin/approves') }}" class="btn btn-secondary">{{ __('message.close') }}</a>
</div>