<section id="top-rated" class="section-padding">
    <div class="flex flex-col gap-6">
        <div class="responsive-px">
            <h2 class="capitalize text-3xl md:text-4xl font-medium">
                {{ __('theme::web.home_top_rated_title') }}
            </h2>
        </div>
        <!-- Top Rated Slider -->
        <div class="swiper top-rated-slider responsive-px">
            <div class="swiper-wrapper pt-4">
                <!-- Static Top Rated Item 1 -->
                @foreach($productsTopRate as $itemPT)
                <div class="swiper-slide pb-6">
                    <div data-id="{{ $itemPT->id }}"
                        class="selectProduct card-base p-2 rounded-xl cursor-pointer fd-item relative block w-full">
                        <div class="relative rounded-xl overflow-hidden">
                            <img alt="{{ $itemPT->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                data-src="{{ url($itemPT->image) }}"
                                style="aspect-ratio: 358 / 225;"
                                class="lazyload rounded-2xl object-cover w-full" />
                            <div style="background-color: rgba(0, 0, 0, 0.2);" class="absolute inset-0 p-2 z-10">
                                <div class="flex items-center justify-between">
                                    <span style="background-color: rgba(0, 0, 0, 0.15);" class="w-9 h-9 flex rounded-full favoriteIcon"
                                        data-id="{{ $itemPT->id }}">
                                        <img alt="Fast Ship Hu"
                                            data-src="{{ url(($itemPT->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                            class="m-auto lazyload h-6 w-6">
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <span class="bg-secondary text-white rounded-full px-2 py-1.5 flex items-center text-sm gap-1">
                                            <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                                class="w-5 h-5 lazyload" />
                                           <span>20% off</span>
                                        </span>
                                        <span class="bg-warning text-white rounded-full px-2 py-1.5 flex items-center text-sm gap-1">
                                            <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/clock_icon.svg') }}"
                                                class="w-5 h-5 lazyload" />
                                            <span>15-20 min</span>
                                        </span>
                                    </div>
                                </div>      
                            </div> 
                        </div>
                        <div class="flex flex-col gap-2 mt-5">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center capitalize gap-1 text-muted">
                                    <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" 
                                        class="w-7 h-7 lazyload rounded-full" width="28" height="28"
                                        data-src="{{ url(optional($itemPT->store)->avatar_image) }}" />
                                    <span class="truncate">{{ optional($itemPT->store)->name }}</span>
                                </span>
                                <span class="flex items-center capitalize gap-1 text-secondary">
                                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_rating.svg') }}"
                                    class="w-5 h-5 lazyload" />
                                    <span>{{ $itemPT->averageRating() }}</span>
                                </span>
                            </div>
                            <h3 class="font-medium text-lg leading-[1.5] md:text-[22px] md:leading-snug capitalize text-start">
                                {{ $itemPT->name }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 flex items-center gap-1 text-lg">
                                    <span class="text-muted line-through">$ {{ number_format($itemPT->price + 5, 2) }}</span>
                                    <span class="text-secondary font-medium">$ {{ number_format($itemPT->price, 2) }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                                        class="w-6 h-6 lazyload" />
                                    <span>{{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, optional($itemPT->store)->lat, optional($itemPT->store)->lng)['distance_km'] }} km</span>
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