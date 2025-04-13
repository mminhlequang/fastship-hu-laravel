<section id="top-rated" class="py-12">
    <div class="flex flex-col gap-10">
        <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <h2 class="capitalize text-3xl md:text-4xl font-medium">
                {{ __('theme::web.home_top_rated_title') }}
            </h2>
        </div>
        <!-- Top Rated Slider -->
        <div class="swiper top-rated-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <div class="swiper-wrapper pt-6">
                <!-- Static Top Rated Item 1 -->
                @foreach($productsTopRate as $itemPT)
                    <div class="swiper-slide">
                        <a data-id="{{ $itemPT->id }}"
                           class="selectProduct cursor-pointer fd-item relative block w-full transition-all duration-500 hover:-translate-y-2 transform-gpu">
                            <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
                            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                 data-src="{{ url($itemPT->image) }}"
                                 class="lazyload aspect-[16/10] rounded-2xl object-cover w-full"/>
                            <div class="p-2 absolute top-0 left-0 right-0 flex items-center justify-between z-10">
                                <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon"
                                      data-id="{{ $itemPT->id }}"><img
                                            data-src="{{ url(($itemPT->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                            class="m-auto lazyload"></span>
                                <div class="flex items-center gap-1">
                                      <span class="bg-secondary text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1">
                                                  <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                                       class="w-6 h-6 lazyload"/>
                                                  20% off
                                      </span>
                                    <span class="bg-warning text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1">
                                      <img data-src="{{ url('assets/icons/clock_icon.svg') }}"
                                           class="w-6 h-6 lazyload"/>
                                      15-20 min
                                      </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-1.5 mt-3 mb-1">
                               <span class="flex items-center capitalize gap-1.5 text-muted">
                               <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                    class="w-7 h-7 lazyload"
                                    data-src="{{ url(optional($itemPT->store)->avatar_image) }}"/>
                                    {{ optional($itemPT->store)->name }}
                               </span>
                                <span class="flex items-center capitalize gap-1.5 text-secondary">
                                    @for($i = 1; $i <= floor($itemPT->averageRating()); $i++)
                                        <img data-src="{{ url('assets/icons/star_rating.svg') }}"
                                             class="w-3 h-3 lazyload"/>
                                    @endfor

                                    @if($itemPT->averageRating() - floor($itemPT->averageRating()) >= 0.5)
                                        <img data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                                             class="w-3 h-3 lazyload"/>
                                    @endif

                                    @for($i = ceil($itemPT->averageRating()); $i < 5; $i++)
                                        <img data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                                             class="w-3 h-3 lazyload"/>
                                    @endfor
                                    {{ $itemPT->averageRating() }}
                               </span>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-medium text-lg leading-[1.5] md:text-[22px] md:leading-snug capitalize text-start">
                                    {{ $itemPT->name }}
                                </h3>
                                <div class="flex items-center justify-between font-medium">
                                    <div class="flex items-center gap-1 text-lg">
                                        <span class="text-muted line-through">${{ number_format($itemPT->price + 5, 2) }}</span>
                                        <span class="text-secondary">${{ number_format($itemPT->price, 2) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <img data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                                             class="w-6 h-6 lazyload"/>
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