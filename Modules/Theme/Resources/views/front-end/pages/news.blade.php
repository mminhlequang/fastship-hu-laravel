@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Fast ship Hu Blog') }}</title>
    <meta name="description"
          content="{{ __('Fast ship Hu Blog') }}"/>
    <meta name="keywords" content="{{ __('Fast ship Hu Blog') }}"/>
@endsection
@section('content')
    <main>
        <!-- Hero Section -->
        <div class="relative h-48 bg-gray-900 text-white flex">
            <div class="absolute inset-0 z-0 bg-cover bg-bottom bg-no-repeat"
                 style="background-image: url('{{ url('assets/images/article_img_1.webp') }}')"></div>
            <div class="responsive-px px-6 pb-9 pt-[68px] h-fit z-10">
                <h1 class="text-[44px] leading-[1.2] font-medium mb-2 tracking-[0.88px]">Event and blog</h1>
                <p class="text-[22px] leading-[1.2722] text-[#CEC6C5]">Boost visibility and sales on the Grab platform with these tips.</p>
            </div>
        </div>

        <!-- Blog Grid -->
        <div class="mx-auto px-[55px] py-[30px] bg-[#f9f8f6]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-10">
                @foreach ($news->take(2) as $item)
                    <a
                            href="{{ url('blogs/'. $item->slug.'.html') }}"
                            class="relative flex flex-col gap-4 p-4 rounded-xl bg-white transition-all card-news"
                    >
                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                             data-src="{{ url($item->image) }}"
                             class="w-full rounded-2xl aspect-[16/10] max-lg:max-h-[280px] object-cover lazyload"

                        />
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center text-sm gap-4">
                                <span class="leading-[1.286] text-[#939191]">Blog</span>
                                <span class="text-[#D0D0D0]">|</span>
                                <span class="text-secondary leading-[1.2] tracking-[0.14px]">{{ $item->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-2xl leading-[1.4] tracking-[0.24px] text-[#0B0B0B]">{{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-4">
                @foreach ($news->skip(2) as $item)
                    <a
                            href="{{ url('blogs/'. $item->slug.'.html') }}"
                            class="relative flex flex-col gap-4 p-4 rounded-xl bg-white transition-all card-news"
                    >

                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                             data-src="{{ url($item->image) }}"
                             class="w-full rounded-2xl aspect-[16/10] max-lg:max-h-[190px] object-cover lazyload"
                        />
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center text-sm gap-4">
                                <span class="text-sm leading-[1.286] text-[#939191]">Blog</span>
                                <span class="text-[#D0D0D0]">|</span>
                                <span class="text-secondary text-sm leading-[1.2] tracking-[0.14px]">{{ $item->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-[18px] leading-[1.4] tracking-[0.18px] text-[#0B0B0B]">{{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- See More Button -->
            {{--            <div class="flex justify-center mt-8">--}}
            {{--                <button class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">--}}
            {{--                    See more--}}
            {{--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"--}}
            {{--                         fill="currentColor">--}}
            {{--                        <path fill-rule="evenodd"--}}
            {{--                              d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"--}}
            {{--                              clip-rule="evenodd"/>--}}
            {{--                    </svg>--}}
            {{--                </button>--}}
            {{--            </div>--}}
        </div>
    </main>

@endsection