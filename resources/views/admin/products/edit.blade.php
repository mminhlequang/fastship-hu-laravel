@extends('adminlte::layouts.app')

@section('htmlheader_title')
{{ __('theme::products.product') }}
@endsection
@section('contentheader_title')
{{ __('theme::products.product') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/admin/products') }}">{{ __('theme::products.product') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection

@section('main-content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('message.edit_title') }}</h3>
        <div class="box-tools">
            <a href="{{ !empty($backUrl) ? $backUrl : url('/admin/products') }}" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs"> {{ __('message.lists') }}</span></a>
        </div>
    </div>

    {!! Form::model($product, [
    'method' => 'PATCH',
    'url' => ['/admin/products', $product->id],
    'class' => 'form-horizontal',
    'files' => true,
    'enctype'=>"multipart/form-data"
    ]) !!}

    @include ('admin.products.form', ['submitButtonText' => __('message.update')])

    {!! Form::close() !!}
</div>
@endsection