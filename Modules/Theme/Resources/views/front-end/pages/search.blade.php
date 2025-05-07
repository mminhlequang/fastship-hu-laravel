@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Fast ship Hu Search') }}</title>
    <meta name="description"
          content="{{ __('Fast ship Hu Search') }}"/>
    <meta name="keywords" content="{{ __('Fast ship Hu Search') }}"/>
@endsection
@section('style')
    <style>
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-active .dropdown-content {
            max-height: 500px;
        }

        .dropdown-active .dropdown-header {
            font-weight: 600;
            color: black;
        }

        .dropdown-active .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-icon {
            transition: transform 0.3s ease;
        }

        /* Custom radio/checkbox styles */
        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid #ddd;
            cursor: pointer;
            position: relative;
            outline: none;
        }

        .custom-checkbox:checked {
            background-color: #22c55e;
            border-color: #22c55e;
        }

        .custom-checkbox:checked::after {
            content: "✓";
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endsection
@section('content')
    <section id="sub-page-header"
             class="responsive-px bg-[#f2efe9] lg:py-10 xl:py-0">
        <div class="flex flex-col lg:flex-nowrap lg:flex-row lg:items-center">
            <div class="flex flex-col gap-2">
                <nav class="flex items-center text-gray-600 text-xl">
                    <a href="{{ url('') }}" class="text-muted transition-all hover:text-secondary">Home</a>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-gray-900 font-medium">Restaurant</span>
                </nav>
                <h1 class="text-[44px] leading-[1.5] md:text-[64px] md:leading-[1.3] font-semibold inline-flex flex-col items-start">
                    Restaurants near me </h1>
                <p class="text-[22px] leading-snug text-muted"> There are 13405 locations in Ho Chi Minh City from
                    00:00 - 23:59 </p>
                <form action="{{ url('search') }}" method="GET" class="flex items-center gap-3">
                    <input type="hidden" name="type" value="1">
                    <div class="flex items-center gap-1.5 py-2 pl-4 pr-2 rounded-full bg-white shadow md:w-auto md:flex-1">
                          <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.75 10C3.75 6.54822 6.54822 3.75 10 3.75C13.4518 3.75 16.25 6.54822 16.25 10C16.25 13.4518 13.4518 16.25 10 16.25C6.54822 16.25 3.75 13.4518 3.75 10ZM10 2.25C5.71979 2.25 2.25 5.71979 2.25 10C2.25 14.2802 5.71979 17.75 10 17.75C14.2802 17.75 17.75 14.2802 17.75 10C17.75 5.71979 14.2802 2.25 10 2.25ZM17.5303 16.4697C17.2374 16.1768 16.7626 16.1768 16.4697 16.4697C16.1768 16.7626 16.1768 17.2374 16.4697 17.5303L19.4697 20.5303C19.7626 20.8232 20.2374 20.8232 20.5303 20.5303C20.8232 20.2374 20.8232 19.7626 20.5303 19.4697L17.5303 16.4697Z"
                                    fill="#636F7E"/>
                            </svg>
                          </span>
                        <input type="text" class="w-[53%] md:w-auto md:flex-1 focus:outline-none"
                               placeholder="Search" name="keywords"/>
                        <button class="rounded-full inline-flex items-center py-2.5 px-4 md:px-8 bg-primary text-white hover:bg-primary-700 capitalize text-xs md:text-base">
                            order now <img data-src="{{ url('assets/icons/up_right_icon.svg') }}"
                                           class="w-4 h-4 md:w-6 md:h-6 brightness-[100] lazyload"/>
                        </button>
                    </div>
                    <span onclick="toggleModal('modalOverlayFilter')"
                          class="h-14 w-14 rounded-full bg-white flex shadow shrink-0 cursor-pointer">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
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
    <section id="all-restaurants"
             class="mx-auto p-4 flex flex-wrap pb-12 responsive-px">
        <!-- Filter Sidebar -->
        <div class="w-full sm:w-1/4 rounded-lg shadow-sm">
            <div class="bg-gray-50 p-4">
                <h2 class="font-medium text-gray-700 my-4">Food list</h2>
                <!-- Espresso Coffee Dropdown -->
                @foreach($categories as $item)
                    <div class="bg-white p-4 dropdown mb-2 {{ collect($item->children->pluck('id'))->intersect(explode(',', \Request::get('categories')))->isNotEmpty() ? 'dropdown-active' : '' }}
                            ">
                        <div
                                class="dropdown-header flex justify-between items-center cursor-pointer py-2"
                        >
                            <span>{{ $item->name_en }}</span>
                            <svg
                                    class="dropdown-icon w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </div>
                        <div class="dropdown-content pl-2">
                            @foreach($item->children as $itemC)
                                <div class="flex items-center py-1">
                                    <input
                                            value="{{ $itemC->id }}"
                                            type="checkbox"
                                            class="custom-checkbox"
                                            id="{{ $itemC->name_en }}"
                                            {{ in_array($itemC->id, explode(',', \Request::get('categories'))) ? 'checked': '' }}
                                    />
                                    <label for="{{ $itemC->name_en }}" class="ml-2 text-sm"
                                    >{{ $itemC->name_en }}</label
                                    >
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <!-- Products Display -->
        <div class="w-full sm:w-3/4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex space-x-4 mb-4">
                    <h2 data-type="1"
                        class="cursor-pointer selectType text-2xl font-medium {{ ($type == 1) ? 'text-black border-b-2 border-black' : 'text-gray-500' }}">
                        All restaurants </h2>
                    <h2 data-type="2"
                        class="cursor-pointer selectType text-2xl font-medium {{ ($type == 2) ? 'text-black border-b-2 border-black' : 'text-gray-500' }}">
                        All food</h2>
                </div>
                <div id="sectionData" class="w-full">
                    @if($type == 1)
                        @include('theme::front-end.ajax.stores')
                    @else
                        @include('theme::front-end.ajax.products')
                    @endif
                </div>
            </div>

        </div>

    </section>
    @include('theme::front-end.modals.filter')
    <input type="hidden" name="type" id="inputType" value="1">

@endsection
@section('script')
    <script type="text/javascript">
        $('body').on('click', '.selectType', function (e) {
            e.preventDefault();
            $('.loading').addClass('loader');
            $('.selectType').removeClass('text-black border-b-2 border-black').addClass('text-gray-500');
            let type = $(this).data('type');
            $('#inputType').val(type);
            $(this).addClass('text-black border-b-2 border-black').removeClass('text-gray-500');
            $.ajax({
                url: "{{ url('ajaxFE/searchData') }}",
                type: "GET",
                data: {
                    type: type,
                    min_price: '{{ \Request::get('min_price') }}',
                    max_price: '{{ \Request::get('max_price') }}',
                    keywords: '{{ \Request::get('keywords') }}',
                    categories: '{{ \Request::get('categories') }}'
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
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const dropdownHeaders = document.querySelectorAll(".dropdown-header");
            dropdownHeaders.forEach((header) => {
                header.addEventListener("click", function () {
                    const dropdown = this.parentElement;
                    dropdown.classList.toggle("dropdown-active");
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const filterCheckboxes = document.querySelectorAll(".custom-checkbox");

            filterCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", function () {
                    console.log(
                        `Filter ${this.id} is now ${
                            this.checked ? "checked" : "unchecked"
                        }`
                    );

                    filterProducts();
                });
            });

            function filterProducts() {
                const checkedFilters = Array.from(
                    document.querySelectorAll(".custom-checkbox:checked")
                ).map((cb) => cb.value);
                const checkedFiltersString = checkedFilters.join(',');
                $('.loading').addClass('loader');
                let type = $('#inputType').val();
                $.ajax({
                    url: "{{ url('ajaxFE/searchData') }}",
                    type: "GET",
                    data: {
                        type: type,
                        categories: checkedFiltersString
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
            }
        });
    </script>

@endsection