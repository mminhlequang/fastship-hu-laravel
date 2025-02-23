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
                    {!! Form::file('profile[avatar]', null) !!}
                    @php
                    if(isset($submitButtonText))
                    echo $user->showAvatar();
                    @endphp
                    {!! $errors->first('profile.avatar', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            <td>{{ __('message.user.email') }} <span class="label-required"></span></td>
            <td>
                <div class="{{ $errors->has('email') ? ' has-error' : ''}}">
                    {!! Form::email('email', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.gender') }}</td>
            <td>
                <div class="{{ $errors->has('profile.gender') ? ' has-error' : ''}}">
                    <label for="boy">
                        {!! Form::radio('profile[gender]', 1, isset($submitButtonText)?null:true, ['class' => 'flat-blue', 'id' => 'boy']) !!}
                        {{ __('message.user.gender_male') }}
                    </label>&nbsp;
                    <label for="girl">
                        {!! Form::radio('profile[gender]', 0, null, ['class' => 'flat-blue', 'id' => 'girl']) !!}
                        {{ __('message.user.gender_female') }}
                    </label>
                    {!! $errors->first('profile.gender', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            <td>{{ __('message.user.username') }} <span class="label-required"></span></td>
            <td>
                @php
                $readonly = [];
                if(isset($isProfile) && $isProfile){
                $readonly = ['readonly' => 'readonly'];
                }
                @endphp
                <div class="{{ $errors->has('username') ? ' has-error' : ''}}">
                    {!! Form::text('username', null, array_merge(['class' => 'form-control input-sm', 'required' => 'required'], $readonly)) !!}
                    {!! $errors->first('username', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.phone') }}</td>
            <td>
                <div class="{{ $errors->has('profile.phone') ? ' has-error' : ''}}">
                    {!! Form::text('profile[phone]', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('profile.phone', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            @php
            $required = [];
            if(!isset($submitButtonText)){
            $required = ['required' => 'required'];
            }
            @endphp
            @if(isset($isProfile) && $isProfile)
            <td>{{ __('message.user.password_current') }}</td>
            <td>
                <div class="{{ $errors->has('password_current') ? ' has-error' : ''}}">
                    {!! Form::password('password_current', ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('password_current', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            @else
            <td>{{ isset($isProfile) && $isProfile?__('message.user.password_new'):__('message.user.password') }} <span class="{{$required?'label-required':''}}"></span></td>
            <td>
                <div class="{{ $errors->has('password') ? ' has-error' : ''}}">
                    {!! Form::password('password', array_merge(['class' => 'form-control input-sm'], $required)) !!}
                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            @endif
            <td>{{ __('message.user.address') }}</td>
            <td>
                <div class="{{ $errors->has('profile.address') ? ' has-error' : ''}}">
                    {!! Form::text('profile[address]', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('profile.address', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            <td>{{ isset($isProfile) && $isProfile?__('message.user.password_new_confirmation'):__('message.user.password_confirmation') }} <span class="{{$required?'label-required':''}}"></span></td>
            <td>
                <div class="{{ $errors->has('password_confirmation') ? ' has-error' : ''}}">
                    {!! Form::password('password_confirmation', array_merge(['class' => 'form-control input-sm'], $required)) !!}
                    {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
            <td>{{ __('message.user.birthday') }}</td>
            <td>
                <div class="{{ $errors->has('profile.birthday') ? ' has-error' : ''}}">
                    {!! Form::text('profile[birthday]', null, ['class' => 'form-control input-sm datepicker']) !!}
                    {!! $errors->first('profile.birthday', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr>
            @if(!isset($isProfile) || !$isProfile)
            <td>{{ __('message.user.role') }} </td>
            <td>
                <div class="{{ $errors->has('roles') ? 'has-error' : '' }}">
                    {!! Form::select('roles[]', $roles, isset($user_roles) ? $user_roles : [], ['class' => 'form-control input-sm select2', 'required'=>'required']) !!}
                </div>
            </td>
            @endif
            <td>{{ __('message.user.position') }}</td>
            <td>
                <div class="{{ $errors->has('profile.position') ? ' has-error' : ''}}">
                    {!! Form::text('profile[position]', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('profile.position', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        @if(!isset($isProfile) || !$isProfile)
        <tr>
            <td style="border-right-width: 0"></td>
            <td colspan="3" style="border-left-width: 0">
                <div class="{{ $errors->has('active') ? ' has-error' : ''}}">
                    <label>
                        {!! Form::checkbox('active', Config("settings.active") , isset($submitButtonText)?null:true ,['class' => 'flat-blue']) !!}
                        {{ __('message.user.active') }}
                    </label>
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        @endif
    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-info mr-2', 'type'=>'submit']) !!}
    @if(!isset($isProfile) || !$isProfile)
    <a href="{{ url('/admin/users') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
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