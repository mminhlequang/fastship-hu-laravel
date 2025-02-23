@extends('theme::front-end.master')
@section('content')
<div class="container list-parent">
    <div class="breadcrumb p-0 py-3">
        <a href="{{ url('/')}}">{{ trans('theme::frontend.home.home') }}</a>
        <span class="mr_lr">&nbsp;/&nbsp;</span>
        <span>{{ ($loadMore[0]->category)->title }}</span>
    </div>
    <div class="pages-title">{{ ($loadMore[0]->category)->title}}</div>
    <div class="row">
        @foreach($loadMore as $item)
        <div class="col-sm-6 col-md-4 content-box">
            <div class="box-image-top">
                <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" class="image-responsive image-responsive--lg">
                    <img class="image-responsive--lg lazyload" data-src="{{ !empty($item->image)?asset($item->image):asset('/images/lazy-img.jpg') }}">
                </a>
            </div>
            <div class="box-news">
                <div class="box-news-group">
                    <a class="box-news-link" href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">
                        <p class="box-news-title">{{ Str::limit($item->title, 70) }}</p>
                    </a>
                    <span class="box-news-time">({{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }})</span><br>
                    <div class="load-more py-3">
                        <a href="#">{{ __('theme::news.load_more') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {!! $loadMore->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection