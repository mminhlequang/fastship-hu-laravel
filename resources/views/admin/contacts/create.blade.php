@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('Liên hệ') }}
@endsection
@section('contentheader_title')
{{ __('Liên hệ') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('admin/commits') }}">{{ __('province.province-list') }}</a></li>
    <li class="active">{{ __('message.new_add') }}</li>
</ol>
@endsection

@section('main-content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('message.new_add') }}</h3>
        <div class="box-tools">
            <a href="{{ url('/admin/commits') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
        </div>
    </div>

    {!! Form::open(['url' => 'admin/commits', 'class' => 'form-horizontal', 'files' => true]) !!}

    @include('admin.contacts.form')

    {!! Form::close() !!}
</div>
@endsection