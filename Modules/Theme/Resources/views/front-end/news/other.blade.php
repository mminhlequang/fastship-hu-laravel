<div class="article-other">
    <h4 class="other-title text-center text-uppercase">{{ trans('theme::frontend.other_news') }}</h4>
    <div class="news-home news-other news-items owl-carousel">
        @foreach($otherNews as $item)
            <div class="item">
                <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" class="image-responsive image-responsive--md">
                    <img class="image-responsive--lg owl-lazy" data-src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                </a>
                <div class="item-body">
                    <h3 class="item-title">
                        <a class="item-link" href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">{{ $item->title }}</a>
                    </h3>
                </div>
            </div>
        @endforeach
    </div>
</div>