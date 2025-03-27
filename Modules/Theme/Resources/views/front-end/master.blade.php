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
    <link href="{{ url(mix('/css/web.css')) }}" rel="stylesheet"/>
    <link rel="stylesheet" href="./assets/css/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./assets/css/main.css"/>
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
</body>

@yield('script')
<script type="text/javascript" src="{{ url('js/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/lazysizes.min.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/js.cookie.min.js') }}"></script>
<script src="./assets/js/swiper-bundle.min.js"></script>
<script src="./assets/js/cutsomer-logo-slider.js"></script>
<script src="./assets/js/popular-categories-slider.js"></script>
<script src="./assets/js/discount-slider.js"></script>
<script src="./assets/js/top-rated-slider.js"></script>
<script src="./assets/js/local-favorite-slider.js"></script>
<script src="./assets/js/main.js"></script>
<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js" charset="utf-8"></script>
<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js" charset="utf-8"></script>
<script type="text/javascript">
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            Cookies.set('lat', latitude);
            Cookies.set('lng', longitude);
        });
    } else {
        document.getElementById("location").innerHTML = "Trình duyệt của bạn không hỗ trợ geolocation.";
    }

</script>
<script type="text/javascript">
    var apiKey = 'HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0';
    function getAddressFromLatLng(lat, lng) {
        var platform = new H.service.Platform({
            apikey: apiKey
        });

        var geocoder = platform.getGeocodingService();

        geocoder.reverseGeocode(
            {lat: lat, lng: lng},
            function(result) {
                var address = result.items[0].address;
                var formattedAddress = address.label;
                document.getElementById('location').innerHTML = "Địa chỉ: " + formattedAddress;
            },
            function(error) {
                document.getElementById('location').innerHTML = "Không thể lấy địa chỉ.";
            }
        );
    }

    getAddressFromLatLng(21.0285, 105.8542);
</script>
</html>
