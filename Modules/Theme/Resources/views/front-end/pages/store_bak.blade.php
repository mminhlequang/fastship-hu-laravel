@extends('theme::front-end.master')
@section('style')
    <style>
        .breadcrumb::after {
            content: "";
            margin: 0 8px;
        }

        .breadcrumb:last-child::after {
            content: "";
        }
    </style>
@endsection
@section('content')
    <!-- Restaurant section -->
    <main class="responsive-px bg-white">
        <!-- Breadcrumbs -->
        <div class="p-4 flex items-center">
            <a href="{{ url('') }}" class="text-gray-500 breadcrumb transition-all hover:text-secondary">Home</a>
            <a href="{{ url('stores') }}" class="text-gray-500 breadcrumb transition-all hover:text-secondary">Restaurant</a>
            <span class="text-gray-800">{{ $store->name }}</span>
        </div>

        <!-- Restaurant Banner -->
        <div class="relative">
            <div
                    class="h-32 md:h-48 w-full bg-cover bg-center rounded-lg"
                    style="
            background-image: url('https://images.unsplash.com/photo-1513639776629-7b61b0ac49cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
          "
            >
                <div
                        class="absolute inset-0 bg-gradient-to-r from-red-700/70 to-red-900/30 rounded-lg"
                ></div>
            </div>

            <!-- Logo & Info positioned on banner -->
            <div class="absolute bottom-2 left-28 flex">
                <div class="ml-4 text-white">
                    <h1 class="text-xl md:text-3xl font-bold">
                        {{ $store->name }}
                    </h1>
                    <p class="text-xs md:text-sm mt-1 max-w-md font-thin">
                        {{ $store->address }}
                    </p>
                </div>
            </div>

            <!-- Star rating on bottom right -->
            <div
                    class="absolute bottom-2 right-3 flex items-center bg-white bg-opacity-30 backdrop-blur-[10.84px] rounded-lg px-2 py-1"
            >
                <div class="flex">
                    @for($i = 1; $i <= floor($store->averageRating()); $i++)
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-white"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                        >
                            <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                    @endfor

                    @if($store->averageRating() - floor($store->averageRating()) >= 0.5)
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-white"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                        >
                            <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                    @endif

                    @for($i = ceil($store->averageRating()); $i < 5; $i++)
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-white"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                        >
                            <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                    @endfor


                </div>
                <span class="ml-1 text-white">{{ $store->averageRating() }}</span>
            </div>

            <!-- Hours tag -->
            <div
                    class="absolute top-2 right-14 text-white text-xs bg-opacity-30 backdrop-blur-[10.84px] px-2 py-2 rounded-md"
            >
                Opening Hours: Today 07:00-20:15
            </div>

            <!-- Heart icon -->
            <button data-id="{{ $store->id }}" data-store="1" class="absolute top-2 right-4 text-white bg-opacity-30 backdrop-blur-[10.84px] p-1 rounded-full favoriteIcon">
                <img data-src="{{ url(($store->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}" class="m-auto lazyload">
            </button>

            <!-- User avatar overlayed on banner -->
            <div class="absolute -bottom-4 left-6 z-10">
                <div class="bg-white p-1 rounded-lg shadow-md">
                    <div
                            class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-white p-1"
                    >
                        <img onerror="this.onerror=null; this.src='{{ url('images/avatar.png') }}'"
                             data-src="{{ url($store->avatar_image) }}"
                             alt="KFC Logo"
                             class="w-14 h-14 md:w-16 md:h-16 lazyload"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Restaurant details -->
        <div
                class="flex flex-wrap items-center justify-center border-b py-2 mt-2 space-x-2 text-sm text-gray-500"
        >
            <div
                    class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2"
            >
                <span class="text-black">Delivery: 25-30 mins</span>
            </div>
            <div
                    class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2"
            >
                <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 mr-1 text-black"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <span class="text-black">30 mins · 0.1 km</span>
            </div>
            <div
                    class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2"
            >
                <span class="text-black">Min. order: $1.00</span>
            </div>
            <div
                    class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2"
            >
                <img
                        data-src="{{ url('assets/icons/shipper_icon.svg') }}"
                        class="w-4 h-4 md:w-6 md:h-6 mr-1 lazyload"
                />
                <span class="text-black">$1.00</span>
            </div>
            <div
                    class="flex items-center text-sm shadow-sm rounded-full border border-gray-300 px-4 py-2"
            >
          <span class="text-black"
          >Enjoy up to PHP50 off with Group Order.</span
          >
                <a href="#" class="text-secondary font-medium">&nbsp;See more</a>
            </div>
        </div>

        <!-- Menu categories -->
        <div class="border-b">
            <div class="flex flex-wrap justify-center overflow-x-auto no-scrollbar">
                @foreach($store->categories as $itemC)
                    <button data-id="{{ $itemC->id }}"
                            class="selectCategory px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"
                    >
                        {{ \App\Helper\LocalizationHelper::getNameByLocale($itemC) }}
                    </button>

                @endforeach
                <button data-id=""
                        class="selectCategory px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"
                >
                    More
                </button>
                <!-- Search bar -->
                <div class="p-4 flex justify-end">
                    <div class="relative">
                        <input id="inputSearch"
                               type="text"
                               placeholder="Search"
                               class="pl-8 pr-4 py-2 w-64 rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500"
                        />
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-gray-400 absolute left-2.5 top-2.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most ordered section -->
        <div class="px-4 pb-6">
            <div class="flex items-center">
                <h2 class="text-lg font-semibold">Most ordered</h2>
                <span class="ml-2 text-yellow-500">🔥</span>
            </div>
            <p class="text-sm text-gray-500 mb-4">
                This is a limited quantity item!
            </p>

            <div id="sectionData" class="grid grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
                @foreach($store->products as $itemP)
                    <a data-id="{{ $itemP->id }}"
                       class="selectProduct cursor-pointer relative block rounded-xl overflow-hidden pt-2 px-2 pb-3 w-full border border-solid border-black/10 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                             data-src="{{ url($itemP->image) }}"
                             class="aspect-square rounded-2xl object-cover w-full lazyload">
                        <div class="p-3 absolute top-2 left-0 right-0 flex items-start md:items-center justify-between z-10">
                            <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon" data-id="{{ $itemP->id }}"><img data-src="{{ url(($itemP->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}" class="m-auto lazyload"></span>
                            <div class="flex items-center flex-col md:flex-row gap-1">
                <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                  <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}" class="w-6 h-6 lazyload">
                  20% off
                </span>
                                <span class="bg-warning text-white rounded-full py-1 px-2.5 md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                  <img data-src="{{ url('assets/icons/clock_icon.svg') }}" class="w-6 h-6 lazyload">
                  15-20 min
                </span>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <h3 class="font-medium text-lg md:text-[22px] leading-snug capitalize">
                                {{ $itemP->name }}
                            </h3>
                            <div class="flex items-center justify-between font-medium">
                                <div class="flex items-center gap-1 text-base md:text-lg">
                                    <span class="text-muted line-through">{{ number_format($itemP->price + 5, 2) }}&nbsp;Ft</span>
                                    <span class="text-secondary">{{ number_format($itemP->price, 2) }}&nbsp;Ft</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <img data-src="{{ url('assets/icons/cart.svg') }}" class="w-8 h-8 lazyload">
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
    <input type="hidden" name="category" id="inputCategory" value="">
    <input type="hidden" name="keywords" id="inputKeywords" value="">
@endsection
@section('script')
    <script type="text/javascript">
        $('body').on('click', '.selectCategory', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let keywords = $('#inputKeywords').val();
            $('#inputCategory').val(id);
            $('.loading').addClass('loader');
            $('.selectCategory').removeClass('text-black').addClass('text-gray-500');
            $(this).addClass('text-black').removeClass('text-gray-500');
            $.ajax({
                url: "{{ url('ajaxFE/getProductsByStore') }}",
                type: "GET",
                data: {
                    store_id: '{{ $store->id }}',
                    category_id: id,
                    keywords: keywords
                },
                success: function (res) {
                    $('#sectionData').html(res);
                    ;
                    $('.loading').removeClass('loader');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        });

        $('body').on('change', '#inputSearch', function (e) {
            e.preventDefault();
            let keywords = $(this).val();
            let categoryId = $('#inputCategory').val();
            $('#inputKeywords').val(keywords);
            $('.loading').addClass('loader');
            $.ajax({
                url: "{{ url('ajaxFE/getProductsByStore') }}",
                type: "GET",
                data: {
                    store_id: '{{ $store->id }}',
                    category_id: categoryId,
                    keywords: keywords
                },
                success: function (res) {
                    $('#sectionData').html(res);
                    ;
                    $('.loading').removeClass('loader');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        });
    </script>
@endsection