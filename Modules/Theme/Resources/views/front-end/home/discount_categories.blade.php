@foreach($categoriesChild as $itemC)
    <a data-id="{{ $itemC->id }}" class="selectCategoryChild capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base">{{ \App\Helper\LocalizationHelper::getNameByLocale($itemC) }}</a>
@endforeach
<a href="{{ url('stores') }}" class="capitalize flex items-center text-primary hover:opacity-70 text-sm md:text-base"> View all dishes <img data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" ></a>