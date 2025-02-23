<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
        <table class="table table-bordered table-condensed">
            <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('name', trans('theme::shops.name'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required'=>true]) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('address') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('name', trans('theme::shops.address'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('phone') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('name', trans('theme::shops.phone'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('phone', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('arrange') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('arrange', trans('theme::shops.arrange'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::number('arrange', isset($shop) ? $shop->arrange : $arrange+=1, ['class' => 'form-control input-sm','min' => 1]) !!}
                    {!! $errors->first('arrange', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('active', trans('theme::shops.active'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::checkbox('active', config('settings.active'),isset($shop) && $shop->active === config('settings.active') ? true : false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('shops') }}" class="btn btn-secondary">{{ __('message.close') }}</a>
</div>

