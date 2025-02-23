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
@section('breadcrumb')
    <div class="breadcrumb breadcrumb-fixed">
        <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
        &nbsp;/&nbsp;
        <a href="{{ url(optional($category->parent)->slug . '/' . $category->slug) }}">{{ $category->title }}</a>
        &nbsp;/&nbsp;<span>{{ $news->title }}</span>
    </div>
@endsection
@section('content')
    <div class="article article-detail">
        <div class="row">
            <div class="col-12 col-lg-9 pl-30">
                <h1 class="title-h2">
                    {{ $news->title }}
                </h1>
                <p class="fb-like" data-href="{{ Request::fullUrl() }}" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true"></p>
                <p class="article-postdate">
                    <i class="fas fa-calendar-alt"></i> {{ Carbon\Carbon::parse($news->updated_at)->format(config('settings.format.date')) }}
                </p>
                @if(!empty($news->description))
                    <p class="article-summary">
                        <i>{!! $news->description !!}</i>
                    </p>
                @endif
                <div class="article-content">
                    {!! $news->content !!}
                    <div class="fb-comments" data-href="{{ Request::fullUrl() }}" data-width="100%" data-numposts="5"></div>
                </div>
            </div>
            @include('theme::front-end.news.sidebar')
        </div>
        @if($otherNews->count() > 0)
            @include('theme::front-end.news.other')
        @endif
    </div>
@endsection