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
    <link rel="stylesheet" href="{{ url('assets/css/swiper-bundle.min.css') }}"/>
    <link rel="stylesheet" href="{{ url('assets/css/main.css') }}"/>
    @yield('style')
    <style>
        .skeleton {
            background-color: #e0e0e0;
            animation: skeleton-loading 1.5s infinite linear;
            border-radius: 4px;
        }

        @keyframes skeleton-loading {
            0% {
                background-color: #e0e0e0;
            }
            50% {
                background-color: #c0c0c0;
            }
            100% {
                background-color: #e0e0e0;
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

    @include('theme::front-end.modals.login')
    @include('theme::front-end.modals.forgot')
</body>

@yield('script')
<script type="text/javascript" src="{{ url('js/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/lazysizes.min.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/js.cookie.min.js') }}"></script>
<script src="{{ url('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ url('assets/js/cutsomer-logo-slider.js') }}"></script>
<script src="{{ url('assets/js/popular-categories-slider.js') }}"></script>
<script src="{{ url('assets/js/discount-slider.js') }}"></script>
<script src="{{ url('assets/js/top-rated-slider.js') }}"></script>
<script src="{{ url('assets/js/local-favorite-slider.js') }}"></script>
<script src="{{ url('assets/js/main.js') }}"></script>
<script>
    $(document).ready(function() {
        $('img.lazyload').on('lazyloaded', function() {
            $(this).prev('.skeleton').fadeOut(100);
        });
    });
</script>
<script type="text/javascript">
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            Cookies.set('lat', latitude);
            Cookies.set('lng', longitude);
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
                    document.getElementById('location').textContent = `${address.label}`;
                } else {
                    document.getElementById('location').textContent = 'No address found.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('location').textContent = 'Error fetching address.';
            });
    }
</script>
<script type="text/javascript">
    function toggleLanguageDropdown() {
        const dropdown = document.getElementById('languageDropdown');
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
    function toggleModal(modalClassName) {
        const modalOverlays = document.querySelectorAll('.modalOverlay');

        modalOverlays.forEach((modalOverlay) => {
            if (!modalOverlay.classList.contains('hidden') && !modalOverlay.classList.contains(modalClassName)) {
                modalOverlay.classList.add('hidden');
            }
        });

        const targetModalOverlay = document.querySelector(`.${modalClassName}`);
        if (targetModalOverlay) {
            targetModalOverlay.classList.toggle('hidden');

            if (!targetModalOverlay.classList.contains('hidden')) {
                targetModalOverlay.addEventListener('click', function (e) {
                    if (e.target === targetModalOverlay) {
                        targetModalOverlay.classList.add('hidden');
                    }
                });
            }
        }
    }


</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

        });
    });

</script>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-recaptcha.js"></script>

<script>
    var firebaseConfig = {
        apiKey: "AIzaSyA1zJdq1xJDJrH3OLHAWd-7BSZQDzaSFPE",
        authDomain: "fastshiphu-1ac6c.firebaseapp.com",
        projectId: "fastshiphu-1ac6c",
        storageBucket: "fastshiphu-1ac6c.firebasestorage.app",
        messagingSenderId: "938800403743",
        appId: "1:938800403743:web:4b7d1a9b8a5398b9268c2a",
        measurementId: "G-3TN8CFL21M"
    };

    firebase.initializeApp(firebaseConfig);

    window.onload = function () {
        renderRecaptcha();
    };

    function renderRecaptcha() {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
        recaptchaVerifier.render();
    }

    function sendOtp() {
        var number = '+84969696969';
        firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier)
            .then(function (confirmationResult) {
                window.confirmationResult = confirmationResult;
                alert('"Message Sent Successfully."');
            })
            .catch(function (error) {
                alert(error.message);
            });
    }

    function verifyOtp() {
        var code = document.getElementById("verificationCode").value;
        window.confirmationResult.confirm(code)
            .then(function (result) {
                var user = result.user;
                document.getElementById("successRegsiter").textContent = "You are registered successfully.";
                document.getElementById("successRegsiter").style.display = "block";

                var userData = {
                    uid: user.uid,
                    phoneNumber: user.phoneNumber
                };

                fetch('/store-user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(userData)
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            })
            .catch(function (error) {
                document.getElementById("error").textContent = error.message;
                document.getElementById("error").style.display = "block";
            });
    }
</script>

</html>
