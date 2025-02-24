<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<div class="content-mail" style="padding: 15px;">
    <div style="width: 600px; margin: 0 auto;">
        <div style="background-color: #fff;">
            <div style="padding: 15px;">
                <h4 style="color: #1ba100; font-weight: bold; margin-top: 20px !important; font-size: 16px; border-bottom: 1px solid #ccc; margin-bottom: 15px; padding-bottom: 5px;">
                    {{ $data['title'] }} <span style="font-size: 14px; color: #999; font-weight: 500;">(Ngày {{ \Carbon\Carbon::now()->format(config('settings.format.datetime')) }})</span>
                </h4>
                <img src="{{ asset($data['image']) }}" alt="" height="100"/>
                <table style="font-family:Arial, sans-serif; width:100%; max-width:600px; border-collapse:collapse" align="left" border="1" bordercolor="#211551" cellpadding="10" cellspacing="0">
                    <thead>
                    <tr>
                        <th bgcolor="#1ba100" style="color:#fff; text-align: left;">Nội dung</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align:left; vertical-align:middle">{!! $data['description'] !!}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
</body>
</html>