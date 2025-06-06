@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('steps.name') }}
@endsection
@section('contentheader_title')
{{ __('steps.name') }}
@endsection
@section('contentheader_description')

@endsection


@section('main-content')
<div class="box">
    <div class="box-header ">
        <h3 class="box-title">{{ __('message.new_add') }}</h3>
        <div class="box-tools">
            <a href="{{ url('/admin/steps') }}" class="btn btn-default"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs"> {{ __('message.lists') }}</span></a>
        </div>
    </div>
    {!! Form::open(['url' => '/admin/steps', 'class' => 'form-horizontal', 'files' => true]) !!}

    @include('admin.steps.form')

    {!! Form::close() !!}
</div>
@endsection