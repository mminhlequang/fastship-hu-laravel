@foreach($productFaster as $itemPF)
<div data-id="{{ $itemPF->id }}" class="selectProduct card-base cursor-pointer fd-item relative block transition-all bg-white p-2 rounded-xl overflow-hidden">
    <div class="relative rounded-xl overflow-hidden">
        <div class="skeleton absolute inset-0 bg-gray-200 z-50" style="display: none;"></div>
       
        <img alt="{{ $itemPF->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
            data-src="{{ $itemPF->image }}"
            class="object-cover w-full lazyload rounded-xl"
            style="aspect-ratio: 259 / 225;"    
        >

        <div style="background-color: rgba(0, 0, 0, 0.2);" class="absolute inset-0 p-2 z-10">
            <div class="flex items-start md:items-center justify-between">
                <span style="background-color: rgba(0, 0, 0, 0.15);" class="w-9 h-9 flex rounded-full favoriteIcon" data-id="{{ $itemPF->id }}">
                    <img
                        alt="Fast Ship Hu" data-src="{{ url(($itemPF->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}" class="m-auto w-6 h-6 lazyload">
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
                <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" class="w-7 h-7 rounded-full lazyload" width="28" height="28" data-src="{{ url(optional($itemPF->store)->avatar_image) }}">
                <span>
                    {{ optional($itemPF->store)->name }} 
                </span>
            </div> 
            <span class="flex items-center capitalize gap-1 text-secondary">
                <span class="flex items-center">
                    <img
                    alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-5 h-5 lazyload"
                    src="{{ url('assets/icons/star_rating.svg') }}">
                </span>
                <span>
                    {{ $itemPF->averageRating() }}
                </span>
            </span>
        </div>
        <h3 class="font-medium text-lg md:text-[22px] leading-tight capitalize text-black line-clamp-2">
            {{ $itemPF->name }}
        </h3>
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-1 text-base md:text-lg">
                <span class="text-muted line-through">
                    $ {{ number_format($itemPF->price + 5, 2) }}
                </span>
                <span class="text-secondary font-medium">
                    $ {{ number_format($itemPF->price, 2) }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-gray-400">
                <img alt="Fast Ship Hu"
                    data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                    class="w-6 h-6 lazyload">
                <span>
                    {{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, optional($itemPF->store)->lat, optional($itemPF->store)->lng)['distance_km'] }} km
                </span>
            </div>
        </div>
    </div>
</div>
@endforeach