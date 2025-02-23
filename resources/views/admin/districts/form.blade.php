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
        <tr class="row {{ $errors->has('province_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('province_id', __('Tỉnh'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('province_id', $provinces, null, ['class' => 'form-control input-sm select2', 'required' => 'required']) !!}
            </td>
        </tr>

    </table>

</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary'])
    !!}
    &nbsp
    <a href="{{ url('admin/districts') }}" class="btn btn-secondary">{{ __('message.close') }}</a>
</div>
