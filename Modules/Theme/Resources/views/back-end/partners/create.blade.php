@extends('adminlte::layouts.app')
@php($typeId = (int)\Request::get('type'))
@section('htmlheader_title')
    {{ trans('theme::partners.title') }}
@endsection
@section('contentheader_title')
    {{ trans('theme::partners.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/partners' ) }}">{{ trans('theme::partners.title') }}</a></li>
        <li class="active">{{ __('message.new_add') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.new_add') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/partners') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
            </div>
        </div>

        {!! Form::open(['url' => '/partners', 'class' => 'form-horizontal', 'files' => true]) !!}

        @include('theme::back-end.partners.form')

        {!! Form::close() !!}
    </div>
@endsection
