@extends('adminlte::layouts.errors')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.pagenotfound') }}
@endsection

@section('main-content')

    <div class="error-page mt-5 container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-yellow text-center f-100"> 404</h2>
            </div>
        </div>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! {{ trans('adminlte_lang::message.pagenotfound') }}.</h3>
            <p>
                {{ trans('adminlte_lang::message.notfindpage') }}
                {{-- {{ trans('adminlte_lang::message.mainwhile') }} <a href='{{ url('/home') }}' class="btn btn-danger">{{ trans('adminlte_lang::message.returndashboard') }}</a> {{ trans('adminlte_lang::message.usingsearch') }} --}}
            </p>
            <a href="/" class="btn btn-primary">Về trang chủ</a>
            {{-- <form class='search-form'>
                <div class='input-group'>
                    <input type="text" name="search" class='form-control' placeholder="{{ trans('adminlte_lang::message.search') }}"/>
                    <div class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                    </div>
                </div><!-- /.input-group -->
            </form> --}}
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
@endsection