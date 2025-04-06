@extends('theme::front-end.master')
@section('style')
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .input-field:focus {
            border-color: #74ca45;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .password-field {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .avatar-container {
            padding-bottom: 20px;
        }

        .avatar-inner {
            width: 120px;
            height: 120px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin: 0 auto;
        }

        .initials {
            font-size: 48px;
            color: #7ac142;
            font-weight: bold;
        }

        .camera-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #f5f5f5;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #666;
        }

        .menu-item.active {
            border-radius: 12px;
            background-color: #74ca45;
            color: white;
        }

        .menu-item i {
            width: 24px;
            margin-right: 10px;
        }

        .pagination-item {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .logo {
            color: #7ac142;
            font-weight: bold;
            font-size: 24px;
        }
    </style>
@endsection
@section('content')
    <section
            class="bg-gray-100 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 py-3"
    >
        <div class="flex flex-wrap">
            <!-- Left Sidebar -->
        @include('theme::front-end.layouts.sidebar')
        <!-- Right Content -->
            <div class="w-full sm:w-3/4">
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Tab Navigation -->
                    <div class="flex pb-6">
                        <h2 class="text-xl font-medium w-full sm:w-auto">My wishlist</h2>
                        <div class="ml-auto">
                            <a href="{{ url('customer/delete_favorite') }}" class="px-5 pb-3 text-sm font-medium text-pink-500">
                                Delete all
                            </a>
                        </div>
                    </div>
                    @if(Session::has('success'))
                        <div class="bg-gray-50 border-l-4 border-secondary mb-4 text-primary">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                        @forelse($storesFavorite as $itemS)
                            <a href="{{ url('store/'.$itemS->slug.'.html') }}"
                               class="relative block rounded-xl overflow-hidden pt-2 px-2 pb-3 w-full border border-solid border-black/10 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
                                <div class="relative flex items-center flex-col justify-center">
                                    <div class="swiper restaurant-slider relative">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                                     data-src="{{ url($itemS->avatar_image) }}"
                                                     class="rounded-xl aspect-[16/10] w-full object-cover lazyload"/>
                                            </div>
                                            <div class="swiper-slide">
                                                <img data-src="{{ url($itemS->avatar_image) }}"
                                                     class="rounded-xl aspect-[16/10] w-full object-cover lazyload"/>
                                            </div>
                                        </div>
                                        <div class="px-4 flex items-center justify-between absolute left-0 right-0 z-50 top-[45%] pointer-events-none">
                                            <button class="btn-prev-blur w-[34px] h-[34px] shadow-sm rounded-full flex bg-white/30 backdrop-blur-sm pointer-events-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"
                                                     class="size-4 m-auto">
                                                    <path fill-rule="evenodd"
                                                          d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                            <button class="btn-next-blur w-[34px] h-[34px] shadow-sm rounded-full flex bg-white/30 backdrop-blur-sm pointer-events-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"
                                                     class="size-4 m-auto">
                                                    <path fill-rule="evenodd"
                                                          d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                    <div class="flex flex-col w-full gap-2 mt-2">
                                        <div class="flex items-center justify-between">
                                    <span class="text-muted flex items-center gap-2">
                                        @if(count($itemS->products) > 0)
                                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" data-src="{{ url($itemS->products[0]->image) }}"
                                             class="h-8 w-8 lazyload"/>
                                        {{ $itemS->products[0]->name }}
                                    @endif
                                    </span>
                                            <span class="flex items-center capitalize gap-1.5 text-secondary">
                                      <span class="flex items-center">
                                      @for($i = 1; $i <= floor($itemS->averageRating()); $i++)
                                              <img data-src="{{ url('assets/icons/star_rating.svg') }}"
                                                   class="w-3 h-3 lazyload"/>
                                          @endfor

                                          @if($itemS->averageRating() - floor($itemS->averageRating()) >= 0.5)
                                              <img data-src="{{ url('assets/icons/star_half_rating.svg') }}"
                                                   class="w-3 h-3 lazyload"/>
                                          @endif

                                          @for($i = ceil($itemS->averageRating()); $i < 5; $i++)
                                              <img data-src="{{ url('assets/icons/star_empty_rating.svg') }}"
                                                   class="w-3 h-3 lazyload"/>
                                          @endfor
                                        </span> {{ $itemS->averageRating() }} </span>
                                        </div>
                                        <div class="flex items-center gap-1 capitalize">
                                            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" data-src="{{ url($itemS->avatar_image) }}"
                                                 class="w-8 h-8 rounded-full object-cover lazyload"/> {{ $itemS->name }}
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-2 text-base">
                                            <img data-src="{{ url('assets/icons/shipper_icon.svg') }}" class="w-6 h-6 lazyload"/> $0.00 </span>
                                            <div class="flex items-center gap-1 text-base md:text-lg">
                                                <span class="text-muted line-through">${{ number_format($itemS->price + 5, 2) }}</span>
                                                <span class="text-secondary">${{ number_format($itemS->price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        @empty
                            <img data-src="{{ url('images/no-data.webp') }}" class="lazyload">
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const avatarContainer = document.querySelector(".w-32.h-32");
            const cameraButton = document.querySelector(".fa-camera").parentNode;

            const fileInput = document.createElement("input");
            fileInput.type = "file";
            fileInput.accept = "image/*";
            fileInput.style.display = "none";
            document.body.appendChild(fileInput);

            cameraButton.addEventListener("click", function () {
                fileInput.click();
            });

            fileInput.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        avatarContainer.innerHTML = "";
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.className = "w-full h-full object-cover rounded-full";
                        avatarContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
