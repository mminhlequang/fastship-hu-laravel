@extends('adminlte::layouts.app')
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
                    <td class="text-center" style="width:25%"><strong>Mã QR</strong></td>
                    <td style="width:75%" colspan="4"><strong>Thông tin</strong></td>
                </tr>
                <tr>
                    <td rowspan="13">
                        <div id="qrcode" style="display: flex; justify-content: center"></div>
                        <div style="display: flex; justify-content: center; margin-top: 10px;">
                            <button id="downloadBtn" class="btn btn-sm btn-danger">Tải QR code về máy</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>{{ __('customers.name') }}</th>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.email') }}</th>
                    <td>{{ $customer->email }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.phone') }}</th>
                    <td>{{ $customer->phone }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.birthday') }}</th>
                    <td>{{ \Carbon\Carbon::parse($customer->birthday)->format(config('settings.format.datetime')) }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.address') }}</th>
                    <td>{{ $customer->address }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.street') }}</th>
                    <td>{{ $customer->street }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.zip') }}</th>
                    <td>{{ $customer->zip }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.city') }}</th>
                    <td>{{ $customer->city }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.state') }}</th>
                    <td>{{ $customer->state }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.country') }}</th>
                    <td>{{ $customer->country }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.country_code') }}</th>
                    <td>{{ $customer->country_code }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.lat') }}</th>
                    <td>{{ $customer->lat }}</td>
                </tr>
                <tr>
                    <th>{{ __('customers.lng') }}</th>
                    <td>{{ $customer->lng }}</td>
                </tr>
                <tr>
                    <th> {{ __('customers.active') }} </th>
                    <td> {{ ($customer->active == 1) ? 'Active' : 'Block' }} </td>
                </tr>
                <tr>
                    <th> {{ __('message.created_at') }} </th>
                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->format(config('settings.format.datetime')) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('scripts-footer')
    <script src="{{ url("js/qrcode.min.js") }}"></script>
    <script type="text/javascript">
        const downloadBtn = document.getElementById('downloadBtn');
        const qrCodeDiv = document.getElementById('qrcode');

        var url = '{{ url('scan-qr.html') }}' +
            '?customer_id=' + encodeURIComponent({{ $customer->id }}) +
            '&promotion_id=' + encodeURIComponent({{ $customer->promotion_id }});

        var qrcode = new QRCode(
            "qrcode",
            {
                text: url,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#FFFFFF",
                correctLevel: QRCode.CorrectLevel.M
            }
        );
        downloadBtn.addEventListener('click', () => {
            const canvas = document.querySelector('#qrcode canvas');
            const qrCodeURL = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = qrCodeURL;
            downloadLink.download = 'qr_code.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });


    </script>
@endsection