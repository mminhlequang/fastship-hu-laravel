@foreach($storesFavorite as $itemS)
    <div class="swiper-slide" role="group"
         aria-label="1 / 3"><a href="{{ url('store/'.$itemS->slug.'.html') }}"
                               class="relative dg-item block transition-all duration-500 hover:-translate-y-2 transform-gpu">
            <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                 data-src="{{ url($itemS->avatar_image) }}"
                 class="aspect-[16/10] rounded-2xl object-cover w-full lazyload"
            >
            <div class="p-2 absolute top-0 left-0 right-0 flex items-center justify-between z-10"><span
                        class="w-9 h-9 flex rounded-full bg-black/30"><img
                            data-src="{{ url('assets/icons/heart_line_icon.svg') }}" class="m-auto lazyload"
                    ></span><span
                        class="bg-secondary text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1"><img
                            data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                            class="w-6 h-6 lazyload"> 20% off </span>
            </div>
            <div class="flex flex-col mt-3"><h3
                        class="text-lg leading-[1.5] md:text-[22px] text-start md:leading-snug capitalize">{{ $itemS->name }}</h3>
                <div class="flex items-center justify-between font-medium"><span
                            class="text-muted">Restaurant</span><span
                            class="flex items-center capitalize gap-1.5 text-secondary"><span class="flex items-center"><img
                                    data-src="{{ url('assets/icons/star_rating.svg') }}"
                                    class="w-3 h-3 lazyload"
                                    src="{{ url('assets/icons/star_rating.svg') }}"><img
                                    data-src="{{ url('assets/icons/star_rating.svg') }}"
                                    class="w-3 h-3 lazyload"
                                    src="{{ url('assets/icons/star_rating.svg') }}"><img
                                    data-src="{{ url('assets/icons/star_rating.svg') }}"
                                    class="w-3 h-3 lazyload"
                                    src="{{ url('assets/icons/star_rating.svg') }}"><img
                                    data-src="{{ url('assets/icons/star_rating.svg') }}"
                                    class="w-3 h-3 lazyload"
                                    src="{{ url('assets/icons/star_rating.svg') }}"><img
                                    data-src="{{ url('assets/icons/star_rating.svg') }}"
                                    class="w-3 h-3 lazyload"
                                    src="{{ url('assets/icons/star_rating.svg') }}"></span>{{ $itemS->averageRating() }}</span>
                </div>
            </div>
        </a></div>
@endforeach
