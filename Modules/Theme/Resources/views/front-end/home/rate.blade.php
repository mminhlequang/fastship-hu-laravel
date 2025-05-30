<section id="top-rated" class="flex flex-wrap flex-col gap-6 mb-[50px]">
    <div class="responsive-px">
        <h2 class="capitalize text-3xl md:text-4xl font-medium">
            {{ __('theme::web.home_top_rated_title') }}
        </h2>
    </div>
    <!-- Top Rated Slider -->
    <div class="swiper top-rated-slider responsive-px">
        <div class="swiper-wrapper">
            <!-- Static Top Rated Item 1 -->
            @foreach($productsTopRate as $item)
                @include('theme::front-end.components.product')
            @endforeach
        </div>
    </div>

</section>