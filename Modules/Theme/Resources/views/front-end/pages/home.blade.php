@extends('theme::front-end.master')

@section('content')
    <main>
        <section
                id="banner"
                class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <div
                    class="flex flex-col lg:flex-nowrap lg:flex-row py-10 lg:items-center"
            >
                <div class="lg:max-w-[530px] 2xl:max-w-[unset] flex flex-col gap-6">
            <span class="text-primary hover:opacity-70 text-xl"
            >Good evening</span
            >
                    <h1
                            class="text-5xl leading-[1.5] md:text-[64px] md:leading-[1.3] font-semibold inline-flex flex-col items-start"
                    >
              <span class="relative">
                Your Favorite
                <img
                        src="./assets/images/line_text_banner_1.svg"
                        class="h-[13px] absolute -bottom-1 right-0"
                />
              </span>

                        <span class="relative">
                Food Delivery
                <img
                        src="./assets/images/line_text_banner_2.svg"
                        class="h-[20px] absolute left-0 right-0 -bottom-1 w-full"
                />
              </span>
                        <span>Partner</span>
                    </h1>
                    <p class="text-[22px] leading-snug text-muted">
                        We are the fastest and most popular delivery service across the
                        city.
                    </p>
                    <form action="#">
                        <div
                                class="flex items-center gap-1.5 py-2 pl-4 pr-2 rounded-full bg-white shadow"
                        >
                            <img
                                    src="./assets/icons/map_banner_input_icon.svg"
                                    class="w-6 h-6"
                            />
                            <input
                                    type="text"
                                    class="flex-1 focus:outline-none"
                                    placeholder="Enter your delivery location"
                            />
                            <button
                                    class="rounded-full py-2.5 px-8 bg-primary text-white hover:bg-primary-700"
                            >
                                Search
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center gap-4 text-muted">
              <span class="flex items-center gap-1.5 cursor-pointer">
                <img src="./assets/icons/gps_banner_icon.svg" class="w-6 h-6"/>
                <u>Share location</u>
              </span>
                        <span class="flex items-center gap-1.5 cursor-pointer">
                <u>Login for save address</u>
              </span>
                    </div>
                </div>
                <div class="inline-flex flex-1 mt-5 md:mt-0">
                    <img src="./assets/images/banner_img.svg" class="w-full"/>
                </div>
            </div>
        </section>
        <section id="popular-category" class="py-12">
            <div class="flex flex-col gap-10">
                <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    <h2 class="capitalize text-3xl md:text-4xl font-medium">
                        popular categories
                    </h2>
                </div>
                <div
                        class="swiper popular-categories-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <div class="swiper-wrapper pb-12">
                        <!-- Slides will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </section>
        <section id="fastest-delivery" class="py-6">
            <div class="flex flex-col gap-10">
                <div
                        class="flex items-center justify-between px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <h2 class="capitalize text-3xl md:text-4xl font-medium">
                        Fastest delivery
                    </h2>
                    <a href="#" class="flex items-center text-primary"
                    >View all dishes
                        <img src="./assets/icons/up_right_icon.svg" class="w-5 h-5"
                        /></a>
                </div>
                <div
                        class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <a
                            href="#"
                            class="fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu"
                    >
                        <img
                                src="./assets/images/food_item_img_1.webp"
                                class="aspect-square rounded-2xl object-cover w-full"
                        />
                        <div
                                class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                        >
                <span class="w-9 h-9 flex rounded-full bg-black/30">
                  <img
                          src="./assets/icons/heart_line_icon.svg"
                          class="m-auto"
                  />
                </span>
                            <div class="flex items-center flex-col md:flex-row gap-1">
                  <span
                          class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                  >
                    <img
                            src="./assets/icons/ticket_star_icon.svg"
                            class="w-6 h-6"
                    />
                    20% off
                  </span>
                                <span
                                        class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                                >
                    <img src="./assets/icons/clock_icon.svg" class="w-6 h-6"/>
                    15-20 min
                  </span>
                            </div>
                        </div>
                        <div
                                class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1"
                        >
                <span class="flex items-center capitalize gap-1.5 text-muted">
                  <img class="w-7 h-7" src="./assets/images/food_logo_1.webp"/>
                  Foodworld
                </span>
                            <span
                                    class="flex items-center capitalize gap-1.5 text-secondary"
                            >
                  <span class="flex items-center">
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                  </span>
                  5
                </span>
                        </div>
                        <div class="flex flex-col">
                            <h3
                                    class="font-medium text-lg md:text-[22px] leading-snug capitalize"
                            >
                                Cheese Burger
                            </h3>
                            <div class="flex items-center justify-between font-medium">
                                <div class="flex items-center gap-1 text-base md:text-lg">
                                    <span class="text-muted line-through">$3.30</span>
                                    <span class="text-secondary">$2.20</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <img
                                            src="./assets/icons/map_banner_input_icon.svg"
                                            class="w-6 h-6"
                                    />
                                    <span>0.44 km</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a
                            href="#"
                            class="fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu"
                    >
                        <img
                                src="./assets/images/food_item_img_1.webp"
                                class="aspect-square rounded-2xl object-cover w-full"
                        />
                        <div
                                class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                        >
                <span class="w-9 h-9 flex rounded-full bg-black/30">
                  <img
                          src="./assets/icons/heart_line_icon.svg"
                          class="m-auto"
                  />
                </span>
                            <div class="flex items-center flex-col md:flex-row gap-1">
                  <span
                          class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                  >
                    <img
                            src="./assets/icons/ticket_star_icon.svg"
                            class="w-6 h-6"
                    />
                    20% off
                  </span>
                                <span
                                        class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                                >
                    <img src="./assets/icons/clock_icon.svg" class="w-6 h-6"/>
                    15-20 min
                  </span>
                            </div>
                        </div>
                        <div
                                class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1"
                        >
                <span class="flex items-center capitalize gap-1.5 text-muted">
                  <img class="w-7 h-7" src="./assets/images/food_logo_1.webp"/>
                  Foodworld
                </span>
                            <span
                                    class="flex items-center capitalize gap-1.5 text-secondary"
                            >
                  <span class="flex items-center">
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img
                            src="./assets/icons/star_rating_half.svg"
                            class="w-3 h-3"
                    />
                  </span>
                  4.5
                </span>
                        </div>
                        <div class="flex flex-col">
                            <h3
                                    class="font-medium text-lg md:text-[22px] leading-snug capitalize"
                            >
                                Cheese Burger
                            </h3>
                            <div class="flex items-center justify-between font-medium">
                                <div class="flex items-center gap-1 text-base md:text-lg">
                                    <span class="text-muted line-through">$3.30</span>
                                    <span class="text-secondary">$2.20</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <img
                                            src="./assets/icons/map_banner_input_icon.svg"
                                            class="w-6 h-6"
                                    />
                                    <span>0.44 km</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a
                            href="#"
                            class="fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu"
                    >
                        <img
                                src="./assets/images/food_item_img_1.webp"
                                class="aspect-square rounded-2xl object-cover w-full"
                        />
                        <div
                                class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                        >
                <span class="w-9 h-9 flex rounded-full bg-black/30">
                  <img
                          src="./assets/icons/heart_line_icon.svg"
                          class="m-auto"
                  />
                </span>
                            <div class="flex items-center flex-col md:flex-row gap-1">
                  <span
                          class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                  >
                    <img
                            src="./assets/icons/ticket_star_icon.svg"
                            class="w-6 h-6"
                    />
                    20% off
                  </span>
                                <span
                                        class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                                >
                    <img src="./assets/icons/clock_icon.svg" class="w-6 h-6"/>
                    15-20 min
                  </span>
                            </div>
                        </div>
                        <div
                                class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1"
                        >
                <span class="flex items-center capitalize gap-1.5 text-muted">
                  <img class="w-7 h-7" src="./assets/images/food_logo_1.webp"/>
                  Foodworld
                </span>
                            <span
                                    class="flex items-center capitalize gap-1.5 text-secondary"
                            >
                  <span class="flex items-center">
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                  </span>
                  5
                </span>
                        </div>
                        <div class="flex flex-col">
                            <h3
                                    class="font-medium text-lg md:text-[22px] leading-snug capitalize"
                            >
                                Cheese Burger
                            </h3>
                            <div class="flex items-center justify-between font-medium">
                                <div class="flex items-center gap-1 text-base md:text-lg">
                                    <span class="text-muted line-through">$3.30</span>
                                    <span class="text-secondary">$2.20</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <img
                                            src="./assets/icons/map_banner_input_icon.svg"
                                            class="w-6 h-6"
                                    />
                                    <span>0.44 km</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a
                            href="#"
                            class="fd-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu"
                    >
                        <img
                                src="./assets/images/food_item_img_1.webp"
                                class="aspect-square rounded-2xl object-cover w-full"
                        />
                        <div
                                class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                        >
                <span class="w-9 h-9 flex rounded-full bg-black/30">
                  <img
                          src="./assets/icons/heart_line_icon.svg"
                          class="m-auto"
                  />
                </span>
                            <div class="flex items-center flex-col md:flex-row gap-1">
                  <span
                          class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                  >
                    <img
                            src="./assets/icons/ticket_star_icon.svg"
                            class="w-6 h-6"
                    />
                    20% off
                  </span>
                                <span
                                        class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                                >
                    <img src="./assets/icons/clock_icon.svg" class="w-6 h-6"/>
                    15-20 min
                  </span>
                            </div>
                        </div>
                        <div
                                class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1"
                        >
                <span class="flex items-center capitalize gap-1.5 text-muted">
                  <img class="w-7 h-7" src="./assets/images/food_logo_1.webp"/>
                  Foodworld
                </span>
                            <span
                                    class="flex items-center capitalize gap-1.5 text-secondary"
                            >
                  <span class="flex items-center">
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img src="./assets/icons/star_rating.svg" class="w-3 h-3"/>
                    <img
                            src="./assets/icons/star_rating_half.svg"
                            class="w-3 h-3"
                    />
                  </span>
                  4.5
                </span>
                        </div>
                        <div class="flex flex-col">
                            <h3
                                    class="font-medium text-lg md:text-[22px] leading-snug capitalize"
                            >
                                Cheese Burger
                            </h3>
                            <div class="flex items-center justify-between font-medium">
                                <div class="flex items-center gap-1 text-base md:text-lg">
                                    <span class="text-muted line-through">$3.30</span>
                                    <span class="text-secondary">$2.20</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <img
                                            src="./assets/icons/map_banner_input_icon.svg"
                                            class="w-6 h-6"
                                    />
                                    <span>0.44 km</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        <div class="flex justify-end">
            <img src="./assets/icons/heart_deco_icon.png" class="h-[110px]"/>
        </div>
        <section id="discount" class="py-6 flex flex-col gap-10">
            <div
                    class="flex items-start md:items-center justify-between flex-col md:flex-row px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 gap-4 md:gap-0"
            >
                <h2 class="capitalize text-3xl md:text-4xl font-medium">
                    Discount Guaranteed! 👌
                </h2>
                <div class="flex items-center gap-3 md:gap-6">
                    <a
                            href="#"
                            class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
                    >Vegan</a
                    >
                    <a
                            href="#"
                            class="capitalize font-medium text-dark text-sm md:text-base"
                    >Pizza & Fast food</a
                    >
                    <a
                            href="#"
                            class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
                    >Sushi</a
                    >
                    <a
                            href="#"
                            class="capitalize text-muted hover:text-dark hover:font-medium text-sm md:text-base"
                    >others</a
                    >
                    <a
                            href="#"
                            class="capitalize flex items-center text-primary hover:opacity-70 text-sm md:text-base"
                    >
                        View all dishes
                        <img src="./assets/icons/up_right_icon.svg" class="w-5 h-5"/>
                    </a>
                </div>
            </div>
            <div
                    class="swiper discount-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
            >
                <div class="swiper-wrapper pt-6">
                    <!-- Slides will be populated by JavaScript -->
                </div>
            </div>
        </section>
        <section id="top-rated" class="py-12">
            <div class="flex flex-col gap-10">
                <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    <h2 class="capitalize text-3xl md:text-4xl font-medium">
                        Top-rated
                    </h2>
                </div>
                <div
                        class="swiper top-rated-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <div class="swiper-wrapper pt-6">
                        <!-- Slides will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </section>
        <section id="local-favorite" class="py-12">
            <div class="flex flex-col gap-10">
                <div
                        class="flex items-center justify-between px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <h2 class="text-3xl md:text-4xl font-medium">Local favorites</h2>
                    <a href="#" class="flex items-center text-primary">
                        View all dishes
                        <img src="./assets/icons/up_right_icon.svg" class="w-5 h-5"/>
                    </a>
                </div>

                <div
                        class="swiper local-favorites-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <div class="swiper-wrapper pt-6">
                        <!-- Slides will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </section>
        <section
                id="become-partner"
                class="py-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="relative">
                    <img
                            src="./assets/images/partner_with_us_image.svg"
                            alt="Become partner"
                            class="w-full"
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
                            <button
                                    class="inline-flex py-2 px-4 md:py-4 md:px-6 gap-1.5 bg-primary text-white rounded-full capitalize text-sm md:text-base"
                            >
                                get started
                                <img
                                        src="./assets/icons/up_right_icon.svg"
                                        class="brightness-[100]"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img
                            src="./assets/images/ride_wth_us_image.svg"
                            alt="Become partner"
                            class="w-full"
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
                            <button
                                    class="inline-flex py-2 px-4 md:py-4 md:px-6 gap-1.5 bg-primary text-white rounded-full capitalize text-sm md:text-base"
                            >
                                get started
                                <img
                                        src="./assets/icons/up_right_icon.svg"
                                        class="brightness-[100]"
                                />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
                id="how-we-work"
                class="py-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <div class="flex flex-col gap-10">
                <h2 class="text-3xl md:text-4xl font-medium">How we work</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    <div
                            class="rounded-2xl px-4 py-6 flex flex-col bg-[#FFF8ED] justify-between"
                    >
                        <div class="flex flex-col gap-3">
                            <h3
                                    class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-custom-warning"
                            >
                                It's 100% risk-free
                            </h3>
                            <p class="text-muted text-sm md:text-base">
                                There's no fee for joining Fast ship hu. You can quit
                                whenever, for any reason. When you earn, we earn.z
                            </p>
                        </div>
                        <img
                                src="./assets/images/how_we_work_img_1.svg"
                                class="mx-auto w-[75%]"
                                loading="lazy"
                        />
                    </div>
                    <div
                            class="rounded-2xl px-4 py-6 flex flex-col bg-[#EFF9EF] justify-between"
                    >
                        <div class="flex flex-col gap-3">
                            <h3
                                    class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-custom-primary"
                            >
                                Power online sales with same house deliveries
                            </h3>
                            <p class="text-muted text-sm md:text-base">
                                Add fast Drive logistics for Fast ship hu, affordable express
                                deliveries for ecommerce .
                            </p>
                        </div>
                        <img
                                src="./assets/images/how_we_work_img_2.svg"
                                class="mx-auto w-[75%]"
                                loading="lazy"
                        />
                    </div>
                    <div
                            class="rounded-2xl px-4 py-6 flex flex-col bg-[#EAF7FC] justify-between"
                    >
                        <div class="flex flex-col gap-3">
                            <h3
                                    class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-info"
                            >
                                We do the heavy lifting
                            </h3>
                            <p class="text-muted text-sm md:text-base">
                                We handle ads, payments, delivery and support.
                            </p>
                        </div>
                        <img
                                src="./assets/images/how_we_work_img_3.svg"
                                class="mx-auto w-[75%]"
                                loading="lazy"
                        />
                    </div>
                    <div
                            class="rounded-2xl px-4 py-6 flex flex-col bg-[#FFF0EE] gap-10 justify-between"
                    >
                        <div class="flex flex-col gap-3">
                            <h3
                                    class="font-medium text-2xl leading-[1.5] md:text-[28px] md:leading-snug text-danger"
                            >
                                Boost your sale
                            </h3>
                            <p class="text-muted text-sm md:text-base">
                                91% of Wolt orders are extra sales you wouldn’t otherwise get.
                            </p>
                        </div>
                        <img
                                src="./assets/images/how_we_work_img_4.svg"
                                class="mx-auto w-[75%]"
                                loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </section>
        <section
                id="cta-download-app"
                class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <div
                    class="bg-[#F1EFE9] rounded-3xl px-4 pt-10 lg:pb-10 xl:pb-0 md:pl-12 lg:pl-8 xl:pl-12 md:pt-10 md:pr-4"
            >
                <div class="flex flex-col lg:flex-row md:items-center gap-16">
                    <div
                            class="flex flex-col md:gap-8 lg:gap-4 xl:gap-8 lg:max-w-[454px]"
                    >
                        <h2 class="text-3xl md:text-[44px] leading-[1.5] font-medium">
                            Honey, we’re not cooking tonight
                        </h2>
                        <p class="text-muted text-base md:text-lg">
                            Get the Apple-awarded Wolt app and choose from 40,000
                            restaurants and hundreds of stores in 20+ countries. Discover
                            and get what you want – our courier partners bring it to you.
                        </p>
                        <div class="grid grid-cols-2 gap-8">
                            <a href="#" class="block">
                                <img src="./assets/images/download_ios.svg" class="w-full"/>
                            </a>
                            <a href="#" class="block">
                                <img
                                        src="./assets/images/download_android.svg"
                                        class="w-full"
                                />
                            </a>
                        </div>
                    </div>
                    <div>
                        <img src="./assets/images/cta_banner.webp" class="w-full"/>
                    </div>
                </div>
            </div>
        </section>
        <section id="great-customer" class="py-12">
            <div
                    class="swiper great-customer-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
            >
                <div class="swiper-wrapper">
                    <!-- Slides will be populated by JavaScript -->
                </div>
            </div>
        </section>
        <section
                id="cta-order"
                class="bg-[#f2efe9] flex justify-center px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 lg:pb-0 pb-6"
        >
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="lg:max-w-[554px] lg:-mr-16 relative z-10">
                    <img
                            src="./assets/images/cta_order_image.webp"
                            loading="lazy"
                            class="w-full"
                    />
                </div>
                <div
                        class="p-8 lg:py-10 lg:px-16 rounded-xl bg-white flex flex-col gap-8"
                >
                    <h2 class="text-4xl font-medium text-center">
                        From kitchens everywhere,<br/>
                        to your dining table.
                    </h2>
                    <p class="text-muted text-lg text-center">
                        Fusce volutpat lectus et nisl consectetur finibus. In vitae
                        scelerisque augue, in varius eros.
                    </p>
                    <div
                            class="flex flex-col gap-6 md:gap-0 md:flex-row md:items-center justify-between"
                    >
                        <div class="flex items-center gap-2">
                            <img
                                    src="./assets/icons/kitchen_cta_icon_1.svg"
                                    loading="lazy"
                                    class="w-14 h-14"
                            />
                            <h3 class="font-medium text-lg">
                                Daily<br/>
                                Discounts
                            </h3>
                        </div>
                        <div class="w-[1px] h-10 bg-gray-600 md:block hidden"></div>
                        <div class="flex items-center gap-2">
                            <img
                                    src="./assets/icons/kitchen_cta_icon_2.svg"
                                    loading="lazy"
                                    class="w-14 h-14"
                            />
                            <h3 class="font-medium text-lg">
                                Live<br/>
                                Tracing
                            </h3>
                        </div>
                        <div class="w-[1px] h-10 bg-gray-600 md:block hidden"></div>
                        <div class="flex items-center gap-2">
                            <img
                                    src="./assets/icons/kitchen_cta_icon_3.svg"
                                    loading="lazy"
                                    class="w-14 h-14"
                            />
                            <h3 class="font-medium text-lg">
                                Quick<br/>
                                Delivery
                            </h3>
                        </div>
                    </div>
                    <div class="text-center">
                        <button
                                class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700"
                        >
                            Order now
                            <img
                                    src="./assets/icons/up_right_icon.svg"
                                    class="brightness-[100]"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section
                id="blog"
                class="py-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <div class="flex flex-col gap-10">
                <h2 class="text-3xl md:text-4xl font-medium">Local favorites</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a
                            href="#"
                            class="flex flex-col gap-4 p-4 rounded-xl transition-all hover:bg-white hover:shadow-xl"
                    >
                        <img
                                src="./assets/images/article_img_1.webp"
                                class="w-full rounded-xl aspect-[16/10] object-cover"
                                loading="lazy"
                        />
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center text-muted text-sm gap-4">
                                <span>Blog</span>
                                <span>|</span>
                                <span class="text-secondary">Mar 8, 2025</span>
                            </div>
                            <p class="text-lg">
                                Introducing GrabAds and the Top 3 GrabAds Campaigns That
                                Really Work
                            </p>
                        </div>
                    </a>
                    <a
                            href="#"
                            class="flex flex-col gap-4 p-4 rounded-xl transition-all hover:bg-white hover:shadow-xl"
                    >
                        <img
                                src="./assets/images/article_img_2.webp"
                                class="w-full rounded-xl aspect-[16/10] object-cover"
                                loading="lazy"
                        />
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center text-muted text-sm gap-4">
                                <span>Blog</span>
                                <span>|</span>
                                <span class="text-secondary">Mar 4, 2025</span>
                            </div>
                            <p class="text-lg">
                                6 Effective Promotional Strategies to Try for Your Business
                            </p>
                        </div>
                    </a>
                    <a
                            href="#"
                            class="flex flex-col gap-4 p-4 rounded-xl transition-all hover:bg-white hover:shadow-xl"
                    >
                        <img
                                src="./assets/images/article_img_3.webp"
                                class="w-full rounded-xl aspect-[16/10] object-cover"
                                loading="lazy"
                        />
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center text-muted text-sm gap-4">
                                <span>Blog</span>
                                <span>|</span>
                                <span class="text-secondary">Mar 9, 2025</span>
                            </div>
                            <p class="text-lg">
                                5 Reasons Why Self Pick-up Is Part of the New Normal
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection