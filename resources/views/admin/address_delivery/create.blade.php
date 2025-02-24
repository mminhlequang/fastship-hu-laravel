@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('theme::products.product') }}
@endsection
@section('contentheader_title')
@endsection
@section('contentheader_description')

@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/admin/address_delivery') }}">{{ __('facility.list-fcl') }}</a></li>
    <li class="active">{{ __('message.new_add') }}</li>
</ol>
@endsection

@section('main-content')
<div class="box">
    <div class="box-header ">
        <h3 class="box-title">{{ __('message.new_add') }}</h3>
        <div class="box-tools">
            <a href="{{ url('/admin/address_delivery') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
        </div>
    </div>

    {!! Form::open(['url' => 'admin/address_delivery','method' => 'POST', 'class' => 'form-horizontal', 'files' => true,'multiple' => true]) !!}

    @include('admin.address_delivery.form')

    {!! Form::close() !!}
</div>
{{-- @include('admin.facilities.modal') --}}

@endsection