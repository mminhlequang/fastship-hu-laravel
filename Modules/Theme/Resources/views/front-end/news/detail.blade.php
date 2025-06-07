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
        <div class="absolute inset-0 flex">
            <div class="responsive-px w-full xl:px-[54px]">
                <!-- Breadcrumb -->
                <div class="flex flex-wrap items-center justify-between pt-[38px]">
                    <div class="flex flex-wrap items-center text-base leading-[1.6] tracking-[0.16px]">
                        <a href="{{ url('') }}" class="text-[#CEC6C5] hover:text-green-200">Home</a>
                        <span class="text-[#847D79] mx-2">|</span>
                        <a href="{{ url('blogs') }}" class="text-[#CEC6C5] hover:text-green-200">Blog</a>
                        <span class="text-[#847D79] mx-2">|</span>
                        <span class="font-medium text-[#F8F1F0]">{{ \App\Helper\LocalizationHelper::getNameByLocale($news) }}</span>
                    </div>

                    <!-- Share Button aligned right -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('blogs/'.$news->slug).'.html' }}"
                       rel="nofollow" target="_blank"  class="flex items-center bg-[#FBFBFB] border border-[#EDEDEF] text-[14px] leading-[1.6] tracking-[0.28px] py-2 px-4 rounded-2xl ml-auto cursor-pointer">
                        <img src="{{ url('assets/icons/share.svg') }}" alt="Fast Ship Hu" class="w-6 h-6 mr-2">
                        Share
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content container -->
    <main class="relative z-10">
        <div class="absolute inset-0 bg-[#f9f8f6] top-[97px]"></div>
        <div class="-mt-[97px] responsive-content  relative z-10">
            <!-- Main content box with shadow -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden px-[50px] xl:px-[100px] py-[40px] mb-[92px]">
                <!-- Blog post metadata -->
                <div class="pb-0">
                    <div class="flex items-center text-sm leading-[1.286] mb-3">
                        <span class="pr-4 text-[#282828]">Blog</span>
                        <span class="text-[#D0D0D0] pr-4">|</span>
                        <span class="text-secondary tracking-[0.14px]">{{ $news->created_at->format('M d, Y') }}</span>
                    </div>

                    <!-- Blog post title -->
                    <h1 class="text-4xl leading-[1.4] tracking-[0.36px] mb-10">{{ \App\Helper\LocalizationHelper::getNameByLocale($news) }}</h1>

                    <!-- Blog post content -->
                    <div class="prose max-w-none">
                        <p class="mb-6 text-gray-700 leading-relaxed">
                            {!! $news->content !!}
                        </p>
                    </div>
                </div>


            </div>

            <!-- Related News Section -->
            <div class="mt-16 pb-[60px]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl leading-[1.4] text-[#0B0B0B] font-medium">Related News</h2>
                    <a href="{{ url('blogs') }}"
                       class="inline-flex items-center rounded-full py-3 px-6 bg-primary text-lg leading-[1.22222] font-medium text-white hover:bg-primary-700">
                        See more
                        <span class="h-6 w-6 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </span>
                        </span>
                    </a>
                </div>

                <!-- Related articles grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($otherNews as $item)
                        <a
                                href="{{ url('blogs/'. $item->slug.'.html') }}"
                                class="flex flex-col gap-4 p-4 rounded-xl bg-white transition-all card-news"
                        >
                            <img
                                    data-src="{{ url($item->image) }}"
                                    class="w-full rounded-2xl aspect-[16/10] max-lg:max-h-[190px] object-cover lazyload"

                            />
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center text-muted text-sm gap-4">
                                    <span class="text-sm leading-[1.286] text-[#939191]">Blog</span>
                                    <span class="text-[#D0D0D0] text-[18px]">|</span>
                                    <span class="text-secondary text-sm leading-[1.2] tracking-[0.14px]">{{ $item->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-lg">{{ \App\Helper\LocalizationHelper::getNameByLocale($item) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection