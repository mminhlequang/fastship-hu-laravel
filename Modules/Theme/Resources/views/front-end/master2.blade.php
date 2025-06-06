<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @section('title')
        <title>{{ !empty($settings['meta_title']) ? $settings['meta_title'] : trans('frontend.title') }}</title>
        <meta name="description"
              content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}"/>
        <meta name="keywords"
              content="{{ !empty($settings['meta_keyword']) ? $settings['meta_keyword'] : trans('frontend.keyword') }}"/>
    @show
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('sitemap.xml') }}"/>
    <meta content="INDEX,FOLLOW" name="robots"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, user-scalable=yes, minimal-ui">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="google-site-verification" content="">
    <meta name="copyright" content="{{ $settings['meta_title'] }}"/>
    <meta name="author" content="{{ $settings['meta_title'] }}"/>
    <meta name="GENERATOR" content="{{ $settings['meta_title'] }}"/>
    <meta http-equiv="audience" content="General"/>
    <meta name="resource-type" content="Document"/>
    <meta name="distribution" content="Global"/>
    <meta name="geo.position" content="Huế"/>
    <meta name="geo.region" content="VN"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}"/>
    <meta property="fb:app_id" content="678581042953588"/>
    <meta property="og:site_name" content="{{ $settings['company_link'] }}"/>
    <meta property="og:type" content="product"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}"/>
    <meta property="og:url" itemprop="url" content="{{ Request::fullUrl() }}"/>
    @section('facebook')
        <meta property="og:title"
              content="{{ !empty($settings['meta_title']) ? $settings['meta_title'] : trans('frontend.title') }}"/>
        <meta property="og:description"
              content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}"/>
        <meta property="og:image" content="{{ asset(Storage::url($settings['company_logo'])) }}"/>
        <meta property="og:image:type" content="image/jpeg"/>
        <meta property="og:image:width" content="600"/>
        <meta property="og:image:height" content="315"/>
    @show
    <meta name="twitter:card" content="article"/>
    <meta name="twitter:description" content="{{ $settings['meta_description'] }}"/>
    <meta name="twitter:title" content="{{ $settings['meta_title'] }}"/>
    <meta name="twitter:image" content="{{ asset(Storage::url($settings['company_logo'])) }}"/>
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}"/>
    <link rel="canonical" href="{{ Request::fullUrl() }}"/>
    <link rel="stylesheet" href="{{ url('assets/css/swiper-bundle.min.css') }}"/>
    <link rel="stylesheet" href="{{ url('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ url('theme.css') }}"/>
    @yield('style')
    <style>
        .skeleton {
            animation: pulse 1.5s infinite ease-in-out;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
        }

        @keyframes pulse {
            0% {
                background-position: 0% 0%;
            }
            100% {
                background-position: -200% 0%;
            }
        }
    </style>
</head>

<body>
@section('schema')
    <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "{{ $settings['company_website'] }}",
                "alternateName": "{{ $settings['meta_title'] }}",
                "url": "{{ url('/') }}",
                "logo": "{{ asset(Storage::url($settings['company_logo'])) }}",
                "sameAs": [
                    "{{ $settings['follow_facebook'] }}",
                    "{{ $settings['follow_twitter'] }}",
                    "{{ $settings['follow_linked'] }}",
                    "{{ $settings['follow_google'] }}"
                ],
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $settings['company_address'] }}",
                    "addressRegion": "Hue",
                    "postalCode": "49000",
                    "addressCountry": "VN"
                }
            }

    </script>
    <div class="app">
        @include('theme::front-end.layouts.header')
        @section('content')
        @show
        @include('theme::front-end.layouts.footer')
    </div>
    @include('theme::front-end.modals.selector_location')
</body>

<script type="text/javascript" src="{{ url('js/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/lazysizes.min.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/js.cookie.min.js') }}"></script>
<script src="{{ url('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ url('assets/js/popular-categories-slider.js') }}"></script>
<script src="{{ url('assets/js/filter-categories-slider.js') }}"></script>
<link href="{{ url('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
<script src="{{ url('plugins/toastr/toastr.min.js') }}"></script>
<link href="{{ url('plugins/select2/select2.min.css') }}" rel="stylesheet"/>
<script src="{{ url('plugins/select2/select2.min.js') }}"></script>
@yield('script')
<script type="text/javascript">
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            let latitude = Cookies.get('lat') ?? position.coords.latitude;
            let longitude = Cookies.get('lng') ?? position.coords.longitude;
            getAddressByLatLng(latitude, longitude);
        });
    } else {
        document.getElementById("location").innerHTML = "No address found";
    }

    function getAddressByLatLng(lat, lng) {
        const apiKey = 'HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0';
        const url = `https://revgeocode.search.hereapi.com/v1/revgeocode?at=${lat},${lng}&apikey=${apiKey}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.items && data.items.length > 0) {
                    const address = data.items[0].address;
                    console.log('Address:', address);
                    document.querySelectorAll('.currentLocationText').forEach(el => {
                        el.textContent = address.label;
                    });
                    Cookies.set('lat', lat);
                    Cookies.set('lng', lng);
                    Cookies.set('address', address.label);
                } else {
                    document.querySelectorAll('.currentLocationText').forEach(el => {
                        el.textContent = 'No address found.';
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.querySelectorAll('.currentLocationText').forEach(el => {
                    el.textContent = 'No address found.';
                });
            });
    }
</script>
<script type="text/javascript">
    function toggleLanguageDropdown() {
        const dropdown = document.getElementById('languageDropdown');
        dropdown.classList.toggle('hidden');
    }

    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('hidden');
    }

    window.addEventListener('click', function (e) {
        if (!e.target.closest('.language-selector')) {
            const dropdown = document.getElementById('languageDropdown');
            if (!dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        }
    });

    function setLanguageAndSubmit(language) {
        document.getElementById('locale_client').value = language;
        document.getElementById('frmLag').submit();
    }

</script>
<script type="text/javascript">
    function formatCountry(country) {
        if (!country.id) return country.text;
        const img = $(country.element).data('image');
        const text = country.text;

        return $(
            `<span class="flex items-center gap-2">
                <img src="${img}" class="w-5 h-5 object-cover rounded-sm" />
                <span>${text}</span>
            </span>`
        );
    }

    function formatSelected(country) {
        if (!country.id) return country.text;
        const img = $(country.element).data('image');
        const dial = country.id;

        return $(
            `<span class="flex items-center gap-2">
                <img src="${img}" class="w-5 h-5 object-cover rounded-sm" />
                <span>${dial}</span>
            </span>`
        );
    }

    $(document).ready(function () {
        $('#country-select').select2({
            templateResult: formatCountry,
            templateSelection: formatSelected,
            width: '100%',
            dropdownAutoWidth: true,
            escapeMarkup: markup => markup,
            minimumResultsForSearch: 0,
        });
    });
</script>
</html>
