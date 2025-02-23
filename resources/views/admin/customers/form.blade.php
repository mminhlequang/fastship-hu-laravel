<div class="box-body">
    <table class="table table-striped table-bordered">
        <tr>
            <td>{{ __('message.user.name') }} <span class="label-required"></span></td>
            <td>
                <div class="{{ $errors->has('name') ? ' has-error' : ''}}">
                    {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.avatar') }}</td>
            <td>
                <div class="{{ $errors->has('profile.avatar') ? ' has-error' : ''}}">
                    {!! Form::file('avatar', null) !!}
                    @php
                    if(isset($customer))
                    echo App\Models\Customer::showAvatar($customer->avatar);
                    @endphp
                    {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            <td>{{ __('message.user.email') }} <span class="label-required"></span></td>
            <td>
                <div class="{{ $errors->has('email') ? ' has-error' : ''}}">
                    {!! Form::email('email', null, ['class' => 'form-control input-sm', ]) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.gender') }}</td>
            <td>
                <div class="{{ $errors->has('profile.gender') ? ' has-error' : ''}}">
                    <label for="boy">
                        {!! Form::radio('sex', 1, isset($submitButtonText)?null:true, ['class' => 'flat-blue', 'id' => 'boy']) !!}
                        {{ __('message.user.gender_male') }}
                    </label>&nbsp;
                    <label for="girl">
                        {!! Form::radio('sex', 0, null, ['class' => 'flat-blue', 'id' => 'girl']) !!}
                        {{ __('message.user.gender_female') }}
                    </label>
                    {!! $errors->first('profile.gender', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            <td>{{ __('message.user.phone') }}</td>
            <td>
                <div class="{{ $errors->has('phone') ? ' has-error' : ''}}">
                    {!! Form::text('phone', null, ['class' => 'form-control input-sm','required' => 'required']) !!}
                    {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.active') }}</td>
            <td  style="border-left-width: 0">
                <div class="{{ $errors->has('active') ? ' has-error' : ''}}">
                    <label>
                        {!! Form::checkbox('active', Config("settings.active") , isset($submitButtonText)?null:true ,['class' => 'flat-blue']) !!}
                        
                    </label>
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            
            <td>{{ __('message.user.address') }}</td>
            <td>
                <div class="{{ $errors->has('profile.address') ? ' has-error' : ''}}">
                    {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.birthday') }}</td>
            <td>
                <div class="{{ $errors->has('profile.birthday') ? ' has-error' : ''}}">
                    {!! Form::text('birthday', isset($birthday) ? $birthday : null, ['class' => 'form-control input-sm datepicker']) !!}
                    {!! $errors->first('birthday', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>

    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-info mr-2', 'type'=>'submit']) !!}
    @if(!isset($isProfile) || !$isProfile)
    <a href="{{ url('/admin/customers') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
    @endif
</div>
@section('scripts-footer')
<script>
    $(function() {
        $('.datepicker').datepicker({
            autoclose: true,
            language: '{{ app()->getLocale() }}',
            format: 'dd/mm/yyyy',
            todayHighlight: true,
        });
    })
</script>
@endsection