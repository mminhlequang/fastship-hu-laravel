<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <table class="table table-condensed">
        <tr class="row {{ $errors->has('user_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('user_id', trans('transactions.user_id'), ['class' => 'control-label  label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('user_id', $customers, null, ['class' => 'form-control input-sm select2', 'required' => 'required', 'id' => 'city_id']) !!}
                {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('type') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', trans('transactions.type'), ['class' => 'control-label  label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('type', \App\Models\Transaction::$TYPE, null, ['class' => 'form-control input-sm select2', 'required' => 'required', 'id' => 'city_id']) !!}
                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('price') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price', trans('transactions.price'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::text('price', isset($data) && !empty($data->price) ? number_format($data->price) : null, ['class' => 'form-control input-sm required', 'required' => 'required', 'id' => 'inputPrice']) !!}
                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', trans('transactions.description'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm required']) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('status') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('status', trans('transactions.status'), ['class' => 'control-label  label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('status', \App\Models\Transaction::$STATUS, null, ['class' => 'form-control input-sm select2', 'required' => 'required', 'id' => 'city_id']) !!}
                {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

    </table>

</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/transactions') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript">
        function changePrice(idObj) {
            idObj.addEventListener('keyup', function() {
                var n = parseInt(this.value.replace(/\D/g, ''), 10);
                idObj.value = Number.isNaN(n) ? 0 : n.toLocaleString('en');
            }, false);
        }
        changePrice(document.getElementById("inputPrice"));
    </script>
@endsection