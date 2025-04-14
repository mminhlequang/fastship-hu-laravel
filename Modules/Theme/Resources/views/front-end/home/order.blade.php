<section
        id="cta-order"
        class="bg-[#f2efe9] flex justify-center px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 lg:pb-0 pb-6"
>
    <div class="flex flex-col lg:flex-row lg:items-center">
        <div class="lg:max-w-[554px] lg:-mr-16 relative z-10">
            <img alt="Fast Ship Hu"
                    data-src="{{ url('assets/images/cta_order_image.webp') }}"
                    class="w-full lazyload"
            />
        </div>
        <div
                class="p-8 lg:py-10 lg:px-16 rounded-xl bg-white flex flex-col gap-8"
        >
            <h2 class="text-4xl font-medium text-center">
                {{ __('theme::web.home_order_title1') }}<br/>
                {{ __('theme::web.home_order_title2') }}
            </h2>
            <p class="text-muted text-lg text-center">
                {{ __('theme::web.home_order_description') }}
            </p>
            <div
                    class="flex flex-col gap-6 md:gap-0 md:flex-row md:items-center justify-between"
            >
                <div class="flex items-center gap-2">
                    <img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/kitchen_cta_icon_1.svg') }}"
                            class="w-14 h-14 lazyload"
                    />
                    <h3 class="font-medium text-lg">
                        {{ __('theme::web.home_order_option1_1') }}<br/>
                        {{ __('theme::web.home_order_option1_2') }}
                    </h3>
                </div>
                <div class="w-[1px] h-10 bg-gray-600 md:block hidden"></div>
                <div class="flex items-center gap-2">
                    <img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/kitchen_cta_icon_2.svg') }}"
                            class="w-14 h-14 lazyload"
                    />
                    <h3 class="font-medium text-lg">
                        {{ __('theme::web.home_order_option2_1') }}<br/>
                        {{ __('theme::web.home_order_option2_2') }}
                    </h3>
                </div>
                <div class="w-[1px] h-10 bg-gray-600 md:block hidden"></div>
                <div class="flex items-center gap-2">
                    <img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/kitchen_cta_icon_3.svg') }}"
                            class="w-14 h-14 lazyload"
                    />
                    <h3 class="font-medium text-lg">
                        {{ __('theme::web.home_order_option3_1') }}<br/>
                        {{ __('theme::web.home_order_option3_2') }}
                    </h3>
                </div>
            </div>
            <div class="text-center">
                <button class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                    {{ __('theme::web.order_now') }}
                    <img alt="Fast Ship Hu"
                            data-src="{{ url('assets/icons/up_right_icon.svg') }}"
                            class="brightness-[100] lazyload"
                    />
                </button>
            </div>
        </div>
    </div>
</section>