@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('Định nghĩa keyword') }}
@endsection
@section('contentheader_title')
    {{ __('Định nghĩa từ khóa') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('Định nghĩa từ khóa') }}</li>
    </ol>
@endsection
@section('main-content')
    <div id="alert"></div>
    <div class="box">
        <ul class="nav nav-tabs" id="nav-tabs-settings" role="tablist">
            @for($i = 0;$i < count($tabs);$i++)
                <li role="presentation" class="{{ $i == 0 ? 'active' : ''}}">
                    <a href="#{{$tabs[$i]}}" aria-controls="{{$tabs[$i]}}" role="tab" data-toggle="tab">
                        {{ __('Từ khóa ') . $tabs[$i] }}
                    </a>
                </li>
            @endfor
        </ul>
        {!! Form::open([
            'method' => 'PATCH',
            'url' => ['admin/define-keywords'],
            'class' => 'form-horizontal',
            'files' => true,
            'id' => 'define_keywords'
        ]) !!}
        <div class="tab-content">
            @for($i = 0;$i < count($tabs);$i++)
                <div role="tabpanel" class="tab-pane {{$i == 0 ? 'active' : ''}}" id="{{$tabs[$i]}}">
                    <div class="box-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        @foreach ($data[$tabs[$i]] as $item)
                            <div class="form-group {{ $errors->has('value') ? 'has-error' : ''}}">
                                {!! Form::label('key', $item['key'], ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-6">
                                    @if($item['type'] == 'number')
                                        {!! Form::number($item['key'], $item['value'], ['class' => 'form-control input-sm', 'id' => $item['key']]) !!}
                                    @else
                                        {!! Form::text($item['key'], $item['value'], ['class' => 'form-control input-sm', 'id' => $item['key']]) !!}
                                    @endif
                                    {!! $errors->first($item['key'], '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
            <div class="box-footer">
                {!! Form::submit(__('message.update'), ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
