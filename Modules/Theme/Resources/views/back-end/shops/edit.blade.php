@extends('adminlte::layouts.app')
@php($typeId = (int)\Request::get('type'))
@section('htmlheader_title')
    {{ trans('theme::shops.title') }}
@endsection
@section('contentheader_title')
    {{ trans('theme::shops.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/shops') }}">{{ trans('theme::shops.title') }}</a></li>
        <li class="active">{{ __('message.edit_title') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.edit_title') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/shops') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
            </div>
        </div>

        {!! Form::model($shop, [
            'method' => 'PATCH',
            'url' => ['/shops', $shop->id],
            'class' => 'form-horizontal',
            'files' => true
        ]) !!}

        @include ('theme::back-end.shops.form')

        {!! Form::close() !!}
    </div>
@endsection
