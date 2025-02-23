@extends('adminlte::layouts.auth')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.login') }}
@endsection
@section('css')

@endsection
@section('content')

<body class="hold-transition login-page">
    <div id="app" class="login-box">
        <div class="login-logo border-bottom py-4">
            <img src="{{ \DB::table('settings')->where('key', 'company_logo')->value('value') ?? asset('images/logoFB.png') }}"  alt="logo">
        </div>

        @if(count($errors) > 0)
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span> {{ $errors->first() }}</span>
        </div>
        @endif

        <div class="login-box-body">
            <form class="general-form" action="{{ url('/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    @php
                    $type = config('auth.providers.users.field','email') == "email"? "email": "text";
                    @endphp
                    <input type="{{ $type }}" class="form-control" placeholder="{{ trans('adminlte_lang::message.username') }}" name="{{ config('auth.providers.users.field','email') }}" />
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="{{ trans('adminlte_lang::message.password') }}" name="password" />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="checkbox icheck">
                    <label>
                        <input style="display:none;" type="checkbox" name="remember"> {{ trans('adminlte_lang::message.remember') }}
                    </label>
                </div>
                <button type="submit" class="btn btn-login btn-lg btn-block my-4">{{ trans('adminlte_lang::message.buttonsign') }}</button>
            </form>

            {{-- <a href="{{ url('/password/reset') }}">{{ trans('adminlte_lang::message.forgotpassword') }}</a>
            <p>{{ trans('adminlte_lang::message.youdonthaveanaccount') }} <a href="{{ url('/register') }}" class="text-center">{{ trans('adminlte_lang::message.register') }}</a></p> --}}
        </div>
    </div>

    @include('adminlte::layouts.partials.scripts_auth')

    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
    </script>
</body>

@endsection