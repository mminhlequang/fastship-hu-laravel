<section id="discount" class="py-6 flex flex-col gap-10">
    <div
            class="flex items-start md:items-center justify-between flex-col md:flex-row px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 gap-4 md:gap-0"
    >
        <h2 class="capitalize text-3xl md:text-4xl font-medium">
            Discount Guaranteed! 👌
        </h2>
        <div class="flex items-center gap-3 md:gap-6">
            <a
                    href="#"
                    class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
            >Vegan</a
            >
            <a
                    href="#"
                    class="capitalize font-medium text-dark text-sm md:text-base"
            >Pizza & Fast food</a
            >
            <a
                    href="#"
                    class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
            >Sushi</a
            >
            <a
                    href="#"
                    class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
            >others</a
            >
            <a
                    href="{{ url('stores') }}"
                    class="capitalize flex items-center text-primary hover:opacity-70 text-sm md:text-base"
            >
                View all dishes
                <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload"/>
            </a>
        </div>
    </div>
    <div class="swiper discount-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
        <div class="swiper-wrapper pt-6">
            @foreach($storesFavorite as $itemS)
                <div class="swiper-slide">
                    <a href="{{ url('store/'.$itemS->slug.'.html') }}"
                       class="dg-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu">
                        <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" data-src="{{ url($itemS->avatar_image) }}"
                             class="aspect-[16/10] rounded-2xl object-cover w-full lazyload"/>
                        <div class="p-2 absolute top-0 left-0 right-0 flex items-center justify-between z-10">
                      <span class="w-9 h-9 flex rounded-full bg-black/30">
                        <img data-src="{{ url('assets/icons/heart_line_icon.svg') }}" class="m-auto lazyload"/>
                      </span>
                            <span class="bg-secondary text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1">
                        <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}" class="w-6 h-6 lazyload"/>
                        20% off
                      </span>
                        </div>
                        <div class="flex flex-col mt-3">
                            <h3 class="text-lg leading-[1.5] md:text-[22px] text-start md:leading-snug capitalize">
                                {{ $itemS->name }}
                            </h3>
                            <div class="flex items-center justify-between font-medium">
                                <span class="text-muted">Restaurant</span>
                                <span class="flex items-center capitalize gap-1.5 text-secondary">
                            <span class="flex items-center">
                                @for($i = 1; $i <= floor($itemS->averageRating()); $i++)
                                    <img data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"/>
                                @endfor

                                @if($itemS->averageRating() - floor($itemS->averageRating()) >= 0.5)
                                    <img data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                                         class="w-3 h-3 lazyload"/>
                                @endif

                                @for($i = ceil($itemS->averageRating()); $i < 5; $i++)
                                    <img data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                                         class="w-3 h-3 lazyload"/>
                                @endfor
                            </span>
                            {{ $itemS->averageRating() }}
                            </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </div>

</section>