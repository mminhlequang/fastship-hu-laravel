<section id="popular-category" class="section-padding">
    <div class="flex flex-col gap-10">
        <div class="responsive-px">
            <h2 class="capitalize text-3xl md:text-4xl font-medium text-black">
                {{ __('theme::web.popular_categories') }}
            </h2>
        </div>
        <div class="swiper popular-categories-slider responsive-px">
            <div class="swiper-wrapper">
                @foreach ($popularCategories as $keyC => $itemC)
                    <div class="swiper-slide rounded-2xl pb-6">
                        <div data-id="{{ $itemC->id }}" class="selectCategory card-base relative rounded-2xl bg-white px-2 py-3 mt-3 flex flex-col gap-3 w-full">
                            <img alt="{{ $itemC->name_en }}" data-src="{{ url($itemC->image) }}" class="lazyload rounded-xl aspect-[159/113]"  onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
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