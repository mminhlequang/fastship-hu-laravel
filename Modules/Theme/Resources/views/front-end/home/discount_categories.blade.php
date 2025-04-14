@foreach($categoriesChild as $itemC)
    <span data-id="{{ $itemC->id }}" class="selectCategoryChild capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base cursor-pointer">{{ \App\Helper\LocalizationHelper::getNameByLocale($itemC) }}</span>
@endforeach
<a href="{{ url('stores') }}" class="capitalize flex items-center text-primary hover:opacity-70 text-sm md:text-base"> {{ __('theme::web.view_all_dish') }} <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" ></a>