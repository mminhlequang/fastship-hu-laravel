<style>
    #popular-category .swiper-slide {
        padding-top: 3.125rem;
        padding-bottom: 3.75rem;
        width: 175px !important;
    }

    .popular-button {
        border: 1px solid #EEEEEE;
        background: #FFFFFF;
        backdrop-filter: blur(20px);
    }

    .popular-button.active {
        background: #EAF9E2;
        border-color: #EAF9E2;
    }
</style>

<section id="popular-category" class="section-top-padding">
    <div>
        <div class="responsive-px flex items-center gap-6">
            <h2 class="flex-1 line-clamp-1 capitalize text-3xl md:text-4xl font-medium text-black">
                {{ __('theme::web.popular_categories') }}
            </h2>

            <div class="flex items-center gap-6">
                <button class="popular-button w-12 h-12 p-3 flex items-center justify-center rounded-full">
                    <!-- active -->
                    <!-- <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/left-chevron-active.svg') }}" class="w-6 h-6 lazyload" /> -->
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/left-chevron.svg') }}" class="w-6 h-6 lazyload" />
                </button>
                <button class="popular-button active w-12 h-12 p-3 flex items-center justify-center rounded-full">
                  <!-- active -->
                  <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/right-chevron-active.svg') }}" class="w-6 h-6 lazyload" />
                  <!-- <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/right-chevron.svg') }}" class="w-6 h-6 lazyload" /> -->
                </button>
                <button style="padding: 12px 14px" class="popular-button h-12 gap-3 flex items-center justify-center rounded-full">
                    <!-- active -->
                    <!-- <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/sort-icon-active.svg') }}" class="w-6 h-6 lazyload" /> -->
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/sort-icon.svg') }}" class="w-6 h-6 lazyload" />
                    <span>Sort by</span>
                </button>
            </div>
        </div>
        <div class="swiper popular-categories-slider responsive-px">
            <div class="swiper-wrapper">
                @foreach ($popularCategories as $keyC => $itemC)
                    <div class="swiper-slide">
                        <div data-id="{{ $itemC->id }}" class="selectCategory card-base relative rounded-2xl bg-white px-2 py-3 flex flex-col gap-3">
                            <img alt="{{ $itemC->name_en }}" data-src="{{ url($itemC->image) }}" style="aspect-ratio: 159 / 113;" class="lazyload object-cover rounded-xl"  onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                            />

                            <div class="flex flex-col gap-1 items-center justify-center w-full text-center">
                                <h3 class="font-medium text-lg line-clamp-1">{{ $itemC->name_en }}</h3>
                                <p class="text-secondary capitalize">{{ count($itemC->stores) }}&nbsp;{{ __('theme::web.place') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>