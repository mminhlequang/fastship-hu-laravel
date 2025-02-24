@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('facility.list-fcl') }}
@endsection
@section('contentheader_title')
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/admin/address_delivery') }}">    {{ __('facility.list-fcl') }}
    </a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection

@section('main-content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('message.edit_title') }}</h3>
        <div class="box-tools">
            <a href="{{ !empty($backUrl) ? $backUrl : url('/admin/address_delivery') }}" class="btn btn-warning btn-sm"><i
                    class="fa fa-arrow-left" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.lists') }}</span></a>
        </div>
    </div>

    {!! Form::model($address_delivery , [
        'method' => 'PATCH',
        'url' => ['/admin/address_delivery', $address_delivery->id],
        'class' => 'form-horizontal',
        'files' => true
    ]) !!}

    @include ('admin.address_delivery.form', ['submitButtonText' => __('message.update')])

    {!! Form::close() !!}
</div>
{{-- @include('admin.facilities.modal') --}}
@endsection
