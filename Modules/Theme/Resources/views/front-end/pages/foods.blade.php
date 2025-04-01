@extends('theme::front-end.master')

@section('content')
    <main>
        <section id="sub-page-header"
                 class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 bg-[#f2efe9] lg:py-10 xl:py-0">
            <div class="flex flex-col lg:flex-nowrap lg:flex-row lg:items-center">
                <div class="flex flex-col gap-6">
                    <nav class="flex items-center text-gray-600 text-xl">
                        <a href="/" class="text-muted">Home</a>
                        <span class="mx-2 text-gray-400">|</span>
                        <span class="text-gray-900 font-medium">Food list</span>
                    </nav>
                    <h1 class="text-[44px] leading-[1.5] md:text-[64px] md:leading-[1.3] font-semibold inline-flex flex-col items-start">
                        Cafe near me </h1>
                    <p class="text-[22px] leading-snug text-muted">21 Restaurants</p>
                    <form action="#" class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-1.5 py-2 pl-4 pr-2 rounded-full bg-white shadow md:w-auto md:flex-1">
            <span>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M3.75 10C3.75 6.54822 6.54822 3.75 10 3.75C13.4518 3.75 16.25 6.54822 16.25 10C16.25 13.4518 13.4518 16.25 10 16.25C6.54822 16.25 3.75 13.4518 3.75 10ZM10 2.25C5.71979 2.25 2.25 5.71979 2.25 10C2.25 14.2802 5.71979 17.75 10 17.75C14.2802 17.75 17.75 14.2802 17.75 10C17.75 5.71979 14.2802 2.25 10 2.25ZM17.5303 16.4697C17.2374 16.1768 16.7626 16.1768 16.4697 16.4697C16.1768 16.7626 16.1768 17.2374 16.4697 17.5303L19.4697 20.5303C19.7626 20.8232 20.2374 20.8232 20.5303 20.5303C20.8232 20.2374 20.8232 19.7626 20.5303 19.4697L17.5303 16.4697Z"
                      fill="#636F7E"/>
              </svg>
            </span>
                            <input type="text" class="w-[53%] md:w-auto md:flex-1 focus:outline-none"
                                   placeholder="Search"/>
                            <button class="rounded-full inline-flex items-center py-2.5 px-4 md:px-8 bg-primary text-white hover:bg-primary-700 capitalize text-xs md:text-base">
                                order now <img data-src="{{ url("assets/icons/up_right_icon.svg") }}"
                                               class="w-4 h-4 md:w-6 md:h-6 brightness-[100] lazyload"/>
                            </button>
                        </div>
                        <span class="h-14 w-14 rounded-full bg-white flex shadow shrink-0">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                 class="m-auto">
              <path d="M18.48 18.5368H21M4.68 12L3 12.044M4.68 12C4.68 13.3255 5.75451 14.4 7.08 14.4C8.40548 14.4 9.48 13.3255 9.48 12C9.48 10.6745 8.40548 9.6 7.08 9.6C5.75451 9.6 4.68 10.6745 4.68 12ZM10.169 12.0441H21M12.801 5.55124L3 5.55124M21 5.55124H18.48M3 18.5368H12.801M17.88 18.6C17.88 19.9255 16.8055 21 15.48 21C14.1545 21 13.08 19.9255 13.08 18.6C13.08 17.2745 14.1545 16.2 15.48 16.2C16.8055 16.2 17.88 17.2745 17.88 18.6ZM17.88 5.4C17.88 6.72548 16.8055 7.8 15.48 7.8C14.1545 7.8 13.08 6.72548 13.08 5.4C13.08 4.07452 14.1545 3 15.48 3C16.8055 3 17.88 4.07452 17.88 5.4Z"
                    stroke="#878080" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
          </span>
                    </form>
                </div>
                <div class="inline-flex flex-1 mt-5 md:mt-0">
                    <img data-src="{{ url('assets/images/banner_img.svg') }}" class="w-full lazyload"/>
                </div>
            </div>
        </section>
        <!-- Menu categories -->
        <section
                class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 flex flwx-wrap items-center justify-between">
            <div class="text-2xl font-medium mb-6 text-black py-3 mt-3"> Food list</div>
            <div class="flex flex-wrap justify-center overflow-x-auto no-scrollbar">
                <button class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"> Super Meals</button>
                <button class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"> Super Meals</button>
                <button class="px-4 py-3 border-b-2 border-black text-black font-medium whitespace-nowrap"> Family
                    Meals
                </button>
                <button class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"> Jolly Meal Savers
                </button>
                <button class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"> Chickenjoy</button>
                <button class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"> Burgers</button>
                <button class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"> More (5)</button>
            </div>
        </section>
        <section id="all-restaurants"
                 class="flex flex-col gap-10 pb-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 relative">
            <div class="flex space-x-4">
                <h2 class="text-2xl font-medium mb-6 text-gray-500"> All restaurants </h2>
                <h2 class="text-2xl font-medium mb-6 text-black">All food</h2>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 md:gap-6">
                <a href="#"
                   class="relative block rounded-xl overflow-hidden pt-2 px-2 pb-3 w-full border border-solid border-black/10 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
                    <img data-src="{{ url('assets/images/food_item_img_1.webp') }}"
                         class="aspect-square rounded-2xl object-cover w-full lazyload"/>
                    <div class="p-3 absolute top-2 left-0 right-0 flex items-start md:items-center justify-between z-10">
          <span class="w-9 h-9 flex rounded-full bg-black/30">
            <img data-src="{{ url('assets/icons/heart_line_icon.svg') }}" class="m-auto lazyload"/>
          </span>
                        <div class="flex items-center flex-col md:flex-row gap-1">
            <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
              <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}" class="w-6 h-6 lazyload"/> 20% off </span>
                            <span class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1">
              <img data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-6 h-6 lazyload"/> 15-20 min </span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="font-medium text-lg md:text-[22px] leading-snug capitalize"> Cheese Burger </h3>
                        <div class="flex items-center justify-between font-medium">
                            <div class="flex items-center gap-1 text-base md:text-lg">
                                <span class="text-muted line-through">$3.30</span>
                                <span class="text-secondary">$2.20</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <img data-src="{{ url('assets/icons/cart.svg') }}" class="w-8 h-8 lazyload"/>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    </main>
@endsection