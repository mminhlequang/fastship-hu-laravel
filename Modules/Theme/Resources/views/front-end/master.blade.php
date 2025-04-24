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
        .lazyload {
            position: relative;
            background: #eee;
            overflow: hidden;
            display: inline-block; /* Ensures proper sizing for inline elements */
            line-height: 0; /* Prevents alt text from showing during load */
        }

        .lazyload img {
            opacity: 0;
            transition: opacity 0.3s;
            width: 100%;
            height: auto;
        }

        /* Shimmer effect */
        .lazyload::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #eee;
            z-index: 1;
        }

        .lazyload::before {
            content: "";
            position: absolute;
            top: 0;
            left: -150%;
            width: 150%;
            height: 100%;
            background: linear-gradient(
                    90deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.6) 50%,
                    rgba(255, 255, 255, 0) 100%
            );
            animation: shimmer 1.5s infinite;
            z-index: 2;
        }

        /* When image is loaded */
        .lazyloaded img {
            opacity: 1;
        }

        .lazyloaded::before,
        .lazyloaded::after {
            content: none;
        }

        @keyframes shimmer {
            100% {
                left: 100%;
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
    @include('theme::front-end.layouts.loading')
    @include('theme::front-end.modals.login')
    @include('theme::front-end.modals.otp')
    @include('theme::front-end.modals.product')
    @include('theme::front-end.modals.selector_location')
</body>

<script type="text/javascript" src="{{ url('js/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/lazysizes.min.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/js.cookie.min.js') }}"></script>
<script src="{{ url('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ url('assets/js/popular-categories-slider.js') }}"></script>
<link href="{{ url('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
<script src="{{ url('plugins/toastr/toastr.min.js') }}"></script>
<link href="{{ url('plugins/select2/select2.min.css') }}" rel="stylesheet"/>
<script src="{{ url('plugins/select2/select2.min.js') }}"></script>
@yield('script')
<script type="text/javascript">
    function formatCountry(country) {
        if (!country.id) return country.text;
        const img = $(country.element).data('image');
        const text = country.text;

        return $(
            `<span class="flex items-center gap-2">
                <img data-src="${img}" class="w-5 h-5 object-cover rounded-sm lazyload" />
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
                <img data-src="${img}" class="w-5 h-5 object-cover rounded-sm lazyload" />
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
    $(document).ready(function () {
        $('#newsLetterForm').submit(function (e) {
            e.preventDefault();
            $('.loading').addClass('loader');
            $.ajax({
                url: '{{ url('ajaxFE/newsLetter') }}',
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    const data = response;
                    if (data.status) {
                        $('#newsLetterForm')[0].reset();
                        toastr.success(data.message);
                        $('.loading').removeClass('loader');
                    } else {
                        let err = data.errors;
                        let mess = err.join("<br/>");
                        toastr.error(mess);
                        $('.loading').removeClass('loader');
                    }
                }
            });
        });
        $('body').on('click', '.selectProduct', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('.loading').addClass('loader');
            $.ajax({
                url: '{{ url('ajaxFE/getDetailProduct') }}',
                type: "GET",
                data: {
                    id: id
                },
                success: function (res) {
                    $('#modalProduct').html(res);
                    toggleModal('modalOverlayProduct');
                    loadJsModal();
                    $('.loading').removeClass('loader');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $('.loading').removeClass('loader');
                }
            });
        });

        function loadJsModal() {
            const decreaseBtn = document.getElementById("decreaseBtn");
            const increaseBtn = document.getElementById("increaseBtn");
            const quantityElement = document.getElementById("quantity");
            const addToOrderBtn = document.getElementById("addToOrderBtn");

            const basePrice = parseFloat(document.getElementById("inputPrice").value);
            let currentTotal = basePrice;
            let quantity = 1;

            decreaseBtn.addEventListener("click", function () {
                if (quantity > 1) {
                    quantity--;
                    updateQuantityAndPrice();
                }
            });

            increaseBtn.addEventListener("click", function () {
                quantity++;
                updateQuantityAndPrice();
            });

            document.querySelectorAll('input[type="radio"].variation-radio').forEach((radio) => {
                radio.addEventListener("change", function () {
                    const groupDiv = radio.closest('[data-group]');
                    const groupName = groupDiv.getAttribute("data-group");
                    document.querySelectorAll(`[data-group="${groupName}"] input[type="radio"]`).forEach((item) => {
                        const label = item.closest("label");
                        if (item.checked) {
                            label.classList.add("bg-green-50", "border-primary");
                        } else {
                            label.classList.remove("bg-green-50", "border-primary");
                        }
                    });
                    updateTotalPrice();
                });

                const label = radio.closest("label");
                label.addEventListener("click", function () {
                    radio.checked = true;
                    const event = new Event("change");
                    radio.dispatchEvent(event);
                });
            });

            addToOrderBtn.addEventListener("click", function () {
                const productId = this.getAttribute("data-id");
                const storeId = this.getAttribute("data-store");
                const authId = "{{ \Auth::guard('loyal_customer')->id() }}";

                if (!authId) {
                    return toggleModal('modalOverlayLogin');
                }

                const selectedRadios = document.querySelectorAll('input.variation-radio:checked');
                const variations = Array.from(selectedRadios).map((radio) => ({
                    variation_value: parseInt(radio.value)
                }));

                const orderData = {
                    product: productId,
                    storeId: storeId,
                    quantity: quantity,
                    totalPrice: currentTotal * quantity,
                    variations: variations
                };

                addCart(productId, storeId, quantity, variations);
            });

            function addCart(productId, storeId, quantity, variations) {
                $('.loading').addClass('loader');
                let url = '{{ url('ajaxFE/addCart') }}';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        store_id: storeId,
                        quantity: quantity,
                        variations: variations
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        $('.loading').removeClass('loader');
                        if (data.status) {
                            toastr.success(data.message);
                            toggleModal('modalOverlayProduct');
                        } else {
                            toastr.warning(data.message);
                        }
                    })
                    .catch(error => {
                        $('.loading').removeClass('loader');
                        toastr.error('Error: ' + error.message);
                    });
            }


            function updateQuantityAndPrice() {
                quantityElement.textContent = quantity;
                updateTotalPrice();
            }

            function updateTotalPrice() {
                let additionalPrice = 0;
                const selectedVariations = document.querySelectorAll('input.variation-radio:checked');
                selectedVariations.forEach((radio) => {
                    const price = parseFloat(radio.dataset.price) || 0;
                    additionalPrice += price;
                });
                currentTotal = basePrice + additionalPrice;
                const finalPrice = (currentTotal * quantity).toFixed(2);
                addToOrderBtn.textContent = `Add to order • $${finalPrice}`;
            }

            updateTotalPrice();
        }

        $('#loginForm').on('submit', function (e) {
            e.preventDefault();
            $('.loading').addClass('loader');
            $.ajax({
                url: '{{ url('ajaxFE/sendOtp') }}',
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    const data = response;
                    if (data.status) {
                        var phone = data.data;
                        localStorage.setItem('phone', phone);
                        firebase.auth().signInWithPhoneNumber(phone, window.recaptchaVerifier)
                            .then(function (confirmationResult) {
                                window.confirmationResult = confirmationResult;
                                toastr.success('Send OTP Successfully');
                                $('#loginForm')[0].reset();
                                toggleModal('modalOverlayLogin');
                                toggleModal('modalOverlayOtp');
                                startCountdown();
                                $('.loading').removeClass('loader');
                            })
                            .catch(function (error) {
                                const errorMessage = error.message || error.code || 'An error occurred';
                                toastr.error(errorMessage);
                                $('.loading').removeClass('loader');
                            });
                    } else {
                        let err = data.message;
                        let mess = err.join("<br/>");
                        toastr.error(mess);
                        $('.loading').removeClass('loader');
                    }
                }, error: function (xhr, status, error) {
                    toastr.error("Something went wrong! Please try again.");
                    $('.loading').removeClass('loader');
                }
            });
        });

    });

</script>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-recaptcha.js"></script>
<script type="text/javascript">
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
        const phone = localStorage.getItem('phone');
        firebase.auth().signInWithPhoneNumber(phone, window.recaptchaVerifier)
            .then(function (confirmationResult) {
                window.confirmationResult = confirmationResult;
                toastr.success('Send OTP Successfully');
            })
            .catch(function (error) {
                const errorMessage = error.message || error.code || 'An error occurred';
                toastr.error(errorMessage);
            });
    }
</script>
<script type="text/javascript">
    $('body').on('click', '.favoriteIcon', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const authId = "{{ \Auth::guard('loyal_customer')->id() }}";
        if (!authId) {
            return toggleModal('modalOverlayLogin');
        }
        let id = $(this).data('id');
        let isStore = $(this).data('store') ?? 0;
        const $img = $(this).find('img');
        const currentSrc = $img.attr('src');
        let url = (isStore == 1) ? '{{ url('ajaxFE/favoriteStore') }}' : '{{ url('ajaxFE/favoriteProduct') }}';
        $.ajax({
            url: url,
            type: "GET",
            data: {
                id: id
            },
            success: function (res) {
                if (res.status) {
                    if (currentSrc.includes('heart_line_icon.svg')) {
                        $img.attr('src', '/assets/icons/heart_check.svg');
                    } else {
                        $img.attr('src', '/assets/icons/heart_line_icon.svg');
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });
</script>


</html>
