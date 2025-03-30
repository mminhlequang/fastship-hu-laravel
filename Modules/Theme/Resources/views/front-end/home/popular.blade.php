<section id="popular-category" class="py-12">
    <div class="flex flex-col gap-10">
        <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <h2 class="capitalize text-3xl md:text-4xl font-medium">
                popular categories
            </h2>
        </div>
        <div class="swiper popular-categories-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <div class="swiper-wrapper pb-12">
                @foreach ($popularCategories as $category)
                    <div class="swiper-slide rounded-2xl">
                        <div class="rounded-2xl bg-white p-4 flex flex-col gap-8 hover:shadow-xl transition-all w-full cursor-pointer">
                            <img src="{{ asset($category['image']) }}" loading="lazy" class="w-full"
                                 alt="Food Category"/>
                            <div class="flex flex-col gap-1 items-center justify-center">
                                <h3 class="font-medium text-lg">{{ $category['title'] }}</h3>
                                <p class="text-secondary capitalize">{{ $category['places'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>