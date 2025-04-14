<section id="popular-category">
    <div class="flex flex-col gap-10">
        <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <h2 class="capitalize text-3xl md:text-4xl font-medium">
                {{ __('theme::web.popular_categories') }}
            </h2>
        </div>
        <div class="swiper popular-categories-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <div class="swiper-wrapper">
                @foreach ($popularCategories as $keyC => $itemC)
                    <div class="swiper-slide rounded-2xl pb-6">
                        <div data-id="{{ $itemC->id }}" class="selectCategory {{ ($keyC == 0) ? 'border-2 border-solid border-primary' : '' }} relative rounded-2xl bg-white p-4 mt-3 flex flex-col gap-8 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
                            <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
                            <img alt="{{ $itemC->name_en }}" data-src="{{ url($itemC->image) }}" width="120" height="96" class="lazyload"  onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"/>
                            <div class="flex flex-col gap-1 items-center justify-center">
                                <h3 class="font-medium text-lg">{{ $itemC->name_en }}</h3>
                                <p class="text-secondary capitalize">{{ count($itemC->stores) }}&nbsp;{{ __('theme::web.place') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>