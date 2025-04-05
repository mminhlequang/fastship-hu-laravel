@extends('theme::front-end.master')

@section('content')
    <main>
        @include('theme::front-end.home.banner')
        @include('theme::front-end.home.popular')
        <section id="fastest-delivery" class="py-6">
            <div class="flex flex-wrap flex-col gap-10">
                <div class="flex flex-wrap items-center justify-between px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    <h2 class="capitalize text-3xl md:text-4xl font-medium">Fastest delivery</h2>
                    <a href="{{ url('foods') }}" class="flex items-center text-primary"
                    >View all dishes
                        <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload"
                        /></a>
                </div>
                <div id="sectionFastest"
                     class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    @include('theme::front-end.home.fastest')
                </div>
            </div>
        </section>
        <div class="flex justify-end">
            <img data-src="{{ url('assets/icons/heart_deco_icon.png') }}" class="h-[110px] lazyload"/>
        </div>
        <section id="discount" class="py-6 flex flex-col gap-10">
            <div
                    class="flex items-start md:items-center justify-between flex-col md:flex-row px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 gap-4 md:gap-0"
            >
                <h2 class="capitalize text-3xl md:text-4xl font-medium">
                    Discount Guaranteed! 👌
                </h2>
                <div class="flex items-center gap-3 md:gap-6">
                    <a
                            href="#"
                            class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
                    >Vegan</a
                    >
                    <a
                            href="#"
                            class="capitalize font-medium text-dark text-sm md:text-base"
                    >Pizza & Fast food</a
                    >
                    <a
                            href="#"
                            class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
                    >Sushi</a
                    >
                    <a
                            href="#"
                            class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
                    >others</a
                    >
                    <a
                            href="{{ url('stores') }}"
                            class="capitalize flex items-center text-primary hover:opacity-70 text-sm md:text-base"
                    >
                        View all dishes
                        <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload"/>
                    </a>
                </div>
            </div>
            <div class="swiper discount-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                <div class="swiper-wrapper pt-6" id="sectionDiscount">
                    @include('theme::front-end.home.discount')
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
        $('body').on('click', '.selectCategory', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('.loading').addClass('loader');
            $('.selectCategory').removeClass('border-2 border-solid border-primary');
            $(this).addClass('border-2 border-solid border-primary');

            $.ajax({
                url: "{{ url('ajaxFE/searchDataHome') }}",
                type: "GET",
                data: {
                    categories: id
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-Type', 'application/json');
                },
                success: function (res) {
                    $('#sectionFastest').html(res.view1);
                    $('#sectionDiscount').html(res.view2);
                    loadSkeleton();
                    $('.loading').removeClass('loader');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log(xhr);
                    $('.loading').removeClass('loader');
                }
            });
        });


    </script>
@endsection