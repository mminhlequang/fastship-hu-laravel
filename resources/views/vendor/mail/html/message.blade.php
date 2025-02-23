@component('vendor.mail.html.layout')

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('vendor.mail.html.subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
@endcomponent
