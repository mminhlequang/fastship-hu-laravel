@extends('adminlte::layouts.app')
@section('style')
    @toastr_css
@endsection
@section('htmlheader_title')
    {{ __('drivers.title') }}
@endsection
@section('contentheader_title')
    {{ __('drivers.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/drivers') }}" title="{{ __('message.lists') }}"
                   class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span
                            class="hidden-xs"> {{ __('message.lists') }}</span></a>
                @can('CustomerController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/drivers', $customer->id],
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
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>{{ __('drivers.name') }}</th>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('drivers.phone') }}</th>
                    <td>{{ $customer->phone }}</td>
                </tr>
                </tbody>
            </table>
            <hr>
            <div>
                <b><u>Thủ tục hồ sơ</u></b>
            </div>
            {!! Form::model($customer, [
                 'method' => 'PATCH',
                 'url' => ['admin/drivers', $customer->id],
                 'class' => 'form-horizontal',
                 'files' => true
             ]) !!}
            <div class="row {{ $errors->has('step_id') ? 'has-error' : '' }}">
                <div class="col-md-4 col-lg-3">
                    {!! Form::label('step_id', trans('Confirm step'), ['class' => 'control-label','title'=>'Vui lòng chọn danh mục trùng với danh mục thương hiệu của sản phẩm']) !!}
                </div>
                <div class="col-md-8 col-lg-9">
                    {!! Form::select('step_id', $steps ?? [], $customer->step_id ?? null, ['class' => 'form-control input-sm  select2', 'id' => 'category']) !!}
                    {!! $errors->first('step_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            @forelse($stepsX as $keyS => $itemS)
                @foreach($customer->steps as $keyS => $itemS)
                    <div>
                        <button data-toggle="collapse" class="btn btn-info" type="button" data-toggle="collapse"
                                data-target="#collapseExample-{{ $keyS }}" aria-expanded="false"
                                aria-controls="collapseExample">
                            {{ $itemS->name }}&nbsp;
                            @if($itemS->id == 1)
                                <i class="fas fa-caret-square-down"></i>
                            @endif
                        </button>
                        <div class="card card-body">
                            <table class="table table-striped">
                                <tr class="row {{ $errors->has('comment') ? 'has-error' : ''}}">
                                    <td class="col-md-4 col-lg-3">
                                        {!! Form::label('comment', __('steps.comment'), ['class' => 'control-label label-required']) !!}
                                    </td>
                                    <input type="hidden" name="data[{{$keyS}}][id]" value="{{ $itemS->pivot->id }}">
                                    <td class="col-md-8 col-lg-9">
                                        {!! Form::textarea("data[$keyS][comment]", $itemS->pivot->comment ?? null, ['class' => 'form-control input-sm']) !!}
                                    </td>
                                </tr>
                                <tr class="row {{ $errors->has('link') ? 'has-error' : ''}}">
                                    <td class="col-md-4 col-lg-3">
                                        {!! Form::label('link', __('steps.link'), ['class' => 'control-label label-required']) !!}
                                    </td>
                                    <td class="col-md-8 col-lg-9">
                                        {!! Form::text("data[$keyS][link]", $itemS->pivot->link ??null, ['class' => 'form-control input-sm']) !!}
                                        {!! $errors->first('link', '<p class="help-block">:message</p>') !!}
                                    </td>
                                </tr>
                                <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
                                    <td class="col-md-4 col-lg-3">
                                        {!! Form::label('image', trans('stores.image'), ['class' => 'control-label']) !!}
                                    </td>
                                    <td class="col-md-8 col-lg-9">
                                        <div>
                                            <div class="input-group-btn">
                                                {!! Form::file("data[$keyS][image]", array_merge(['class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                                            </div>
                                            &nbsp;
                                            <div>
                                                @if($itemS->pivot->image != null)
                                                    <img src="{{ url($itemS->pivot->image) }}" width="100" height="100">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="row {{ $errors->has('status') ? 'has-error' : ''}}">
                                    <td class="col-md-4 col-lg-3">
                                        {!! Form::label('status', trans('steps.status'), ['class' => 'control-label  label-required']) !!}
                                    </td>
                                    <td class="col-md-8 col-lg-9">
                                        {!! Form::select("data[$keyS][status]", \App\Models\Step::$STATUS, $itemS->pivot->status ?? null, ['class' => 'form-control input-sm select2']) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if($itemS->id == 1)
                        <div class="collapse" id="collapseExample-{{ $keyS }}">
                            <div class="card card-body">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <th>{{ __('drivers.name') }}</th>
                                        <td>{{ optional($customer->profile)->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.sex') }}</th>
                                        <td>{{ (optional($customer->profile)->sex == 1) ? 'Male': 'Female' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.birthday') }}</th>
                                        <td>{{ optional($customer->profile)->birthday }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.address') }}</th>
                                        <td>{{ optional($customer->profile)->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.cccd') }}</th>
                                        <td>{{ optional($customer->profile)->cccd }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.cccd_date') }}</th>
                                        <td>{{ optional($customer->profile)->cccd_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.image_cccd_before') }}</th>
                                        <td>
                                            @if(optional($customer->profile)->image_cccd_before != null)
                                                <a href="{{ optional($customer->profile)->image_cccd_before }}"
                                                   target="_blank">
                                                    <img src="{{ optional($customer->profile)->image_cccd_before }}"
                                                         width="100" height="100">
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.image_cccd_after') }}</th>
                                        <td>
                                            @if(optional($customer->profile)->image_cccd_after != null)
                                                <a href="{{ optional($customer->profile)->image_cccd_after }}"
                                                   target="_blank">
                                                    <img src="{{ optional($customer->profile)->image_cccd_after }}"
                                                         width="100" height="100">
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.address_temp') }}</th>
                                        <td>{{ optional($customer->profile)->address_temp }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.tax_code') }}</th>
                                        <td>{{ optional($customer->profile)->tax_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.payment_method') }}</th>
                                        <td>{{ optional($customer->profile)->payment_method }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.card_number') }}</th>
                                        <td>{{ optional($customer->profile)->card_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.card_expires') }}</th>
                                        <td>{{ optional($customer->profile)->card_expires }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.card_cvv') }}</th>
                                        <td>{{ optional($customer->profile)->card_cvv }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.car_id') }}</th>
                                        <td>{{ optional($customer->profile)->car_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.license') }}</th>
                                        <td>{{ optional($customer->profile)->license }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.image_license_after') }}</th>
                                        <td>
                                            @if(optional($customer->profile)->image_license_after != null)
                                                <a href="{{ optional($customer->profile)->image_license_after }}"
                                                   target="_blank">
                                                    <img src="{{ optional($customer->profile)->image_license_after }}"
                                                         width="100" height="100">
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('drivers.image_license_before') }}</th>
                                        <td>
                                            @if(optional($customer->profile)->image_license_before != null)
                                                <a href="{{ optional($customer->profile)->image_license_before }}"
                                                   target="_blank">
                                                    <img src="{{ optional($customer->profile)->image_license_before }}"
                                                         width="100" height="100">
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            @empty
                <div class="text-center">{{ __("No data") }}</div>
            @endforelse
            <div class="box-footer">
                {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
                <a href="{{ !empty($backUrl) ? $backUrl : url('admin/drivers') }}" class="btn btn-default"><i
                            class="fas fa-times"></i> {{ __('message.close') }}</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
@endsection