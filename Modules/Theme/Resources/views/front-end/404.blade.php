@extends('theme::front-end.master')
@section('title')
    <title>{{ '404: Page not found - ' . $settings['meta_title'] }}</title>
    <META NAME="KEYWORDS" content="{{ $settings['meta_keyword'] }}"/>
    <meta name="description" content="{{ $settings['meta_description'] }}"/>
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
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ trans('theme::frontend.error_page.not_found') }}"
           }
          }
         ]
        }
    </script>
@endsection
@section('content')
    <div class="container">
        <h1 class="title-h2 title-font text-center pt-30 pb-30">
            Oops! Không tìm thấy trang.
        </h1>
        <div class="text-center">
            <div class="flex align-content-center w-full" style="display: flex; justify-content: center">
                <img data-src="{{ url('images/no-data.webp') }}" class="lazyload">
            </div>
            Trang này đang bị lỗi bạn vui lòng quay trở lại trang chủ, <a class="text-medium text-primary hover:text-primary-700" href="{{ url('/') }}">{{ __('Home') }}</a>
        </div>
    </div>
@endsection

