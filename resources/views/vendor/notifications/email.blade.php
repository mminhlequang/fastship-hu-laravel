@component('mail::message')

@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Xin chào!')
@endif
{{-- Intro Lines --}}
Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.
{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
Đặt lại mật khẩu
@endcomponent
@endisset

@lang('Liên kết đặt lại mật khẩu chỉ có giá trị trong 5 phút !')<br>
@lang('Nếu bạn không yêu cầu đặt lại mật khẩu, bạn không cần thực hiện thêm hành động nào.')<br>

{{-- Salutation --}}

@lang('Huesoft Corp!')<br>
@endcomponent
