@extends('theme::front-end.master')
@section('title')
    <title>{{ \App\Helper\LocalizationHelper::getNameByLocale($news) }}</title>
    <meta name="description"
          content="{{ $news->description }}"/>
    <meta name="keywords" content="{{ $news->description }}"/>
@endsection
@section('facebook')
    <meta property="og:title" content="{{ $news->title }}"/>
    <meta property="og:description"
          content="{{ $news->description }}"/>
    <meta property="og:image"
          content="{{ url($news->image) }}"/>
    <meta property="og:image:type" content="image/jpeg"/>
    <meta property="og:image:width" content="600"/>
    <meta property="og:image:height" content="315"/>
@endsection
@section('schema')
    <script type="application/ld+json">
        {
         "@context": "http://schema.org",
         "@type": "BreadcrumbList",
         "itemListElement":
         [
          {
           "@type": "ListItem",
           "position": 1,
           "item":
           {
            "@id": "{{ url('/')}}",
            "name": "{{ trans('theme::frontend.home.home') }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 3,
          "item":
           {
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ \App\Helper\LocalizationHelper::getNameByLocale($news) }}"
           }
          }
         ]
        }


    </script>
@endsection

@section('content')
    <div class="w-full h-48 relative overflow-hidden">
        <img data-src="{{ url($news->image) }}" alt="Banner" class="w-full h-full object-cover lazyload">
        <div class="absolute inset-0 flex items-center">
            <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                <!-- Breadcrumb -->
                <div class="flex flex-wrap items-center justify-between mb-2">
                    <div class="flex flex-wrap items-center text-sm text-white">
                        <a href="{{ url('') }}" class="text-gray-50 hover:text-green-200">Home</a>
                        <span class="text-muted mx-2">|</span>
                        <a href="{{ url('news') }}" class="text-gray-50 hover:text-green-200">Blog</a>
                        <span class="text-muted mx-2">|</span>
                        <span class="text-white-50">{{ \App\Helper\LocalizationHelper::getNameByLocale($news) }}</span>
                    </div>

                    <!-- Share Button aligned right -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('news/'.$news->slug).'.html' }}"
                       rel="nofollow" target="_blank"  class="flex items-center bg-white shadow-md py-2 px-4 rounded-2xl ml-auto cursor-pointer">
                        <img src="{{ url('assets/icons/share.svg') }}" alt="Fast Ship Hu" class="w-4 h-4 mr-2">
                        Share
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content container -->
    <main class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 py-6">
        <!-- Main content box with shadow -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <!-- Blog post metadata -->
            <div class="pb-0">
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <span class="mr-1">Blog</span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-secondary">{{ $news->created_at->format('M d, Y') }}</span>
                </div>

                <!-- Blog post title -->
                <h1 class="text-3xl font-bold mb-6">{{ \App\Helper\LocalizationHelper::getNameByLocale($news) }}</h1>

                <!-- Blog post content -->
                <div class="prose max-w-none">
                    <p class="mb-6 text-gray-700 leading-relaxed">
                        {!! $news->content !!}
                    </p>
                </div>
            </div>


        </div>

        <!-- Related News Section -->
        <div class="mt-16 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Related News</h2>
                <a href="{{ url('news') }}"
                   class="inline-flex items-center rounded-full py-2.5 px-6 bg-primary text-white hover:bg-primary-700">
                    See more
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>

            <!-- Related articles grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($otherNews as $item)
                    <a
                            href="{{ url('news/'. $item->slug.'.html') }}"
                            class="flex flex-col gap-4 p-4 rounded-xl shadow-md bg-white transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]"
                    >
                        <img
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
        </div>
    </main>
@endsection