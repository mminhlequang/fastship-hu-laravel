<section id="local-favorite" class="py-12">
    <div class="flex flex-col gap-10">
        <div
                class="flex items-center justify-between px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <h2 class="text-3xl md:text-4xl font-medium">Local favorites</h2>
            <a href="#" class="flex items-center text-primary">
                View all dishes
                <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload"/>
            </a>
        </div>

        <div class="swiper local-favorites-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <div class="swiper-wrapper pt-6">
                <!-- Start of one local favorite item -->
                @foreach($productsFavorite as $itemPV)
                    <div class="swiper-slide">
                        <a href="{{ url('product/'.$itemPV->slug.'.html') }}"
                           class="w-full fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu">
                            <!-- Product Image with responsive sizing -->
                            <div class="relative ">
                                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                     data-src="{{ url($itemPV->image) }}"
                                     class="aspect-square rounded-2xl object-cover w-full lazyload"/>

                                <!-- Top badges and icons layer with responsive spacing -->
                                <div class="p-2 sm:p-3 absolute top-0 left-0 right-0 flex items-center justify-between z-10">
                                <span class="w-8 h-8 sm:w-9 sm:h-9 flex rounded-full bg-black/30 hover:bg-black/50 transition-colors">
                                    <img data-src="{{ url('assets/icons/heart_line_icon.svg') }}"
                                         class="m-auto lazyload w-4 sm:w-5"/>
                                </span>
                                    <div class="flex items-center gap-1 sm:gap-2 flex-wrap justify-end">
                                    <span class="bg-secondary text-white rounded-full px-2 sm:px-3 py-1 sm:py-1.5 flex items-center text-xs sm:text-sm gap-1">
                                        <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                             class="w-4 h-4 sm:w-6 sm:h-6 lazyload"/>
                                        <span class="hidden xs:inline">20% off</span>
                                        <span class="xs:hidden">20%</span>
                                    </span>
                                        <span class="bg-warning text-white rounded-full px-2 sm:px-3 py-1 sm:py-1.5 flex items-center text-xs sm:text-sm gap-1">
                                        <img data-src="{{ url('assets/icons/clock_icon.svg') }}"
                                             class="w-4 h-4 sm:w-6 sm:h-6 lazyload"/>
                                        <span>15-20 min</span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Store info and rating with responsive text -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-1 mt-2 sm:mt-3 mb-1">
                        <span class="flex items-center capitalize gap-1.5 text-muted text-sm sm:text-base">
                            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                 class="w-5 h-5 sm:w-7 sm:h-7 lazyload"
                                 data-src="{{ url(optional($itemPV->store)->avatar_image) }}"/>
                            <span class="truncate max-w-[120px] sm:max-w-[150px]">{{ optional($itemPV->store)->name }}</span>
                        </span>
                                <span class="flex items-center capitalize gap-1 text-secondary text-sm sm:text-base">
            <span class="flex items-center">
                @for($i = 1; $i <= floor($itemPV->averageRating()); $i++)
                    <img data-src="{{ url('assets/icons/star_rating.svg') }}"
                         class="w-3 h-3 lazyload"/>
                @endfor

                @if($itemPV->averageRating() - floor($itemPV->averageRating()) >= 0.5)
                    <img data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                         class="w-3 h-3 lazyload"/>
                @endif

                @for($i = ceil($itemPV->averageRating()); $i < 5; $i++)
                    <img data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                         class="w-3 h-3 lazyload"/>
                @endfor
            </span>
            <span>{{ $itemPV->averageRating() }}</span>
        </span>
                            </div>

                            <!-- Product details with responsive text sizing -->
                            <div class="flex flex-col">
                                <h3 class="font-medium text-base leading-tight sm:text-lg md:text-[22px] md:leading-snug capitalize text-start line-clamp-2">
                                    {{ $itemPV->name }}
                                </h3>
                                <div class="flex items-center justify-between font-medium mt-1 sm:mt-2">
                                    <div class="flex items-center gap-1 text-base sm:text-lg">
                                        <span class="text-muted line-through text-sm sm:text-base">${{ number_format($itemPV->price + 5, 2) }}</span>
                                        <span class="text-secondary">${{ number_format($itemPV->price, 2) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 sm:gap-2 text-gray-400 text-xs sm:text-sm">
                                        <img data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                                             class="w-4 h-4 sm:w-6 sm:h-6 lazyload"/>
                                        <span>0.44 km</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</section>