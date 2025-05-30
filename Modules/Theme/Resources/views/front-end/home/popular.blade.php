<style>
    #popular-category .swiper-slide {
        padding-top: 1.125rem;
    }

    #popular-category .swiper-slide img {
        width: 120px;
        height: 95px;
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

    .popular-categories-slider {
        overflow: hidden;
        max-width: 100%;
        width: 100%;
        padding-left: 0;
    }

</style>

<section id="popular-category">
    <div>
        <div class="responsive-px flex items-center gap-6">
            <h2 class="flex-1 line-clamp-1 capitalize text-3xl md:text-4xl font-medium text-black">
                {{ __('theme::web.popular_categories') }}
            </h2>

            <div class="flex items-center gap-6">
                <button class="custom-btn-prev-slide popular-button w-12 h-12 p-3 flex items-center justify-center rounded-full">
                    <!-- active -->
                <!-- <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/left-chevron-active.svg') }}" class="w-6 h-6 lazyload" /> -->
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/left-chevron.svg') }}"
                         class="w-6 h-6 lazyload"/>
                </button>
                <button class="custom-btn-next-slide popular-button w-12 h-12 p-3 flex items-center justify-center rounded-full">
                    <!-- active -->
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/right-chevron.svg') }}"
                         class="w-6 h-6 lazyload"/>
                </button>
                <button onclick="toggleModal('modalOverlayFilter')" style="padding: 12px 14px"
                        class="popular-button h-12 gap-3 flex items-center justify-center rounded-full">
                    <!-- active -->
                <!-- <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/sort-icon-active.svg') }}" class="w-6 h-6 lazyload" /> -->
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/sort-icon.svg') }}"
                         class="w-6 h-6 lazyload"/>
                    <span>Sort by</span>
                </button>
            </div>
        </div>
        <div class="responsive-px">
            <div class="swiper popular-categories-slider responsive-px max-w-full overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($popularCategories as $keyC => $itemC)
                        <div class="swiper-slide rounded-2xl cursor-pointer overflow-visible">
                            <div data-id="{{ $itemC->id }}" class="selectCategory relative rounded-2xl bg-white p-4 mb-8 mt-2 flex flex-col gap-8 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45] hover:z-[9999]">
                                <img data-src="{{ url($itemC->image) }}" class="w-[120px] h-[96px] lazyload" alt="Food Category" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"/>
                                <div class="flex flex-col gap-1 items-center justify-center">
                                    <h3 class="font-medium text-lg" title="{{ \App\Helper\LocalizationHelper::getNameByLocale($itemC) }}">{{ str_limit(\App\Helper\LocalizationHelper::getNameByLocale($itemC), 10, '...') }}</h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>