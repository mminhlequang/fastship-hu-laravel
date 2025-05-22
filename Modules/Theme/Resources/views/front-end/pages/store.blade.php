@extends('theme::front-end.master')
@section('title')
    <title>{{ $store->name }}</title>
    <meta name="description"
          content="{{ $store->address }}"/>
    <meta name="keywords" content="{{ $store->name }}"/>
@endsection
@section('style')
    <style>
        .header-container {
            box-shadow: 0px 4px 20px 0px #0000001A;
        }

        .img-store {
            height: 185px;
            object-fit: cover;
            width: 100%;
        }

        .text-yellow {
            color: #FFAB17 !important;
        }

        .text-gray{
            color: #847D79 !important;
        }
        .text-price-gray{
            color: #A6A0A0 !important;
        }
        .selectProduct{
            background: #F9F8F6;
            border: 1px solid #F9F8F6;
        }
        .bg-secondary{
            background: #F17228 !important;
        }

        .bg-gradient-to-r {
            background: linear-gradient(to right, rgb(0 0 0 / 24%) 0%, rgb(0 0 0 / 54%) 46.88%, rgb(0 0 0 / 25%) 69.09%, rgba(0, 0, 0, 0.7));
        }
        .radius-16{
            border-radius: 16px;
        }
        .w-16{
            width: 4.8rem;
        }
        .h-16{
            height: 4.8rem;
        }


        .hero {
            height: 214px;
        }

        .hero-overlay {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.375) 29.5%, rgba(0, 0, 0, 0.552727) 54%, rgba(0, 0, 0, 0.8) 100%);
        }

        .container-logo {
            position: absolute;
            left: 16px;
            bottom: 16px;
            z-index: 10;
        }

        
        .container-information {
            position: absolute;
            left: 132px;
            bottom: 12px;
            z-index: 10;
            padding-right: 16px;
        }

        .wrap-logo {
            width: 100px;
            height: 100px;
            border: 4px solid #F2F0F099;
            padding: 20px;
        }

        .container-tags {
            display: flex;
            align-items: center;
            gap: 8px; 
            padding-left: 0; 
            padding-right: 0;
            padding-top: 12px; 
            margin-bottom: 12px;
            overflow-x: auto;
        }

        .categories {
            order: 2;
            gap: 8px;
        }

        .search-container {
            width: 100%;
        }
        


        .card {
            padding: 10px;
            border-radius: 16px;
            background-color: #f9f8f6;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .card:hover {
            border-color: #74ca45;
            box-shadow: 0px 4px 0px 0px rgba(116, 202, 69, 1);
        }
        
        @media (min-width: 768px) {
            .hero {
                height: 214px;
            }

            .container-logo {
                bottom: -54px
            }

            .wrap-logo {
                width: 140px;
                height: 140px;
            }

            .container-information {
                left: 172px;
                max-width: 680px;
                padding-right: 0;
            }

            .container-tags {
                padding-left: 172px;      
                padding-right: 12px;    
                padding-top: 14px;    
                gap: 12px;    
                padding-bottom: 0;         
                margin-bottom: 24px; 
            }

            .categories {
                order: 0;
                gap: 36px;
            }

            .search-container {
                width: 244px;
            }
        }
    </style>
@endsection
@section('content')
    <!-- Restaurant section -->
    <div class="header-container responsive-px">
        <!-- Breadcrumbs -->
        <div class="p-4 flex items-center md:text-lg lg:text-xl">
            <a href="{{ url('') }}" class="text-gray-500 breadcrumb pr-3 transition-all hover:text-secondary">Home</a>
            <a href="{{ url('stores') }}" class="text-gray-500 breadcrumb px-3 border-l border-r border-gray-300 transition-all hover:text-secondary">Restaurant</a>
            <span class="text-gray-800 pl-3 font-medium">{{ $store->name }}</span>
        </div>

        <!-- Restaurant Banner -->
        <div class="relative">
            @php
                $defaultImage = asset('images/bg_store.png'); // ảnh fallback
                $imagePath = $store->images->first()->image ?? null;
                $imageUrl = $imagePath && file_exists(public_path($imagePath))
                    ? url($imagePath)
                    : $defaultImage;
            @endphp
        
            <div class="hero w-full bg-cover bg-center rounded-2xl relative overflow-hidden"
                style="background-image: url('{{ $imageUrl }}');">
                <div class="absolute inset-0 hero-overlay"
                ></div>
            </div>

            <div
            class="absolute top-0 left-0 w-full p-4 flex items-center justify-between z-10">
                <!-- Star rating on bottom right -->
                <div class="flex items-center text-white bg-black/50 backdrop-blur-[10.84px] rounded-full px-3 md:px-4 py-1.5 md:py-2.5 gap-2 md:gap-4">
                    <span class="hidden md:block text-yellow underline text-sm">234 Review</span>
                    <span class="hidden md:block text-white">|</span>
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
                    <span class="ml-1 text-white">{{ $store->averageRating() }}</span>
                </div>

                <div class="flex gap-3">
                    <!-- Hours tag -->
                    <div class="inline-flex items-center text-white bg-black/50 backdrop-blur-[10.84px] px-3 md:px-4 py-2 md:py-3 rounded-full text-sm">
                        {{ $store->getTodayOpeningHours() }}
                    </div>

                    <!-- Heart icon -->
                    <button data-id="{{ $store->id }}" data-store="1"
                            class="text-white bg-black/50 backdrop-blur-[10.84px] p-2.5 rounded-full h-9 w-9 md:h-11 md:w-11 favoriteIcon">
                        <img data-src="{{ url(($store->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                            class="m-auto md:w-6 md:h-6 w-5 h-5 lazyload">
                    </button>
                </div>
            </div>

            <!-- Logo & Info positioned on banner -->
            <div
                class="container-information"
            >
                <h1 class="text-xl md:text-[44px] text-white font-medium mb-4">
                    {{ $store->name }}
                </h1>
                <p class="text-xs md:text-sm mt-2 max-w-md font-normal" style="color: #FFFFFFCC">
                    {{ $store->address }}
                </p>
            </div>



            <!-- User avatar overlayed on banner -->
            <div class="container-logo">
                <div
                    class="wrap-logo bg-white inline-flex items-center justify-center rounded-2xl"
                >
                    <img onerror="this.onerror=null; this.src='{{ url('images/avatar.png') }}'"
                    data-src="{{ url($store->avatar_image) }}"
                    alt="KFC Logo"
                    class="w-full h-full lazyload"
                    />
                </div>
            </div>
        </div>

        <!-- Restaurant details -->
        <div class="container-tags no-scrollbar">
            <div class="flex flex-shrink-0 items-center shadow-sm rounded-full border px-4 py-2">
                <span class="text-black">Delivery: {{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, $store->lat, $store->lng)['time_minutes'] }} - {{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, $store->lat, $store->lng)['time_minutes'] + 5 }} mins</span>
            </div>
            <div class="flex flex-shrink-0 items-center shadow-sm rounded-full border px-4 py-2">
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

                <span class="text-black"><span class="text-gray-500">{{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, $store->lat, $store->lng)['time_minutes'] }} mins</span> · {{ \App\Models\Order::getDistance($_COOKIE['lat'] ?? 47.1611615, $_COOKIE['lng'] ?? 19.5057541, $store->lat, $store->lng)['distance_km'] }} km</span>
            </div>
            <div
                    class="flex flex-shrink-0 items-center shadow-sm rounded-full border px-4 py-2"
            >
                <span class="text-black"><span class="text-gray-500">Min. order:</span> $1.00</span>
            </div>
            <div
                    class="flex flex-shrink-0 items-center shadow-sm rounded-full border px-4 py-2"
            >
                <img
                        data-src="{{ url('assets/icons/shipper_icon.svg') }}"
                        class="w-4 h-4 md:w-6 md:h-6 mr-1 lazyload"
                />
                <span class="text-black">$1.00</span>
            </div>
            <div
                    class="flex flex-shrink-0 items-center text-sm shadow-sm rounded-full border px-4 py-2"
            >
          <span class="text-gray-500"
          >Enjoy up to PHP50 off with Group Order.</span
          >
                <a href="javascript:;" class="font-md text-secondary font-normal underline">&nbsp;See more</a>
            </div>
        </div>

     <div class="flex flex-col md:flex-row bg-white items-end">
        <!-- Menu categories -->
        <div class="categories order-2 w-full flex-1 flex items-center overflow-x-auto no-scrollbar">
            @foreach($store->categories as $itemC)
                <button data-id="{{ $itemC->id }}"
                    class="selectCategory px-4 py-3 text-center text-gray-500 whitespace-nowrap hover:text-secondary">
                    <img
                       onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                        data-src="{{ url($itemC->image) }}"
                        alt="Logo"
                        class="w-8 h-8 mx-auto mb-2 lazyload"
                    />  
                    <span>
                      {{ \App\Helper\LocalizationHelper::getNameByLocale($itemC) }}
                    </span>
                </button>
            @endforeach
        </div>
        <!-- Search bar -->
         <div style="background: #F2F1F1; order: 1;"
            class="search-container h-10 mb-2 rounded-full md:pl-4 p-2 inline-flex gap-2 items-center border focus-within:border-primary focus-within:ring-1 focus-within:ring-primary"
          >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-gray-400"
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
            <input 
              id="inputSearch"
              placeholder="Search"
              type="text"
              class="outline-none bg-transparent border-none w-full placeholder:text-[#AFAFAF]"
            />
          </div>
     </div>

    </div>

    <main class="responsive-px mt-8">
        <!-- Most ordered section -->
        <div class="px-4 pb-6" id="sectionData">
            @foreach($store->categories as $item)
            <div class="mb-6">
                <div class="flex flex-col gap-2 mb-6">
                    <div class="flex items-center" id="category-{{ $item->id }}">
                        <h2 class="text-2xl font-medium">{{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</h2>
                        <span class="ml-2 text-yellow-500">🧀</span>
                    </div>
                    <p class="text-md text-gray">
                        {{ \App\Helper\LocalizationHelper::getNameByLocale($item, 'description') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @foreach($item->products as $itemP)
                        <a data-id="{{ $itemP->id }}" style="padding: 10px; background: #F9F8F6;"
                           class="selectProduct cursor-pointer relative card">
                           <div class="relative mb-2">
                                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                    data-src="{{ url($itemP->image) }}"
                                    class="aspect-square rounded-xl object-cover w-full aspect-[255/155] lazyload img-store">
                                <div class="absolute p-2 top-0 left-0 w-full flex items-start md:items-center justify-between z-10">
                                    <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon"
                                        data-id="{{ $itemP->id }}"><img
                                                data-src="{{ url(($itemP->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                                class="m-auto lazyload"></span>
                                    <div class="flex items-center flex-col md:flex-row gap-1">
                                    <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                                    <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                        class="w-6 h-6 lazyload">
                                    20% off
                                    </span>
                                    </div>
                                </div>
                           </div> 
                           
                            <div class="flex flex-col gap-1">
                                <h3 class="font-normal text-xl leading-snug capitalize">
                                    {{ $itemP->name }}
                                </h3>
                                <div class="flex items-center justify-between font-medium">
                                    <div class="flex items-center gap-1 text-base md:text-xl truncate">
                                        <span class="text-price-gray line-through">${{ number_format($itemP->price + 5, 2) }}</span>
                                        <span class="text-secondary">${{ number_format($itemP->price, 2) }}</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <img data-src="{{ url('assets/icons/cart.svg') }}" class="w-10 h-10 lazyload">
                                    </div>
                                </div>
                            </div>
                        </a>

                    @endforeach
                </div>
                </div>
            @endforeach
        </div>
    </main>
    <input type="hidden" name="category" id="inputCategory" value="">
    <input type="hidden" name="keywords" id="inputKeywords" value="">
@endsection
@section('script')
    <script type="text/javascript">
        $('.selectCategory').on('click', function () {
            $('.selectCategory').removeClass('text-black font-medium border-b-2 border-black text-gray-500').addClass('text-gray-500');
            $(this).removeClass('text-gray-500').addClass('text-black font-medium border-b-2 border-black');
            let categoryId = $(this).data('id');
            let $target = $('#category-' + categoryId);
            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 100
                }, 600, function () {
                    $('.grid').removeClass('highlight');
                    $target.addClass('highlight');
                    setTimeout(function () {
                        $target.removeClass('highlight');
                    }, 2000);
                });
            }
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
                    $('.loading').removeClass('loader');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        });
    </script>
@endsection