<section id="great-customer" class="section-padding">
    <div class="responsive-px flex flex-col gap-10">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl md:text-4xl font-medium">{{ __('theme::web.home_favorite_title') }}</h2>
            <a href="{{ url('foods') }}" class="flex items-center text-primary">
                {{ __('theme::web.view_all_dish') }}
                <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" />
            </a>
        </div>
        <div
            class="swiper great-customer-slider">
            <div class="swiper-wrapper">
                <!-- Slides will be populated by JavaScript -->
            </div>
        </div>
    </div>
</section>