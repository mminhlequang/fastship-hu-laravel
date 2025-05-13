<div class="grid grid-cols-1 xl:grid-cols-4 gap-4 md:gap-6">
    @forelse($data as $itemP)
        <div data-id="{{ $itemP->id }}"
             class="selectProduct cursor-pointer flex flex-col rounded-2xl overflow-hidden p-2 gap-2 w-full border border-solid border-black/10 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
            <div class="relative">
                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                     data-src="{{ url($itemP->image) }}" alt="{{ $itemP->name }}"
                     class="aspect-[11/9] rounded-xl object-cover w-full lazyload"/>
                <div class="p-2 absolute left-0 top-0 w-full flex items-start md:items-center justify-between z-10">
                    <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon" data-id="{{ $itemP->id }}">
                        <img data-src="{{ url(($itemP->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                            class="m-auto lazyload" alt="Fast Ship Hu">
                    </span>
                    <div class="flex items-center flex-col md:flex-row gap-1">
                        <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                            <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}" class="w-6 h-6 lazyload" alt="Fast Ship Hu"/> 20% off </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <h3 class="font-normal text-lg md:text-[22px] leading-snug capitalize">{{ $itemP->name }}</h3>
                <div class="flex items-center justify-between font-medium">
                    <div class="flex items-center gap-1 text-base md:text-lg">
                        <span class="text-muted line-through">{{ number_format($itemP->price ?? 0 + 5, 2) }}&nbsp;Ft</span>
                        <span class="text-secondary">{{ number_format($itemP->price ?? 0, 2) }}&nbsp;Ft</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-400">
                        <img data-src="{{ url('assets/icons/cart.svg') }}" class="w-10 h-10 lazyload" alt="Fast Ship Hu"/>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <img data-src="{{ url('images/no-data.webp') }}" class="lazyload" alt="Fast Ship Hu">
    @endforelse
</div>