<section
        id="become-partner"
        class="py-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="relative">
            <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
            <img
                    data-src="{{ url('assets/images/partner_with_us_image.svg') }}"
                    alt="Become partner"
                    class="w-full lazyload"
            />
            <div
                    class="absolute left-0 right-0 top-0 md:pt-16 md:pb-10 md:px-10 flex flex-col gap-4 md:gap-0 justify-start md:justify-between h-full px-4 pt-4"
            >
                <div class="flex flex-col justify-between items-start gap-4">
                    <p class="text-muted">Signup as a business</p>
                    <h3 class="text-3xl md:text-4xl font-medium">
                        Partner<br/>
                        with us
                    </h3>
                </div>
                <div>
                    <a href="{{ url('become-our-partner') }}"
                       class="inline-flex py-2 px-4 md:py-4 md:px-6 gap-1.5 bg-primary text-white rounded-full capitalize text-sm md:text-base hover:bg-primary-700"
                    >
                        get started
                        <img
                                data-src="{{ url('assets/icons/up_right_icon.svg') }}"
                                class="brightness-[100] lazyload"
                        />
                    </a>
                </div>
            </div>
        </div>
        <div class="relative">
            <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
            <img data-src="{{ url('assets/images/ride_wth_us_image.svg') }}"
                 alt="Become partner"
                 class="w-full lazyload"
            />
            <div
                    class="absolute left-0 right-0 top-0 md:pt-16 md:pb-10 md:px-10 flex flex-col gap-4 md:gap-0 justify-start md:justify-between h-full px-4 pt-4"
            >
                <div class="flex flex-col justify-between items-start gap-4">
                    <p class="text-muted">Signup as a rider</p>
                    <h3 class="text-3xl md:text-4xl font-medium">
                        Ride<br/>
                        with us
                    </h3>
                </div>
                <div>
                    <a href="{{ url('become-our-driver') }}"
                       class="inline-flex py-2 px-4 md:py-4 md:px-6 gap-1.5 bg-primary text-white rounded-full capitalize text-sm md:text-base hover:bg-primary-700"
                    >
                        get started
                        <img
                                data-src="{{ url('assets/icons/up_right_icon.svg') }}"
                                class="brightness-[100] lazyload"
                        />
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>