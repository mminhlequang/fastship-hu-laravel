<style>
    #local-favorite .swiper-slide {
        padding-top: 3.125rem;
        padding-bottom: 3.75rem;
    }
</style>

<section id="local-favorite" class="flex flex-wrap flex-col gap-6 mb-[50px]" >
    <div class="flex items-center justify-between responsive-px">
        <h2 class="text-3xl md:text-4xl font-medium">{{ __('theme::web.home_favorite_title') }}</h2>
        <a href="{{ url('foods') }}" class="flex items-center text-primary">
            {{ __('theme::web.view_all_dish') }}
            <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" />
        </a>
    </div>
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 responsive-px">
        @foreach($productsFavorite as $item)
            @include('theme::front-end.components.product')
        @endforeach
    </div>
</section>