<section id="fastest-delivery" class="py-6">
    <div class="flex flex-col gap-10">
        <div class="flex items-center justify-between px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <h2 class="capitalize text-3xl md:text-4xl font-medium">Fastest delivery</h2>
            <a href="#" class="flex items-center text-primary"
            >View all dishes
                <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload"
                /></a>
        </div>
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            @foreach($productFaster as $itemPF)
                <a
                        href="{{ url('product/'.$itemPF->slug.'.html') }}"
                        class="fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu"
                >
                    <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                         data-src="{{ url($itemPF->image) }}"
                         class="aspect-square rounded-2xl object-cover w-full lazyload"
                    />
                    <div
                            class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                    >
                    <span class="w-9 h-9 flex rounded-full bg-black/30">
                      <img data-src="{{ url('assets/icons/heart_line_icon.svg') }}"
                           class="m-auto lazyload"
                      />
                    </span>
                        <div class="flex items-center flex-col md:flex-row gap-1">
                      <span
                              class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                      >
                        <img
                                data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                class="w-6 h-6 lazyload"
                        />
                        20% off
                      </span>
                            <span
                                    class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                            >
                        <img data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-6 h-6 lazyload"/>
                        15-20 min
                            </span>
                        </div>
                    </div>
                    <div
                            class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1"
                    >
                    <span class="flex items-center capitalize gap-1.5 text-muted">
                      <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" class="w-7 h-7 lazyload" data-src="{{ url(optional($itemPF->store)->avatar_image) }}"/>
                       {{ optional($itemPF->store)->name }}
                    </span>
                        <span
                                class="flex items-center capitalize gap-1.5 text-secondary"
                        >
                      <span class="flex items-center">
                         @for($i = 1; $i <= floor($itemPF->averageRating()); $i++)
                              <img data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"/>
                          @endfor

                          @if($itemPF->averageRating() - floor($itemPF->averageRating()) >= 0.5)
                              <img data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                                   class="w-3 h-3 lazyload"/>
                          @endif

                          @for($i = ceil($itemPF->averageRating()); $i < 5; $i++)
                              <img data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                                   class="w-3 h-3 lazyload"/>
                          @endfor
                      </span>
                       {{ $itemPF->averageRating() }}
                    </span>
                    </div>
                    <div class="flex flex-col">
                        <h3
                                class="font-medium text-lg md:text-[22px] leading-snug capitalize"
                        >
                            {{ $itemPF->name }}
                        </h3>
                        <div class="flex items-center justify-between font-medium">
                            <div class="flex items-center gap-1 text-base md:text-lg">
                                <span class="text-muted line-through">${{ number_format($itemPF->price + 5, 2) }}</span>
                                <span class="text-secondary">${{ number_format($itemPF->price, 2) }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <img data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                                     class="w-6 h-6 lazyload"
                                />
                                <span>0.44 km</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
<div class="flex justify-end">
    <img data-src="{{ url('assets/icons/heart_deco_icon.png') }}" class="h-[110px] lazyload"/>
</div>