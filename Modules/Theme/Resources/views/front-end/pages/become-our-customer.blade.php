@extends('theme::front-end.master')
@section('title')
<title>{{ __('Become our customer') }}</title>
<meta name="description"
    content="{{ __('Become our customer') }}" />
<meta name="keywords" content="{{ __('Become our customer') }}" />
@endsection
@section('style')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #f0f9eb 0%, #e8f5e0 100%);
    }

    .faq-item {
        border-bottom: 1px solid #e2e8f0;
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .faq-answer.active {
        max-height: 500px;
    }

    input.placeholder-white::placeholder {
        color: #E6FBDA;
    }
</style>
@endsection
@section('content')
<section class="responsive-px bg-[#F1EFE9] ">
    <div class="flex flex-col lg:flex-row md:items-center gap-16">
        <div class="flex flex-col md:gap-8 lg:gap-4 xl:gap-8 py-16 lg:max-w-[454px]">
            <h2 class="text-3xl md:text-[44px] leading-[1.5] font-medium">
                {{ __('theme::web.driver_banner_title') }}
            </h2>
            <p class="text-muted text-base md:text-lg">
                {{ __('theme::web.driver_banner_description') }}
            </p>
            <div class="grid grid-cols-2 gap-8">
                <a href="{{ $settings['follow_ios_driver'] }}" class="block">
                    <img data-src="{{ url('assets/images/download_ios.svg') }}" class="w-full lazyload" />
                </a>
                <a href="{{ $settings['follow_android_driver'] }}" class="block">
                    <img data-src="{{ url('assets/images/download_android.svg') }}"
                        class="w-full lazyload" />
                </a>
            </div>
        </div>
        <div>
            <img data-src="{{ url('assets/images/become-our-customer.webp') }}" class="w-full lazyload" />
        </div>
    </div>
</section>

<!-- Why Use Us Section -->
<section class="mt-[80px] mb-[80px]">
    <div class="responsive-px">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="w-full flex justify-center md:justify-start">
                <h2 class="text-5xl font-medium text-left mb-4">
                    {{ __('theme::web.driver_download_left') }}
                </h2>
            </div>
            <div class="w-full flex flex-col justify-center md:justify-start">
                <p class="text-gray-600 text-center md:text-left max-w-lg mb-4">
                    {{ __('theme::web.driver_download_right') }}
                </p>
                <div class="">
                    <a href="{{ $settings['follow_ios_driver'] }}" class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                        <p class="mr-2">{{ __('theme::web.download_app') }}</p>
                        <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="brightness-[100] lazyload" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-[80px] mt-2 responsive-px">
    <div class="flex flex-col gap-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
            <div class="rounded-2xl px-4 pt-8 flex flex-col bg-[#D7EBCC] justify-between text-center">
                <div class="flex flex-col gap-3">
                    <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-[#538D33]">
                        {{ __('theme::web.driver_section1_title') }}
                    </h3>
                    <p class="text-[#3C3836] text-sm md:text-base pb-8">
                        {{ __('theme::web.driver_section1_description') }}
                    </p>
                </div>
                <img data-src="{{ url('assets/images/delivery1.png') }}" class="mx-auto w-[75%] lazyload" />
            </div>

            <div class="rounded-2xl flex flex-col pb-8 bg-[#D7EBCC] justify-between text-center">
                <img data-src="{{ url('assets/images/delivery2.png') }}" class="mx-auto w-[75%] lazyload" />
                <div class="flex flex-col gap-3">
                    <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-[#538D33]">
                        {{ __('theme::web.driver_section2_title') }}
                    </h3>
                    <p class="text-[#3C3836] text-sm md:text-base">
                        {{ __('theme::web.driver_section2_description') }}
                    </p>
                </div>
            </div>

            <div class="rounded-2xl px-4 pt-8 flex flex-col bg-[#D7EBCC] justify-between text-center">
                <div class="flex flex-col gap-3 ">
                    <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-[#538D33]">
                        {{ __('theme::web.driver_section3_title') }}
                    </h3>
                    <p class="text-[#3C3836] text-sm md:text-base pb-8">
                        {{ __('theme::web.driver_section3_description') }}
                    </p>
                </div>
                <img
                    data-src="{{ url('assets/images/delivery3.png') }}"
                    class="mx-auto w-[75%] lazyload" />
            </div>
        </div>
    </div>
</section>

<section class="responsive-px mb-[80px]">
    <h2 class="text-5xl font-medium text-left mb-10">{{ __('theme::web.driver_work_title') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Feature 1 -->
        <div class="bg-[#F9F8F6] p-4 rounded-xl shadow-sm">
            <div class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <img data-src="{{ url('assets/icons/icon_d1.svg') }}" class="lazyload" />
            </div>
            <p class="text-gray-600 mb-1">{{ __('theme::web.driver_work_section1_step') }}</p>
            <h3 class="text-xl font-semibold mb-3">{{ __('theme::web.driver_work_section1_title') }}</h3>
            <p class="text-gray-600">
                {{ __('theme::web.driver_work_section1_description') }}
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-[#F9F8F6] p-6 rounded-lg shadow-sm">
            <div class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <img data-src="{{ url('assets/icons/icon_d2.svg') }}" class="lazyload" />
            </div>
            <p class="text-gray-600 mb-1">{{ __('theme::web.driver_work_section2_step') }}</p>
            <h3 class="text-xl font-semibold mb-3">{{ __('theme::web.driver_work_section2_title') }}</h3>
            <p class="text-gray-600">
                {{ __('theme::web.driver_work_section2_description') }}
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-[#F9F8F6] p-6 rounded-lg shadow-sm">
            <div
                class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <img data-src="{{ url('assets/icons/icon_d3.svg') }}" class="lazyload" />
            </div>
            <p class="text-gray-600 mb-1">{{ __('theme::web.driver_work_section3_step') }}</p>
            <h3 class="text-xl font-semibold mb-3">{{ __('theme::web.driver_work_section3_title') }}</h3>
            <p class="text-gray-600">
                {{ __('theme::web.driver_work_section3_description') }}
            </p>
        </div>

        <!-- Feature 4 -->
        <div class="bg-[#F9F8F6] p-6 rounded-lg shadow-sm">
            <div
                class="bg-orange-500 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <img data-src="{{ url('assets/icons/icon_d3.svg') }}" class="lazyload" />
            </div>
            <p class="text-gray-600 mb-1">{{ __('theme::web.driver_work_section4_step') }}</p>
            <h3 class="text-xl font-semibold mb-3">{{ __('theme::web.driver_work_section4_title') }}</h3>
            <p class="text-gray-600">
                {{ __('theme::web.driver_work_section4_description') }}
            </p>
        </div>
    </div>
</section>

<section id="how-we-work" class="mb-[80px] responsive-px">
    <div class="flex flex-col gap-10">
        <div class="flex flex-col items-center justify-center p-4 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 w-full mx-auto">
                <!-- Left Column -->
                <div class="md:col-span-3 flex flex-col gap-4">
                    <!-- First Card -->
                    <div class="rounded-2xl px-4 flex flex-col justify-between">
                        <div class="flex flex-col gap-3">
                            <img
                                data-src="{{ url('assets/images/how_we_work_img_1.svg') }}"
                                class="mx-auto w-[75%] lazyload" />
                            <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-black">
                                {{ __('theme::web.driver_risk_section1_title') }}
                            </h3>
                            <p class="text-muted text-sm md:text-base">
                                {{ __('theme::web.driver_risk_section1_description') }}
                            </p>
                        </div>
                    </div>

                    <!-- Second Card -->
                    <div class="rounded-xl flex flex-col">
                        <div class="rounded-2xl px-4 flex flex-col justify-between">
                            <div class="flex flex-col gap-3">
                                <img
                                    data-src="{{ url('assets/images/how_we_work_img_4.svg') }}"
                                    class="mx-auto w-[75%] lazyload" />
                                <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-black">
                                    {{ __('theme::web.driver_risk_section4_title') }}
                                </h3>
                                <p class="text-muted text-sm md:text-base">
                                    {{ __('theme::web.driver_risk_section4_description') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Column (Phone) -->
                <div class="md:col-span-6 flex items-center justify-center">
                    <div class="relative">
                        <img
                            data-src="{{ url('assets/images/iphone15.svg') }}"
                            class="mx-auto w-full lazyload" />
                        <div class="text-center">
                            <a
                                href="{{ $settings['follow_ios_driver'] }}"
                                class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                                {{ __('theme::web.download_app') }}
                                <img
                                    data-src="{{ url('assets/icons/up_right_icon.svg') }}"
                                    class="brightness-[100] lazyload" />
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="md:col-span-3 flex flex-col gap-4">
                    <!-- First Card -->
                    <div class="rounded-xl flex flex-col">
                        <div class="rounded-2xl px-4 flex flex-col justify-between">
                            <div class="flex flex-col gap-3">
                                <img data-src="{{ url('assets/images/how_we_work_img_3.svg') }}" class="mx-auto w-[75%] lazyload" />
                                <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-black">
                                    {{ __('theme::web.driver_risk_section2_title') }}
                                </h3>
                                <p class="text-muted text-sm md:text-base">
                                    {{ __('theme::web.driver_risk_section2_description') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Second Card -->
                    <div class="p-6 flex flex-col">
                        <div class="rounded-2xl px-4 flex flex-col justify-between">
                            <div class="flex flex-col gap-3">
                                <img data-src="{{ url('assets/images/how_we_work_img_2.svg') }}" class="mx-auto w-[75%] lazyload" />
                                <h3 class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-black">
                                    {{ __('theme::web.driver_risk_section3_title') }}
                                </h3>
                                <p class="text-muted text-sm md:text-base">
                                    {{ __('theme::web.driver_risk_section3_description') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Olive Section -->
<section class="responsive-px mb-[80px]">
    <div class="flex flex-col lg:flex-row gap-16">
        <div class="flex-1">
            <img data-src="{{ url('assets/images/banner_delivery.svg') }}" class="w-full lazyload" />
        </div>
        <div class="flex flex-1 flex-col justify-between">
            <p class="text-secondary">{{ __('theme::web.driver_article_title') }}</p>
            <div class="flex flex-col gap-8">
                <h2 class="text-3xl md:text-[36px] leading-[1.5] font-medium">
                    {{ __('theme::web.driver_article_title_lg') }}
                </h2>
                <p class="text-muted text-base md:text-lg">
                    {{ __('theme::web.driver_article_description') }}
                </p>
                <div>
                    <a href="{{ $settings['follow_ios_driver'] }}" class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                        <p class="mr-2">{{ __('theme::web.download_app') }}</p> 
                    <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="brightness-[100] lazyload" />
                    </a>
                </div>
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
                    {{ __('theme::web.partner_get_left1') }}<br />
                    {{ __('theme::web.partner_get_left2') }}
                </h2>
            </div>

            <!-- Phần bên phải (Input + Button) -->
            <div class="w-full md:w-2/3">
                <p class="mb-6">
                    {{ __('theme::web.partner_get_right') }}
                </p>
                <div
                    class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <div class="flex w-full sm:w-2/3 space-x-4">
                        <input type="text" placeholder="Enter Your work email"
                            class="text-black w-full pl-4 py-3 md:py-2.55 rounded-full border border-[#74CA45] focus:outline-none focus:ring-1 bg-transparent placeholder-white" />
                    </div>
                    <div class="flex w-full sm:w-1/3">
                        <button class="inline-flex items-center py-3 md:py-2.55 px-6 rounded-full bg-white border-[1px] border-primary primary text-primary">
                            {{ __('theme::web.get_start') }}
                            <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="ml-2 lazyload" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Use Us Section -->
<section class="mb-[80px]">
    <div class="responsive-px">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="">
                <h2 class="text-4xl font-medium text-left mb-8">
                    {{ __('theme::web.driver_question_title') }}
                </h2>
                <p class="text-gray-600 text-center md:text-left mb-8 max-w-lg">
                    {{ __('theme::web.driver_question_description') }}
                </p>
                <div class="text-left mt-4">
                    <a href="{{ $settings['follow_ios_driver'] }}" class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                        {{ __('theme::web.download_app') }}
                        <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="brightness-[100] lazyload" />
                    </a>
                </div>
            </div>
            <div class="w-full flex flex-col justify-center md:justify-start">
                <div class="faq-container">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item py-4">
                        <div
                            class="faq-question flex justify-between items-center cursor-pointer">
                            <h3 class="text-lg">
                                How long does the application process take?
                            </h3>
                            <span class="faq-icon text-[#363853] text-2xl">+</span>
                        </div>
                        <div class="faq-answer mt-2 text-gray-600 shado-md bg-[#F9F8F6] rounded-lg">
                            <p class="mb-2 p-4">
                                Typically, it takes about 20 minutes to fill out the
                                registration. If you'd like to skip a section, you'll have
                                to save the application and come back to it later. Once
                                you've submitted your application, you'll be contacted
                                within 24-48 hours.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="faq-item py-4">
                        <div
                            class="faq-question flex justify-between items-center cursor-pointer">
                            <h3 class="text-lg">
                                Are there other requirements to deliver?
                            </h3>
                            <span class="faq-icon text-[#363853] text-2xl">+</span>
                        </div>
                        <div class="faq-answer mt-2 text-gray-600 shado-md bg-[#F9F8F6] rounded-lg">
                            <p class="mb-2 p-4">
                                You'll need to be 18 years or older, and in possession of a
                                valid work permit. Additional requirements may apply
                                depending on your method of delivery, such as valid license
                                and insurance for car and motorcycle deliveries. Delivery
                                documents are verified through your account.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="faq-item py-4">
                        <div
                            class="faq-question flex justify-between items-center cursor-pointer">
                            <h3 class="text-lg">
                                How long does the application process take?
                            </h3>
                            <span class="faq-icon text-[#363853] text-2xl">+</span>
                        </div>
                        <div class="faq-answer mt-2 text-gray-600 shado-md bg-[#F9F8F6] rounded-lg">
                            <p class="mb-2 p-4">
                                Typically, it takes about 20 minutes to fill out the
                                registration. If you'd like to skip a section, you'll have
                                to save the application and come back to it later. Once
                                you've submitted your application, you'll be contacted
                                within 24-48 hours.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="faq-item py-4">
                        <div
                            class="faq-question flex justify-between items-center cursor-pointer">
                            <h3 class="text-lg">
                                How long does the application process take?
                            </h3>
                            <span class="faq-icon text-[#363853] text-2xl">+</span>
                        </div>
                        <div class="faq-answer mt-2 text-gray-600 shado-md bg-[#F9F8F6] rounded-lg">
                            <p class="mb-2 p-4">
                                Typically, it takes about 20 minutes to fill out the
                                registration. If you'd like to skip a section, you'll have
                                to save the application and come back to it later. Once
                                you've submitted your application, you'll be contacted
                                within 24-48 hours.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const faqQuestions = document.querySelectorAll(".faq-question");
        faqQuestions.forEach((question) => {
            question.addEventListener("click", function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector(".faq-icon");
                answer.classList.toggle("active");
                if (answer.classList.contains("active")) {
                    icon.textContent = "−";
                } else {
                    icon.textContent = "+";
                }
            });
        });
    });
</script>
@endsection