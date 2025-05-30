<style>
    #top-rated .swiper-slide {
        padding-top: 2.5rem;
        padding-bottom: 3.75rem;
    }
</style>

<section id="top-rated" class="flex flex-wrap flex-col gap-6 mb-[50px]">
    <div class="responsive-px">
        <h2 class="capitalize text-3xl md:text-4xl font-medium">
            {{ __('theme::web.home_top_rated_title') }}
        </h2>
    </div>
    <!-- Top Rated Slider -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 responsive-px">
        @foreach($productsTopRate as $item)
            @include('theme::front-end.components.product')
        @endforeach
    </div>
</section>