<style>
    #local-favorite .swiper-slide {
        padding-top: 3.125rem;
        padding-bottom: 3.75rem;
    }
</style>

<section id="local-favorite" class="section-top-padding">
    <div class="flex items-center justify-between responsive-px">
        <h2 class="text-3xl md:text-4xl font-medium">{{ __('theme::web.home_favorite_title') }}</h2>
        <a href="{{ url('foods') }}" class="flex items-center text-primary">
            {{ __('theme::web.view_all_dish') }}
            <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" />
        </a>
    </div>

    <div class="swiper local-favorites-slider responsive-px">
        <div class="swiper-wrapper">
            <!-- Start of one local favorite item -->
            @foreach($productsFavorite as $itemPV)
            <div class="swiper-slide">
                <div data-id="{{ $itemPV->id }}"
                    class="selectProduct card-base p-2 rounded-xl cursor-pointer w-full fd-item relative block">
                    <!-- Product Image with responsive sizing -->
                    <div class="relative rounded-xl overflow-hidden">

                        <img alt="{{ $itemPV->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                            data-src="{{ url($itemPV->image) }}"
                            class="rounded-xl object-cover w-full lazyload" 
                            style="aspect-ratio: 258 / 225;"  
                        />

                        <!-- Top badges and icons layer with responsive spacing -->
                        <div style="background-color: rgba(0, 0, 0, 0.2);" class="absolute inset-0 p-2 z-10 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span style="background-color: rgba(0, 0, 0, 0.15);" class="w-9 h-9 flex rounded-full favoriteIcon"
                                    data-id="{{ $itemPV->id }}">
                                    <img alt="Fast Ship Hu"
                                    data-src="{{ url(($itemPV->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                    class="m-auto lazyload h-6 w-6">
                                </span>
                                <div class="flex items-center flex-col md:flex-row gap-1">
                                    <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-2 md:py-1.5 flex items-center text-sm gap-1">
                                        <img alt="Fast Ship Hu"
                                            data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                            class="w-5 h-5 lazyload" />
                                        20% off
                                    </span>
                                    <span class="bg-warning text-white rounded-full py-1 px-2.5 md:px-2 md:py-1.5 flex items-center text-sm gap-1">
                                        <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-5 h-5 lazyload" />
                                        15-20 min
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store info and rating with responsive text -->
                    <div class="flex flex-col gap-2 mt-5">
                        <div class="flex items-center gap-2">
                            <span class="flex items-center capitalize gap-1 text-muted">
                                <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                    class="w-7 h-7 lazyload rounded-full" width="28" height="28"
                                    data-src="{{ url(optional($itemPV->store)->avatar_image) }}" />
                                <span class=" line-clamp-1">{{ optional($itemPV->store)->name }}</span>
                            </span>
                            <span class="flex items-center capitalize gap-1 text-secondary">
                                <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_rating.svg') }}"
                                class="w-5 h-5 lazyload" />
                                <span>{{ $itemPV->averageRating() }}</span>
                            </span>
                        </div>

                        <!-- Product details with responsive text sizing -->
                        <h3 class="font-medium text-lg leading-[1.5] md:text-[22px] md:leading-snug capitalize text-start line-clamp-1">
                            {{ $itemPV->name }}
                        </h3>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 flex items-center gap-1 text-lg">
                                <span class="text-muted line-through">{{ number_format($itemPV->price + 5, 1, '.', '') }}&nbsp;Ft</span>
                                <span class="text-secondary font-medium">{{ number_format($itemPV->price, 1, '.', '') }}&nbsp;Ft</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <img alt="Fast Ship Hu" src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                                    class="w-6 h-6 lazyload">
                                <span>{{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, optional($itemPV->store)->lat, optional($itemPV->store)->lng)['distance_km'] }} km</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>