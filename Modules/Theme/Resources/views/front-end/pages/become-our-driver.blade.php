@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Become our driver') }}</title>
    <meta name="description"
          content="{{ __('Become our driver') }}" />
    <meta name="keywords" content="{{ __('Become our driver') }}" />
@endsection
@section('style')
    <style>
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background-color: #cccccc;
            opacity: 1;
            margin: 0 5px;
        }

        .swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet,
        .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet {
            margin: 0 8px;
        }

        .swiper-pagination-bullet-active {
            background-color: #ff6600;
            width: 16px;
            height: 16px;
            border: 2px solid #ff6600;
            position: relative;
            top: 2px;
            left: 2px;
        }

        .swiper-pagination-bullet-active::after {
            content: "";
            position: absolute;
            top: -6px;
            left: -6px;
            width: 24px;
            height: 24px;
            border: 1px solid #ff6600;
            border-radius: 50%;
        }

        .swiper-pagination-bullet-active {
            background-color: #f97316;
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            background: #f17228;
            color: white;
        }

        /* Custom navigation buttons */
        .swiper-button-prev,
        .swiper-button-next {
            position: relative;
            padding: 24px;
            background-color: white;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            color: #847D79;
        }

        .swiper-wrapper {
            position: relative;
            padding-bottom: 30px;
            /* Adjust this value to make space for the buttons */
        }

        .swiper-button-prev:after,
        .swiper-button-next:after {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            font-weight: bold;
        }

        .swiper-button-prev:after {
            content: "\2190";
        }

        .swiper-button-next:after {
            content: "\2192";
        }

        .swiper-slide {
            background-color: unset;
        }

        /* Move pagination to bottom */
        .swiper-pagination {
            position: unset;
        }

        input.placeholder-white::placeholder {
            color: #E6FBDA;
        }
    </style>
@endsection
@section('content')
    <section id="sub-page-header" class="h-[415px] w-full relative">
        <div class="absolute inset-0">
            <img
                    data-src="{{ url('assets/images/banner_customer.png') }}"
                    class="w-full h-full object-cover object-center lazyload"
                    alt="Banner Partner" />
        </div>
        <div
                class="flex flex-col lg:flex-nowrap lg:flex-row lg:items-center absolute inset-0 z-10 responsive-px">
            <div class="flex flex-col gap-6 w-full lg:w-1/2">
                <h1 class="text-4xl mt-5 lg:mt-0 font-medium mb-4 text-white">
                    Make someone's day with delivery
                </h1>
                <p class="text-[22px] leading-snug text-white">
                    Fast Ship Hu Drive is your trusted partner for fast and reliable
                    last-mile deliveries. Your customers place an order on your app or
                    online store, and in less than one hour it's in their hands.
                </p>
                <div class="text-left">
                    <button class="flex gap-2 items-center rounded-full py-2.5 px-6 bg-white border-[1px] border-secondary text-secondary">
                        {{ __('theme::web.signup') }}
                        <img data-src="{{ url('assets/icons/up_right_icon_y.svg') }}" class="lazyload" />
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Use Us Section -->
    <section class="py-15 bg-[#F1EFE9]">
        <div class="responsive-px">
            <h2 class="text-3xl font-medium text-center mb-4">Why use us</h2>
            <p class="text-gray-600 text-center max-w-3xl mx-auto mb-12 w-full lg:w-1/2">
                Simply add Wolt Drive logistics to your online checkout to offer
                convenient and affordable express deliveries. Give your customers the
                best shopping experience in town. Boost sales and increase customer
                loyalty.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white px-7 py-8 rounded-3xl shadow-sm">
                    <div class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-12">
                        <img data-src="{{ url('assets/icons/icon_d1.svg') }}" class="lazyload" />
                    </div>
                    <h3 class="text-4xl mb-[80px]">Instant cash-out</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                The more you deliver, the more money you can earn.
                            </p>
                        </li>
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                Get paid per delivery and distance covered.
                            </p>
                        </li>
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                The consensus mechanism that connects stocks and bitcoin.
                            </p>
                        </li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white px-7 py-8 rounded-3xl shadow-sm">
                    <div class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-12">
                        <img data-src="{{ url('assets/icons/icon_d2.svg') }}" class="lazyload" />
                    </div>
                    <h3 class="text-4xl mb-[80px]">All-in-one app</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                The more you deliver, the more money you can earn.
                            </p>
                        </li>
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                Get paid per delivery and distance covered.
                            </p>
                        </li>
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                The consensus mechanism that connects stocks and bitcoin.
                            </p>
                        </li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white px-7 py-8 rounded-3xl shadow-sm">
                    <div class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-12">
                        <img data-src="{{ url('assets/icons/icon_d3.svg') }}" class="lazyload" />
                    </div>
                    <h3 class="text-4xl mb-[80px]">Easy to track</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                The more you deliver, the more money you can earn.
                            </p>
                        </li>
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                Get paid per delivery and distance covered.
                            </p>
                        </li>
                        <li class="flex items-start space-x-2">
                            <img data-src="{{ url('assets/icons/icon_discount.svg') }}" class="mr-1 lazyload" />
                            <p class="text-gray-600 flex-1">
                                The consensus mechanism that connects stocks and bitcoin.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Who Can Apply Section -->
    <section class="mb-[80px] mt-[80px]">
        <div class="responsive-px">
            <div class="bg-[#F9F8F6] rounded-3xl p-10">
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/2 mb-8 lg:mb-0">
                        <h2 class="text-4xl font-medium mb-8 mt-8">Who can apply?</h2>

                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                <span>Between 18-65 years old</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2 mt-1.5"></div>
                                <span class="font-medium">Applicants aged 60 years old and above require a "fit to
                                work" medical certification</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                <span>Valid Professional Driver's License</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                <span>Valid National Bureau of Investigation (NBI) background check
                                clearance</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                <span>Drug test clearance from an accredited Drug Testing
                                Laboratory</span>
                            </li>
                        </ul>
                        <button class="flex gap-3 items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                            Apply now
                            <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="brightness-[100] lazyload" />
                        </button>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-white border-[8px] rounded-lg p-2">
                            <div class="relative rounded-lg overflow-hidden h-[180px]">
                                <div class="absolute inset-0 border-white border-[8px] rounded-lg">
                                    <img data-src="{{ url('assets/images/u1.svg') }}" alt="Driver" class="w-full h-full object-cover lazyload" />
                                    <div class="absolute bottom-2 left-2 flex items-center justify-center">
                                        <div class="bg-white rounded-full p-2 shadow-md">
                                            <img data-src="{{ url('assets/images/icon_play.svg') }}" alt="Driver" class="lazyload" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg overflow-hidden h-[180px]">
                                <div class="absolute inset-0 border-white border-[8px] rounded-lg">
                                    <img data-src="{{ url('assets/images/u2.svg') }}" alt="Driver" class="w-full h-full object-cover lazyload" />
                                    <div class="absolute bottom-2 left-2 flex items-center justify-center">
                                        <div class="bg-white rounded-full p-2 shadow-md">
                                            <img data-src="{{ url('assets/images/icon_play.svg') }}" alt="Driver" class="lazyload" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg overflow-hidden h-[180px]">
                                <div class="absolute inset-0 border-white border-[8px] rounded-lg">
                                    <img data-src="{{ url('assets/images/u3.svg') }}" alt="Driver" class="w-full h-full object-cover lazyload" />
                                    <div class="absolute bottom-2 left-2 flex items-center justify-center">
                                        <div class="bg-white rounded-full p-2 shadow-md">
                                            <img data-src="{{ url('assets/images/icon_play.svg') }}" alt="Driver" class="lazyload" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg overflow-hidden h-[180px]">
                                <div class="absolute inset-0 border-white border-[8px] rounded-lg">
                                    <img data-src="{{ url('assets/images/u4.svg') }}" alt="Driver" class="w-full h-full object-cover lazyload" />
                                    <div class="absolute bottom-2 left-2 flex items-center justify-center">
                                        <div class="bg-white rounded-full p-2 shadow-md">
                                            <img data-src="{{ url('assets/images/icon_play.svg') }}" alt="Driver" class="lazyload" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg overflow-hidden h-[180px]">
                                <div class="absolute inset-0 border-white border-[8px] rounded-lg">
                                    <img data-src="{{ url('assets/images/u5.svg') }}" alt="Driver" class="w-full h-full object-cover lazyload" />
                                    <div class="absolute bottom-2 left-2 flex items-center justify-center">
                                        <div class="bg-white rounded-full p-2 shadow-md">
                                            <img data-src="{{ url('assets/images/icon_play.svg') }}" alt="Driver" class="lazyload" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg overflow-hidden h-[180px]">
                                <div class="absolute inset-0 border-white border-[8px] rounded-lg">
                                    <img data-src="{{ url('assets/images/u6.svg') }}" alt="Driver" class="w-full h-full object-cover lazyload" />
                                    <div class="absolute bottom-2 left-2 flex items-center justify-center">
                                        <div class="bg-white rounded-full p-2 shadow-md">
                                            <img data-src="{{ url('assets/images/icon_play.svg') }}" alt="Driver" class="lazyload" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="mb-[80px]">
        <div class="responsive-px flex flex-wrap lg:flex-nowrap gap-8">
            <div class="swiper testimonial-swiper bg-[#F1EFE9] rounded-3xl p-6 md:p-12 w-full lg:w-2/3">
                <div class="swiper-wrapper">
                    @foreach ([
                      'u2.svg',
                      'u1.svg',
                      'u3.svg',
                      'u4.svg'
                    ] as $userImage)
                        <div class="swiper-slide">
                            <div class="testimonial-card flex flex-col md:flex-row gap-4">
                                <div class="flex-shrink-0">
                                    <img
                                            data-src="{{ url('assets/images/' . $userImage) }}"
                                            alt="Testimonial user"
                                            class="w-24 h-32 md:w-32 md:h-44 rounded-md object-cover lazyload" />
                                </div>
                                <div class="flex-1 text-left">
                                    <h3 class="text-lg font-bold">Great !!</h3>
                                    <p class="text-gray-600 text-sm mt-2 mb-4">
                                        Easily combinable with other jobs. I am getting extra
                                        income. I like the Walt team - they always answer to any of
                                        my questions respectfully and with a solution!
                                    </p>
                                    <div class="mt-2">
                                        <p class="font-normal">Anton de Swardt</p>
                                        <div class="rate-item flex gap-1">
                                            @for ($i = 0; $i < 5; $i++)
                                                <img src="{{ url('assets/icons/star_rating.svg') }}" alt="Star" class="w-4 h-4">
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center mt-6 md:mt-8">
                    <div class="swiper-button-prev transition"></div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
            <div class="flex flex-col gap-4 md:gap-8 w-full lg:w-1/3 mt-6 lg:mt-0">
                <h2 class="text-2xl md:text-4xl font-medium">What others are saying about Us</h2>
                <p class="text-muted text-base md:text-lg">
                    Join our hundreds of companies embracing strategic finance with Zenly
                </p>
                <div class="rate">
                    <div class="rate-item flex gap-2 mb-2">
                        @for ($i = 0; $i < 5; $i++)
                            <img src="{{ url('assets/icons/star_rating.svg') }}" alt="Star" class="w-4 h-4">
                        @endfor
                    </div>
                    <p class="text-muted text-base md:text-lg">
                        Review from Trustpilot
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Getting Started Section -->
    <section class="mb-[80px]">
        <div class="responsive-px">
            <div class="bg-[#538D33] text-white rounded-3xl p-15 flex flex-col md:flex-row justify-between gap-6">
                <!-- Phần bên trái (Text) -->
                <div class="w-full md:w-1/3 mb-6 md:mb-0">
                    <h2 class="text-4xl font-medium">
                        Get started today for<br />
                        better future finance !
                    </h2>
                </div>

                <!-- Phần bên phải (Input + Button) -->
                <div class="w-full md:w-2/3">
                    <p class="mb-6">
                        Convenient last-mile deliveries, no matter what you sell. From
                        small to big businesses and everything in between - let's talk
                        about your delivery needs.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <div class="flex w-full sm:w-2/3 space-x-4">
                            <input type="text" placeholder="Enter Your work email"
                                   class="text-black w-full pl-4 py-3 md:py-2.55 rounded-full border border-[#74CA45] focus:outline-none focus:ring-1 bg-transparent placeholder-white" />
                        </div>
                        <div class="flex w-full sm:w-1/3">
                            <button class="inline-flex items-center py-3 md:py-2.55 px-6 rounded-full bg-white border-[1px] border-primaryprimary text-primary">
                                Get started
                                <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="ml-2 lazyload" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Food Delivery Section -->
    <section id="cta-download-app" class="responsive-px mb-[80px]">
        <div class="bg-[#F1EFE9] rounded-3xl px-4 pt-10 lg:pb-10 xl:pb-0 md:pl-12 lg:pl-8 xl:pl-12 md:pt-10 md:pr-4">
            <div class="flex flex-col lg:flex-row md:items-center gap-16">
                <div
                        class="flex flex-col md:gap-8 lg:gap-4 xl:gap-8 lg:max-w-[454px]">
                    <h2 class="text-3xl md:text-[44px] leading-[1.5] font-medium">
                        Honey, we're not cooking tonight
                    </h2>
                    <p class="text-muted text-base md:text-lg">
                        Get the Apple-awarded Wolt app and choose from 40,000 restaurants
                        and hundreds of stores in 20+ countries. Discover and get what you
                        want – our courier partners bring it to you.
                    </p>
                    <div class="grid grid-cols-2 gap-8">
                        <a href="{{ $settings['follow_ios'] }}" class="block">
                            <img data-src="{{ url('assets/images/download_ios.svg') }}" class="w-full lazyload" />
                        </a>
                        <a href="{{ $settings['follow_android'] }}" class="block">
                            <img
                                    data-src="{{ url('assets/images/download_android.svg') }}"
                                    class="w-full lazyload" />
                        </a>
                    </div>
                </div>
                <div>
                    <img data-src="{{ url('assets/images/cta_banner.webp') }}" class="w-full lazyload" />
                </div>
            </div>
        </div>
    </section>


@endsection
@section('script')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            const testimonialSwiper = new Swiper(".testimonial-swiper", {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    400: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    1020: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                    },
                },
            });
        });
    </script>
@endsection