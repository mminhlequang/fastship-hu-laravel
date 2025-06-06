<section id="cta-download-app" class="responsive-px">
    <div class="bg-[#F1EFE9] rounded-3xl px-4 pt-10 lg:pb-10 xl:pb-0 md:pl-12 lg:pl-8 xl:pl-12 md:pt-10 md:pr-4">
        <div class="flex flex-col lg:flex-row md:items-center gap-16">
            <div class="flex flex-col md:gap-8 lg:gap-4 xl:gap-8 lg:max-w-[454px]">
                <h2 class="text-3xl md:text-[44px] leading-[1.5] font-medium">
                    {{ __('theme::web.home_download_title') }}
                </h2>
                <p class="text-muted text-base md:text-lg">
                    {{ __('theme::web.home_download_description') }}
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ $settings['follow_ios'] }}" class="block">
                        <img alt="Fast Ship Hu" data-src="{{ url('assets/images/download_ios.svg') }}" class="w-full lazyload"/>
                    </a>
                    <a href="{{ $settings['follow_android'] }}" class="block">
                        <img alt="Fast Ship Hu" data-src="{{ url('assets/images/download_android.svg') }}" class="w-full lazyload"/>
                    </a>
                </div>
            </div>
            <div>
                <img alt="Fast Ship Hu" data-src="{{ url('assets/images/cta_banner.webp') }}" class="w-full lazyload"/>
            </div>
        </div>
    </div>
</section>