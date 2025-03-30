@extends('theme::front-end.master')
@section('title')
    <title>{{ $news->title }}</title>
    <meta name="description" content="{{ !empty($news->description) ? \Illuminate\Support\Str::limit($news->description, 200) : $settings['meta_description'] }}"/>
    <meta name="keywords" content="{{ !empty($news->keywords) ? $news->keywords : $settings['meta_keyword'] }}" />
@endsection
@section('facebook')
    <meta property="og:title" content="{{ $news->title }}" />
    <meta property="og:description" content="{{ !empty($news->description) ? $news->description : !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}" />
    <meta property="og:image" content="{{ !empty($news->image) ? asset($news->image) : asset(Storage::url($settings['company_logo'])) }}" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="315" />
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
           "position": 2,
           "item":
           {
            "@id": "{{ url(optional($category->parent)->slug . '/' . $category->slug) }}",
            "name": "{{ $category->title }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 3,
          "item":
           {
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ $news->title }}"
           }
          }
         ]
        }
    </script>
@endsection

@section('content')
    <main class="max-w-screen-xl mx-auto">
        <!-- Hero Section -->
        <div class="relative h-48 bg-gray-900 text-white flex items-center">
            <div class="absolute inset-0 z-0 bg-center bg-cover opacity-60" style="background-image: url('https://via.placeholder.com/1920x300')"></div>
            <div class="container mx-auto px-6 z-10">
                <h1 class="text-4xl font-bold mb-2">Event and blog</h1>
                <p class="text-xl">Boost visibility and sales on the Grab platform with these tips.</p>
            </div>
        </div>

        <!-- Blog Grid -->
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Blog Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Delivery person" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Feb 21, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Stay on Top of Your Business: Maximize Business Data from GrabMerchant Portal</h3>
                    </div>
                </div>

                <!-- Blog Card 2 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Food delivery" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Event</span>
                            <span class="text-sm text-gray-500">Mar 19, 2025 - Mar 21, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">6 Effective Promotional Strategies to Try for Your Business</h3>
                    </div>
                </div>

                <!-- Blog Card 3 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Restaurant worker" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Mar 4, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Introducing GrabAds and the Top 3 GrabAds Campaigns That Really Work</h3>
                    </div>
                </div>

                <!-- Blog Card 4 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Promotional image" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Mar 6, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">6 Effective Promotional Strategies to Try for Your Business</h3>
                    </div>
                </div>

                <!-- Blog Card 5 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Food delivery app" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Mar 7, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">5 Reasons Why Self Pick-up Is Part of the New Normal</h3>
                    </div>
                </div>

                <!-- Blog Card 6 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Business loans" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Event</span>
                            <span class="text-sm text-gray-500">Mar 2, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Business Loans with Low Interest Rates: Where to Find Them?</h3>
                    </div>
                </div>

                <!-- Blog Card 7 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="GrabMerchant delivery" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Event</span>
                            <span class="text-sm text-gray-500">Mar 18, 2025 - Mar 21, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Why the GrabMerchant Portal Is Your Best Business Partner</h3>
                    </div>
                </div>

                <!-- Blog Card 8 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Food delivery service" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Event</span>
                            <span class="text-sm text-gray-500">Mar 18, 2025 - Mar 21, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Stay on Top of Your Business: Maximize Business Data from GrabMerchant Portal</h3>
                    </div>
                </div>

                <!-- Blog Card 9 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Food containers" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Feb 21, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Business Loans with Low Interest Rates: Where to Find Them?</h3>
                    </div>
                </div>

                <!-- Blog Card 10 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Grab delivery person" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Feb 22, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Why the GrabMerchant Portal Is Your Best Business Partner</h3>
                    </div>
                </div>

                <!-- Blog Card 11 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://via.placeholder.com/600x400" alt="Food delivery simulator" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-500 mr-3">Blog</span>
                            <span class="text-sm text-gray-500">Feb 27, 2025</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Stay on Top of Your Business: Maximize Business Data from GrabMerchant Portal</h3>
                    </div>
                </div>
            </div>

            <!-- See More Button -->
            <div class="flex justify-center mt-8">
                <button class="bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-3 rounded-full flex items-center transition duration-200">
                    See more
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </main>
@endsection