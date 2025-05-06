@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('theme::products.product') }}
@endsection
@section('contentheader_title')
    {{ __('theme::products.product') }}
@endsection

@section('contentheader_description')

@endsection
@section('css')
    <style>
        .gallery {
            position: relative;
            display: block;
            overflow: hidden;
            padding-bottom: 60%;
            margin-bottom: 10px;
        }

        span.label.label-success.mr-5 {
            margin-right: 5rem !important;
        }

        .gallery img {
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
        }
    </style>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/products') }}" title="{{ __('message.lists') }}"
                   class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span
                            class="hidden-xs"> {{ __('message.lists') }}</span></a>
                @can('ProductController@update')
                    <a href="{{ url('/admin/products/' . $product->id . '/edit') }}"
                       class="btn btn-default btn-sm mr-1"><i class="far fa-edit"></i> <span
                                class="hidden-xs"> {{ __('message.edit') }}</span></a>
                @endcan
                @can('ProductController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/products', $product->id],
                        'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="far fa-trash-alt"></i> <span class="hidden-xs"> '. __('message.delete') .'</span>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-default btn-sm',
                            'title' => 'Xoá',
                            'onclick'=>'return confirm("'. __('message.confirm_delete') .'?")'
                    ))!!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        <table class="table table-striped">
            <tbody>
            <tr>
                <th> {{ trans('theme::products.name') }} </th>
                <td> {{ $product->name }} </td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.image') }} </th>
                <td>
                    @if(!empty($product->image))
                        <img width="100" height="100" src="{{ asset($product->image) }}"
                             alt="{{ $product->getNameByLocale() }}"/>
                    @endif
                </td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.category') }} </th>
                <td> {{ optional($product->category)->getNameByLocale() }} </td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.description') }} </th>
                <td>{!! str_limit($product->{'description'}, 240) !!}</td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.content') }} </th>
                <td>{!! str_limit($product->{'content'}, 2000) !!}</td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.creator') }} </th>
                <td> {{ optional($product->creator)->name }} </td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.created_at') }} </th>
                <td> {{ Carbon\Carbon::parse($product->created_at)->format('d/m/Y H:i') }} </td>
            </tr>
            <tr>
                <th> {{ trans('theme::products.updated_at') }} </th>
                <td> {{ Carbon\Carbon::parse($product->updated_at)->format('d/m/Y H:i') }} </td>
            </tr>

            </tbody>
        </table>
    </div>
@endsection
