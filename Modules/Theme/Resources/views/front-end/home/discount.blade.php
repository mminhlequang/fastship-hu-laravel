<style>
    .store-favorite-slide-item {
        padding-top: 2.5rem;
        padding-bottom: 3.75rem;
    }
</style>

@foreach($storesFavorite as $itemS)
    <div class="relative swiper-slide store-favorite-slide-item " role="group" aria-label="1 / 3">
         <a href="{{ url('store/'.$itemS->slug.'.html') }}" class="dg-item block card-base p-2 rounded-xl transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
            <div class="relative rounded-2xl overflow-hidden">
                <img alt="{{ $itemS->name }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                 data-src="{{ url($itemS->avatar_image) }}"
                 class="rounded-2xl object-cover w-full lazyload"
                 style="aspect-ratio: 358 / 220;"
                >
                
               <div class="absolute inset-0 p-2 z-10" style="background-color: rgba(0, 0, 0, 0.2);">
                    <div class="flex items-center justify-between">
                        <span style="background-color: rgba(0, 0, 0, 0.15);" class="w-9 h-9 flex rounded-full favoriteIcon" data-id="{{ $itemS->id }}" data-store="1">
                            <img data-src="{{ url(($itemS->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}" class="m-auto w-6 h-6 lazyload">
                        </span>
                        <span class="bg-secondary text-white rounded-full px-2 py-1.5 flex items-center text-sm gap-1">
                            <img
                                alt="Fast Ship Hu" data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                class="w-5 h-5 lazyload"> 
                            <span>20% off</span>
                        </span>
                    </div>
               </div>
            </div>
    
            <div class="flex flex-col mt-3">
                <div class="flex gap-2">
                    <h3 class="text-lg line-clamp-1 leading-[1.5] md:text-[22px] text-start md:leading-snug capitalize flex-1">
                    {{ $itemS->name }}
                    </h3>
                    <div class="flex items-center gap-2 text-gray-400">
                        <img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}"
                            class="w-6 h-6 lazyload">
                        <span>
                            {{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, optional($itemS)->lat, optional($itemS)->lng)['distance_km'] }} km
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-muted">Restaurant</span>
                    <span class="flex items-center capitalize gap-1 text-secondary">
                        <img data-src="{{ url('assets/icons/star_rating.svg') }}"
                        class="w-5 h-5 lazyload" alt="Fast Ship Hu"
                        src="{{ url('assets/icons/star_rating.svg') }}">
                  
                       <span>{{ $itemS->averageRating() }}</span>
                    </span>
                </div>
            </div>
        </a>
    </div>
@endforeach
