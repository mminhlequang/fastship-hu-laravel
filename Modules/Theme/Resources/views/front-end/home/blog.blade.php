<section id="blog" class="section-padding">
    <div class="flex flex-col gap-10 responsive-px">
        <div class="flex items-center justify-between">
            <h2 class="capitalize text-3xl md:text-4xl font-medium">
                {{ __('theme::web.home_blog') }}
            </h2>
            <a href="{{ url('news') }}" class="flex items-center text-primary">{{ __('theme::web.view_all_dish') }}
                <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/up_right_icon.svg') }}" class="w-5 h-5 lazyload" />
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($news as $itemN)
            <a href="{{ url('news/'.$itemN->slug.'.html') }}" class="relative card-news rounded-2xl flex flex-col gap-4 p-4 bg-white">

                <img alt="{{ \App\Helper\LocalizationHelper::getNameByLocale($itemN) }}"
                    data-src="{{ url($itemN->image) }}" onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                    class="w-full rounded-2xl aspect-[342/190] object-cover lazyload" />

                <div class="flex flex-col gap-4">
                    <div class="flex items-center text-muted text-sm gap-4">
                        <span>Blog</span>
                        <span>|</span>
                        <span class="text-secondary">{{ $itemN->created_at->format('M j, Y') }}</span>
                    </div>
                  
                    <p class="text-lg">
                        {{ \App\Helper\LocalizationHelper::getNameByLocale($itemN) }}
                    </p>
                </div>
            </a>
            @empty
            @endforelse
        </div>
    </div>
</section>