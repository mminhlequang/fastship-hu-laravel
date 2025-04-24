<section id="local-favorite" class="py-12">
    <div class="flex flex-col gap-10">
        <div class="flex items-center justify-between px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <h2 class="text-3xl md:text-4xl font-medium">{{ __('theme::web.home_favorite_title') }}</h2>
            <a href="{{ url('foods') }}" class="flex items-center text-primary">
                {{ __('theme::web.view_all_dish') }}
                <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload"/>
            </a>
        </div>

        <div class="swiper local-favorites-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <div class="swiper-wrapper pt-6">
                <!-- Start of one local favorite item -->
                @foreach($productsFavorite as $itemPV)
                    <div class="swiper-slide">
                        <div data-id="{{ $itemPV->id }}"
                           class="selectProduct cursor-pointer w-full fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu">
                            <!-- Product Image with responsive sizing -->
                            <div class="relative ">

                                <img alt="{{ $itemPV->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                     data-src="{{ url($itemPV->image) }}"
                                     class="aspect-square rounded-2xl object-cover w-full lazyload"/>

                                <!-- Top badges and icons layer with responsive spacing -->
                                <div class="p-2 sm:p-3 absolute top-0 left-0 right-0 flex items-center justify-between z-10">
                                    <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon"
                                          data-id="{{ $itemPV->id }}"><img alt="Fast Ship Hu"
                                                data-src="{{ url(($itemPV->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                                class="m-auto lazyload"></span>
                                    <div class="flex items-center flex-col md:flex-row gap-1">
                      <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                        <img alt="Fast Ship Hu"
                                data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                class="w-6 h-6 lazyload"
                        />
                        20% off
                      </span><span class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                        <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-6 h-6 lazyload"/>
                        15-20 min
                            </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Store info and rating with responsive text -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-1 mt-2 sm:mt-3 mb-1">
                        <span class="flex items-center capitalize gap-1.5 text-muted">
                            <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                 class="w-7 h-7 lazyload rounded-full" width="28" height="28"
                                 data-src="{{ url(optional($itemPV->store)->avatar_image) }}"/>
                            <span class="truncate max-w-[120px] sm:max-w-[150px]">{{ optional($itemPV->store)->name }}</span>
                        </span>
                                <span class="flex items-center capitalize gap-1.5 text-secondary">
            <span class="flex items-center">
                @for($i = 1; $i <= floor($itemPV->averageRating()); $i++)
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_rating.svg') }}"
                         class="w-3 h-3 lazyload"/>
                @endfor

                @if($itemPV->averageRating() - floor($itemPV->averageRating()) >= 0.5)
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                         class="w-3 h-3 lazyload"/>
                @endif

                @for($i = ceil($itemPV->averageRating()); $i < 5; $i++)
                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                         class="w-3 h-3 lazyload"/>
                @endfor
            </span>
            <span>{{ $itemPV->averageRating() }}</span>
        </span>
                            </div>

                            <!-- Product details with responsive text sizing -->
                            <div class="flex flex-col">
                                <h3 class="font-medium text-lg leading-[1.5] md:text-[22px] md:leading-snug capitalize text-start">
                                    {{ $itemPV->name }}
                                </h3>
                                <div class="flex items-center justify-between font-medium">
                                    <div class="flex items-center gap-1 text-lg">
                                        <span class="text-muted line-through">{{ number_format($itemPV->price + 5, 2) }}&nbsp;Ft</span>
                                        <span class="text-secondary">{{ number_format($itemPV->price, 2) }}&nbsp;Ft</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <img alt="Fast Ship Hu" src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                                             class="w-6 h-6 lazyload">
                                        <span>0.44 km</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</section>