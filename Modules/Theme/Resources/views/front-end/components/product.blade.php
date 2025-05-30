@if(isset($item))
    <div data-id="{{ $item->id }}"
         class="selectProduct cursor-pointer flex flex-col rounded-2xl overflow-hidden p-2 gap-2 w-full border border-solid border-black/10 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
        <div class="relative rounded-xl overflow-hidden">
            <img alt="{{ $item->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                 data-src="{{ url($item->image) }}"
                 class="object-cover w-full lazyload rounded-xl aspect-[259/225]"
            >
            <div style="background-color: rgba(0, 0, 0, 0.2);" class="absolute inset-0 p-2 z-10">
                <div class="flex items-start md:items-center justify-between">
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

        <div class="flex flex-col gap-2 mt-2">
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center capitalize gap-1 text-muted">
                    <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                         class="w-7 h-7 rounded-full lazyload" width="28" height="28"
                         data-src="{{ url(optional($item->store)->avatar_image) }}">
                    <span>
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
                <span>
                    {{ $item->averageRating() }}
                </span>
            </span>
            </div>
            <h3 class="font-medium text-lg md:text-[22px] leading-tight capitalize text-black line-clamp-1">
                {{ $item->name }}
            </h3>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-1 text-base md:text-lg">
                <span class="text-muted line-through">
                    {{ number_format($item->price + 5, 0, '.', '') }}&nbsp;Ft
                </span>
                    <span class="text-secondary font-medium">
                    {{ number_format($item->price, 0, '.', '') }}&nbsp;Ft
                </span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
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