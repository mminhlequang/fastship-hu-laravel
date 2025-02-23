<!DOCTYPE html>
<html>
<head>
    <style>
        .email-container {
            background-image: url('{{ url('images/background_mail.png') }}');
            background-repeat: no-repeat;
            background-size: cover;
        }

        .wrap-container {
            margin-bottom: 30px;
            margin-top: 80px;
        }

        @media (max-width: 512px) {
            .wrap-title {
                font-size: 0.8em;
            }
        }
        .d-flex{
            display: flex;
            justify-content: center !important;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="wrap-container">
        <div class="wrap-title"
             style="margin: 0 auto;width: 90%;color: white; background: #fd0f0f;text-align: center;font-weight: 600;border-top-left-radius: 5px; border-top-right-radius: 5px;">
            Thời gian chương trình
            <br>{{ Carbon\Carbon::parse($data->date_start)->format(config('settings.format.date')) ."-".Carbon\Carbon::parse($data->date_end)->format(config('settings.format.date')) }}
        </div>
        <div class="wrap-content"
             style="background: white;padding: 20px; margin: 0 auto;width: 100%; border-radius: 20px;border: 3px solid #fd0f0f;">
            <h5 style="font-weight: bold; text-align: center;text-transform: uppercase;">THÔNG TIN CHƯƠNG TRÌNH<br>
                "{{ $data->name }}"
            </h5>
            <div style="text-align: center;">
                <img src="{{ asset($data->qr) }}" width="200px"/>
                <hr>
            </div>
            @component('mail::table')
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <td class="fw-8"><b>Tên chương trình:</b></td>
                    </tr>
                    <tr>
                        <td class="fw-8"> {{ $data->name }} </td>
                    </tr>
                    <tr>
                        <td class="fw-8" ><b>Ngày bắt đầu:</b></td>
                    </tr>
                    <tr>
                        <td class="fw-8"> {{ Carbon\Carbon::parse($data->date_start)->format(config('settings.format.date')) }} </td>
                    </tr>
                    <tr>
                        <td class="fw-8"><b>Ngày kết thúc:</b></td>
                    </tr>
                    <tr>
                        <td class="fw-8"> {{ Carbon\Carbon::parse($data->date_end)->format(config('settings.format.date')) }} </td>
                    </tr>
                    <tr>
                        <td class="fw-8" ><b>Nội dung:</b></td>
                    </tr>
                    <tr>
                        <td class="fw-8"> {!! $data->description !!} </td>
                    </tr>
                    </tbody>
                </table>

            @endcomponent
            <b>Xin chân thành cảm ơn bạn đã quan tâm đến chương trình.</b>
        </div>
    </div>
</div>
</body>
</html>


