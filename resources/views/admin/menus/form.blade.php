<div class="box-body" xmlns="">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
        <table class="table table-condensed">
            <tr class="row {{ $errors->has('title') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('title', trans('theme::menus.title'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('title', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            @if(isset($menu))
            <tr class="row {{ $errors->has('slug') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('slug', trans('theme::menus.slug'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            @endif
            <tr class="row {{ $errors->has('parent_id') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('parent_id', trans('theme::menus.parent'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::select('parent_id', $menus, null, ['class' => 'form-control select2']) !!}
                    {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('position') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('position', trans('theme::menus.position'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::select('position[]', $position, isset($position_edit) ? $position_edit : null, ['class' => 'form-control input-sm select2','multiple' => true]) !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('arrange') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('arrange', trans('theme::menus.arrange'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::number('arrange', isset($menu->arrange) ? $menu->arrange : $menu_arrange+1, ['class' => 'form-control input-sm', 'min' => 1]) !!}
                    {!! $errors->first('arrange', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('type_id') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('type_id', trans('theme::menus.type_id'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::select('type_id', $typeMenu, null, ['class' => 'form-control input-sm select2','required' => 'required']) !!}
                    {!! $errors->first('type_id', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        </table>
</div>

<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('/menus') }}" class="btn btn-secondary">{{ __('message.close') }}</a>
</div>

@section('scripts-footer')

@endsection