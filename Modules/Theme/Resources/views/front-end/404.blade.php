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
@section('breadcrumb')
    <div class="breadcrumb breadcrumb-fixed justify-content-center">
        <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
        <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
        <span>{{ trans('theme::frontend.error_page.not_found') }}</span>
    </div>
@endsection
@section('content')
    <div class="container article article-detail">
        <h1 class="title-h2 title-font text-center pt-30 pb-30">
            {{ trans('theme::frontend.error_page.not_found') }}
        </h1>
        <div class="text-center">
            {{ trans('theme::frontend.error_page.sorry_page') }}, <a class="intro-btn" href="{{ url('/') }}">{{ trans('theme::frontend.home') }}</a>
        </div>
    </div>
@endsection

