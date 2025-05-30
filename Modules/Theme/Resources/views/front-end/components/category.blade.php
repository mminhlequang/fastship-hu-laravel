<div data-id="{{ $itemC->id }}" class="selectCategory card-base relative rounded-2xl bg-white px-2 py-3 flex flex-col gap-3">
    <img data-src="{{ url($itemC->image) }}" class="w-[120px] h-[96px] lazyload" alt="Food Category" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"/>
    <div class="flex flex-col gap-1 items-center justify-center">
        <h3 class="font-medium text-lg" title="{{ \App\Helper\LocalizationHelper::getNameByLocale($itemC) }}">{{ str_limit(\App\Helper\LocalizationHelper::getNameByLocale($itemC), 10, '...') }}</h3>
    </div>
</div>