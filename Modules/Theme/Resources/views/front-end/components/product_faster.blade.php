@if(isset($item))
    <div data-id="{{ $item->id }}" class="selectProduct swiper-slide hover:shadow-[4px_14px_50px_rgba(0,0,0,0.1)] cursor-pointer fd-item relative block transition-all bg-white p-2 rounded-xl">
        <div class="relative rounded-xl overflow-hidden">
            <img alt="{{ $item->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                 data-src="{{ url($item->image) }}"
                 class="object-cover w-full lazyload rounded-xl max-w-[358px] max-h-[225px]"
            >
            <div style="background-color: rgba(0, 0, 0, 0.2);" class="absolute inset-0 z-10">
                <div class="flex items-start md:items-center justify-between p-2">
                    <span style="background-color: rgba(0, 0, 0, 0.15);" class="w-9 h-9 flex rounded-full favoriteIcon"
                        data-id="{{ $item->id }}">
                        <img
                                alt="Fast Ship Hu"
                                data-src="{{ url(($item->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                class="m-auto w-6 h-6 lazyload">
                    </span>
                    <div class="flex items-center flex-col md:flex-row gap-1">
                    <span
                            class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-2 md:py-1.5 flex items-center text-sm gap-1">
                        <img
                                alt="Fast Ship Hu" data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                class="w-5 h-5 lazyload"> 
                        <span>20% off</span>
                    </span>
                        <span
                                class="bg-warning text-white rounded-full py-1 px-2.5 md:px-2 md:py-1.5 flex items-center text-sm gap-1">
                        <img
                                data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-5 h-5 lazyload"> 
                        <span>15-20 min</span>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2 mt-[19px]">
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center capitalize gap-1 text-[#848484]">
                    <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                         class="w-7 h-7 rounded-full lazyload" width="28" height="28"
                         data-src="{{ url(optional($item->store)->avatar_image) }}">
                    <span class="text-base leading-[1.2]">
                    {{ optional($item->store)->name }} 
                    </span>
                </div>
                <span class="flex items-center capitalize gap-1 text-secondary">
                <span class="flex items-center">
                    <img
                            alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_rating.svg') }}"
                            class="w-5 h-5 lazyload"
                            src="{{ url('assets/icons/star_rating.svg') }}">
                </span>
                <span class="text-base leading-[1.2]">
                    {{ $item->averageRating() }}
                </span>
            </span>
            </div>
            <h3 class="font-medium text-lg md:text-[22px] leading-[1.2] capitalize text-black line-clamp-1 text-left">
                {{ $item->name }}
            </h3>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-1 text-base md:text-lg">
                <span class="text-[#A6A0A0] line-through">
                    {{ number_format($item->price + 5, 0, '.', '') }}&nbsp;Ft
                </span>
                    <span class="text-secondary font-medium">
                    {{ number_format($item->price, 0, '.', '') }}&nbsp;Ft
                </span>
                </div>
                <div class="flex items-center gap-2 text-[#6E6E6E] text-base leading-[1.2] tracking-[0.08px]">
                    <img alt="Fast Ship Hu"
                         data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                         class="w-6 h-6 lazyload">
                    <span>
                    {{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, optional($item->store)->lat, optional($item->store)->lng)['distance_km'] }} km
                </span>
                </div>
            </div>
        </div>
    </div>
@endif