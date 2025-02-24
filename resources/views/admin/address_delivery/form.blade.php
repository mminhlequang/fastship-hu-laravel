<style>
    .quote-imgs-thumbs {
        background: #eee;
        border: 1px solid #ccc;
        border-radius: 0.25rem;
        margin: 1.5rem 0;
        padding: 0.75rem;
    }
       td.col-md-4.col-lg-3 i.fa.fa-plus.btn.btn-success {
            float: right;
        }
    .quote-imgs-thumbs--hidden {
        display: none;
    }

    .img-preview-thumb {
        background: #fff;
        border: 1px solid #777;
        border-radius: 0.25rem;
        box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
        margin-right: 1rem;
        max-width: 100px;
        max-height: 100px;
        padding: 0.25rem;
    }

    .quote-imgs-thumbs {
        background: #eee;
        border: 1px solid #ccc;
        border-radius: 0.25rem;
        margin: 1.5rem 0;
        padding: 0.75rem;
    }

    .quote-imgs-thumbs--hidden {
        display: none;
    }

    .img-preview-thumb {
        background: #fff;
        border: 1px solid #777;
        border-radius: 0.25rem;
        box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
        margin-right: 1rem;
        max-width: 140px;
        padding: 0.25rem;
    }

</style>
<div class="box-body">

    
    <table class="table table-condensed">
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('address_delivery.name1'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('phone') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('phone', trans('address_delivery.phone'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('phone', null, ['class' => 'form-control input-sm',]) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('province_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('province_id', trans('address_delivery.province'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('province_id', $provinces, null, ['class' => 'form-control input-sm select2', 'id' => 'province']) !!}
                {!! $errors->first('province_id', '<p class="help-block">:message</p>') !!}

            </td>
        </tr>
        <tr class="row {{ $errors->has('district_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('district_id', trans('address_delivery.districts'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('district_id', $district ?? [], null, ['class' => 'form-control input-sm select2', 'id' => 'district']) !!}
                {!! $errors->first('district_id', '<p class="help-block">:message</p>') !!}

            </td>
        </tr>
        <tr class="row {{ $errors->has('ward_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('ward_id', trans('address_delivery.wards'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('ward_id', $ward ?? [] , null, ['class' => 'form-control input-sm select2', 'id' => 'ward']) !!}
                {!! $errors->first('ward_id', '<p class="help-block">:message</p>') !!}

            </td>
        </tr>
        <tr class="row {{ $errors->has('address') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('address', trans('address_delivery.address'), ['class' => ' control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('note') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('note', trans('theme::bookings.note'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('note', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer_id', trans('message.customers'), ['class' => 'control-label'])
                !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('customer_id', $customers, null, ['class' => 'form-control input-sm select2']) !!}
                {!! $errors->first('customer_id', '<p class="help-block">:message</p>') !!}

            </td>
            </tr>
        <tr class="row {{ $errors->has('is_default') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_default', trans('address_delivery.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('is_default', 1, (isset($address_delivery) && $address_delivery->is_default===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                </div>
                {!! $errors->first('is_default', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
      
    </table>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-success mr-2', 'type'=>'submit']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/address_delivery') }}" class="btn btn-default"><i class="fas fa-times"></i> {{ __('message.close') }}</a>
</div>

    @section('scripts-footer')
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        {{-- <script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.min.js') }}"></script> --}}
        <script type="text/javascript" src="{{ asset('/js/sweetalert2.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
        <script>
            CKFinder.config({
                connectorPath: '/ckfinder/connector'
            });
        </script>
   <script type="text/javascript">
    $("#province").change(function () {
        var provinceId = $(this).val();
        console.log(provinceId);
        $.ajax({
            url: "{{ url('admin/ajax/getTopicByType') }}",
            type: "GET",
            data: {id: provinceId},
            dataType: "json",
            success: function (data) {
                $('#district').children().remove().end().append('<option value="">{{ trans('message.please_select') }}</option>');
                $.each(data, function (key, value) {
                    $("#district").append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    });
    $("#district").change(function() {
                var districtId = $(this).val();
                console.log(districtId);
                $.ajax({
                    url: "{{ url('admin/ajax/getTopicByTypeWard') }}",
                    type: "GET",
                    data: {
                        id: districtId
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#ward').children().remove().end().append(
                            '<option value="">{{ trans('message.please_select') }}</option>');
                        $.each(data, function(key, value) {
                            $("#ward").append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            });
            
        function getTypeBusiness() {
            $.ajax({
                url: "{{ url('ajax/getTypeBusiness') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#type_id1').children().remove().end().append(
                        '<option value="">--Chọn ngành chọn loại--</option>');
                    $.each(data, function(key, value) {
                        $("#type_id1").append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
            $(document).ready(function() {
            $('#newsletter').submit(function(e) {
                e.preventDefault();
                axios.post('{{ url('ajax/AddTypeBusiness') }}', {
                        name: $("#name_business").val(),
                    })
                    .then(function(response) {
                        const data = response.data;
                        console.log(response);
                        if (data.success == "ok") {
                            toastr.success("Thêm ngành loại hình thành công");
                            $("#name_business").val(null);
                            getTypeBusiness();
                            $('#exampleModalScrollable').modal('hide');
                        } else {
                            let err = data.errors;
                            let mess = err.join("<br/>");
                            toastr.error(mess);
                        }
                    })
                    .catch(function(error) {
                        toastr.error("Lỗi hệ thông");
                    });
            });
        });
</script>
  
        @include('ckfinder::setup')
    @endsection
