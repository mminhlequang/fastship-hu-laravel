@extends('theme::front-end.master')

@section('content')
    <main>
        <!-- Hero Section -->
        <div class="relative h-48 bg-gray-900 text-white flex items-center">
            <div class="absolute inset-0 z-0 bg-center bg-cover opacity-60"
                 style="background-image: url('{{ url('assets/images/article_img_1.webp') }}')"></div>
            <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 px-6 z-10">
                <h1 class="text-4xl font-bold mb-2">Event and blog</h1>
                <p class="text-xl text-gray-300">Boost visibility and sales on the Grab platform with these tips.</p>
            </div>
        </div>

        <!-- Blog Grid -->
        <div class="mx-auto px-4 py-4 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-4">
                @foreach ($news->take(2) as $item)
                    <a
                            href="{{ url('news/'. $item->slug.'.html') }}"
                            class="relative flex flex-col gap-4 p-4 rounded-xl shadow-md bg-white transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]"
                    >
                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                data-src="{{ url($item->image) }}"
                                class="w-full rounded-xl aspect-[16/10] object-cover lazyload"

                        />
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center text-muted text-sm gap-4">
                                <span>Blog</span>
                                <span>|</span>
                                <span class="text-secondary">{{ $item->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-lg">{{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-4">
                @foreach ($news->skip(2) as $item)
                    <a
                            href="{{ url('news/'. $item->slug.'.html') }}"
                            class="relative flex flex-col gap-4 p-4 rounded-xl shadow-md bg-white transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]"
                    >

                        <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                data-src="{{ url($item->image) }}"
                                class="w-full rounded-xl aspect-[16/10] object-cover lazyload"

                        />
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center text-muted text-sm gap-4">
                                <span>Blog</span>
                                <span>|</span>
                                <span class="text-secondary">{{ $item->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-lg">{{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</p>
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