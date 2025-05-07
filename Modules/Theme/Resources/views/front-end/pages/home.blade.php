@extends('theme::front-end.master')

@section('content')
<main>
    @include('theme::front-end.home.banner')
    @include('theme::front-end.home.popular')
    <section id="fastest-delivery" class="section-padding relative">
        <div class="flex flex-wrap flex-col gap-10">
            <div class="flex flex-wrap items-center justify-between responsive-px">
                <h2 class="capitalize text-3xl md:text-4xl font-medium">{{ __('theme::web.home_fast_title') }}</h2>
                <a href="{{ url('foods') }}" class="flex items-center text-primary">{{ __('theme::web.view_all_dish') }}
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" /></a>
            </div>
            <div id="sectionFastest"
                class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 responsive-px">
                @include('theme::front-end.home.fastest')
            </div>
        </div>
        <div class="absolute bottom-[-100px] right-0">
            <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/heart_deco_icon.png') }}" class="h-[110px] lazyload" />
        </div>
    </section>

    <section id="discount" class="section-padding">
        <div class="py-6 flex flex-col gap-10">
            <div class="flex items-start md:items-center justify-between flex-col md:flex-row responsive-px gap-4 md:gap-0">
                <h2 class="capitalize text-3xl md:text-4xl font-medium">
                    {{ __('theme::web.home_discount_title') }} 👌
                </h2>
                <div id="sectionCategories" class="flex items-center gap-3 md:gap-6">
                    @include('theme::front-end.home.discount_categories')
                </div>
            </div>
            <div class="swiper discount-slider responsive-px">
                <div class="swiper-wrapper" id="sectionDiscount">
                    @include('theme::front-end.home.discount')
                </div>
            </div>
        </div>
    </section>
    @include('theme::front-end.home.rate')
    @include('theme::front-end.home.favorite')
    @include('theme::front-end.home.partner')
    @include('theme::front-end.home.work')
    @include('theme::front-end.home.download')
    @include('theme::front-end.home.customer')
    @include('theme::front-end.home.order')
    @include('theme::front-end.home.blog')
</main>
@endsection
@section('script')
<script src="{{ url('assets/js/cutsomer-logo-slider.js') }}"></script>
<script src="{{ url('assets/js/discount-slider.js') }}"></script>
<script src="{{ url('assets/js/top-rated-slider.js') }}"></script>
<script src="{{ url('assets/js/local-favorite-slider.js') }}"></script>
<script type="text/javascript">
    $('body').on('click', '.selectCategory', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.loading').addClass('loader');
        $('.selectCategory').removeClass('shadow-md');
        $(this).addClass('shadow-md');

        $.ajax({
            url: "{{ url('ajaxFE/searchDataHome') }}",
            type: "GET",
            data: {
                categories: id
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-Type', 'application/json');
            },
            success: function(res) {
                $('#sectionFastest').html(res.view1);
                $('#sectionDiscount').html(res.view2);
                $('#sectionCategories').html(res.view3);;
                $('.loading').removeClass('loader');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.log(xhr);
                $('.loading').removeClass('loader');
            }
        });
    });
    $('body').on('click', '.selectCategoryChild', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.loading').addClass('loader');
        $('.selectCategory').removeClass('text-black').removeClass('text-muted');
        $(this).addClass('text-black').removeClass('text-muted');
        $.ajax({
            url: "{{ url('ajaxFE/getStoreByCategory') }}",
            type: "GET",
            data: {
                categories: id
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-Type', 'application/json');
            },
            success: function(res) {
                $('#sectionDiscount').html(res.view);;
                $('.loading').removeClass('loader');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.log(xhr);
                $('.loading').removeClass('loader');
            }
        });
    });
</script>
@endsection