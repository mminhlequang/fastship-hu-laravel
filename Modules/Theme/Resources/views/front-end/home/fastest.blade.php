@foreach($productFaster as $itemPF)
    <div data-id="{{ $itemPF->id }}" class="selectProduct cursor-pointer fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu">
        <div class="skeleton absolute inset-0 bg-gray-200 z-50" style="display: none;"></div>
        <img alt="{{ $itemPF->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
             data-src="{{ $itemPF->image }}"
             class="aspect-square rounded-2xl object-cover w-full lazyload"
        >
        <div class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"><span
                    class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon" data-id="{{ $itemPF->id }}"><img
                        alt="Fast Ship Hu" data-src="{{ url(($itemPF->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}" class="m-auto lazyload"
                ></span>
            <div class="flex items-center flex-col md:flex-row gap-1"><span
                        class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1"><img
                            alt="Fast Ship Hu" data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                            class="w-6 h-6 lazyload"> 20% off </span><span
                        class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1"><img
                            data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-6 h-6 lazyload"
                    > 15-20 min </span></div>
        </div>
        <div class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1">
            <span class="flex items-center capitalize gap-1.5 text-muted">
                <img alt="Fast Ship Hu" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" class="w-7 h-7 rounded-full lazyload" width="28" height="28" data-src="{{ url(optional($itemPF->store)->avatar_image) }}"
                >{{ optional($itemPF->store)->name }} </span><span
                    class="flex items-center capitalize gap-1.5 text-secondary"><span class="flex items-center"><img
                            alt="Fast Ship Hu" data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"
                            src="{{ url('assets/icons/star_rating.svg') }}"><img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"
                            src="{{ url('assets/icons/star_rating.svg') }}"><img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"
                            src="{{ url('assets/icons/star_rating.svg') }}"><img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"
                            src="{{ url('assets/icons/star_rating.svg') }}"><img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/star_rating.svg') }}" class="w-3 h-3 lazyload"
                            src="{{ url('assets/icons/star_rating.svg') }}"></span>{{ $itemPF->averageRating() }}</span>
        </div>
        <div class="flex flex-col"><h3 class="font-normal text-lg md:text-[22px] leading-snug capitalize">
                {{ $itemPF->name }} </h3>
            <div class="flex items-center justify-between font-medium">
                <div class="flex items-center gap-1 text-base md:text-lg"><span
                            class="text-muted line-through">{{ number_format($itemPF->price + 5, 2) }}&nbsp;Ft</span><span
                            class="text-secondary">{{ number_format($itemPF->price, 2) }}&nbsp;Ft</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400"><img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                            class="w-6 h-6 lazyload"
                    ><span>{{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, optional($itemPF->store)->lat, optional($itemPF->store)->lng)['distance_km'] }} km</span>
                </div>
            </div>
        </div>
    </div>
@endforeach