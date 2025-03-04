<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <table class="table ">
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('steps.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('steps.content'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content_pending') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content_pending', trans('steps.content_pending'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content_pending', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
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
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
    <script>CKFinder.config({ connectorPath: '/ckfinder/connector' });</script>
    <script>
        CKEDITOR.replace('content');
        CKEDITOR.replace('content_pending');
    </script>
@endsection
