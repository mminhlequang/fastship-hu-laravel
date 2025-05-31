<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
    @forelse($data as $itemS)
        <a href="{{ url('store/'.$itemS->slug.'.html') }}"
           class="dg-item block card-base p-2 rounded-xl">
            <div class="relative flex items-center flex-col justify-center">
                <div class="swiper restaurant-slider relative">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                 alt="{{ $itemS->name }}" data-src="{{ url($itemS->avatar_image) }}"
                                 class="rounded-xl aspect-[16/10] w-full object-cover lazyload"/>
                        </div>
                        <div class="swiper-slide">
                            <img alt="Fast Ship Hu" data-src="{{ url($itemS->avatar_image) }}"
                                 class="rounded-xl aspect-[16/10] w-full object-cover lazyload"/>
                        </div>
                    </div>
                    <div class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10">
                            <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon" data-id="{{ $itemS->id }}" data-store="1"><img data-src="{{ url(($itemS->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}" class="m-auto lazyload"></span>
                            <div class="flex items-center flex-col md:flex-row gap-1">
                        <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                        <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                            class="w-6 h-6 lazyload" alt="Fast Ship Hu"/> 20% off </span>
                                <span class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                        <img data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-6 h-6 lazyload" alt="Fast Ship Hu"/> 15-20 min </span>
                            </div>
                    </div>
                    <div class="px-2 flex items-center justify-between absolute left-0 right-0 z-50 top-[50%] -translate-y-1/2 pointer-events-none">
                        <button class="btn-prev-blur w-[34px] h-[34px] shadow-sm rounded-full flex bg-white/30 backdrop-blur-sm pointer-events-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"
                                 class="size-4 m-auto">
                                <path fill-rule="evenodd"
                                      d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <button class="btn-next-blur w-[34px] h-[34px] shadow-sm rounded-full flex bg-white/30 backdrop-blur-sm pointer-events-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"
                                 class="size-4 m-auto">
                                <path fill-rule="evenodd"
                                      d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="flex flex-col w-full gap-2 mt-2">
                <div class="flex flex-col gap-2 pb-2 border-b border-dashed border-gray-200">
                    <div class="flex items-center justify-between">
                                <span class="text-muted flex items-center gap-2">
                                    @if(count($itemS->products) > 0)
                                        <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                             data-src="{{ url($itemS->products[0]->image) }}"
                                             class="h-8 w-8 lazyload rounded-full" width="28" height="28"/>
                                        {{ $itemS->products[0]->name }}
                                    @endif
                                    </span>
                        <span class="flex items-center capitalize gap-1.5 text-secondary">
                                      <span class="flex items-center">
                                      @for($i = 1; $i <= floor($itemS->averageRating()); $i++)
                                              <img data-src="{{ url('assets/icons/star_rating.svg') }}"
                                                   class="w-3 h-3 lazyload" alt="Fast Ship Hu"/>
                                          @endfor

                                          @if($itemS->averageRating() - floor($itemS->averageRating()) >= 0.5)
                                              <img data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                                                   class="w-3 h-3 lazyload" alt="Fast Ship Hu"/>
                                          @endif

                                          @for($i = ceil($itemS->averageRating()); $i < 5; $i++)
                                              <img data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                                                   class="w-3 h-3 lazyload" alt="Fast Ship Hu"/>
                                          @endfor
                                        </span> {{ $itemS->averageRating() }} </span>
                    </div>
                    <div class="flex items-center gap-1 capitalize">
                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                             alt="Fast Ship Hu" data-src="{{ url($itemS->avatar_image) }}"
                             class="w-8 h-8 rounded-full object-cover lazyload"/> {{ $itemS->name }}
                    </div>
                    </div>
                    <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-base">
                  <img data-src="{{ url('assets/icons/shipper_icon.svg') }}" class="w-6 h-6 lazyload"/> $0.00 </span>
                        <div class="flex items-center gap-1 text-base">
                            <span class="text-muted line-through">{{ number_format($itemS->price + 5, 1) }}&nbsp;Ft</span>
                            <span class="text-secondary">{{ number_format($itemS->price, 1) }}&nbsp;Ft</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="flex flex-col items-center gap-6 mt-4">
            <img src="{{ url('images/no-data.webp') }}" width="190" height="160" class="mx-auto" >
            <h6 class="text-dark font-medium">Nothing to Show</h6>
        </div>
    @endforelse
</div>
