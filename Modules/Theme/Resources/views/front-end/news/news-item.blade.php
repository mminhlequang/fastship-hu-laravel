@if($news->count() > 0)
@foreach($news as $item)
<div class="col-12 col-sm-6 col-md-4 item mb-15">
    <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" class="image-responsive image-responsive--md">
        <img class="image-responsive--lg card-img-top lazyload" data-src="{{ !empty($item->image)?asset($item->image):asset('/images/lazy-img.jpg') }}">
    </a>
    <div class="item-body">
        <h3 class="item-title">
            <a class="item-link" href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">{{ $item->title }}</a>
        </h3>
        <span class="item-postdate">
            <i class="fas fa-calendar-alt"></i> {{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}
        </span>
        @empty(!$item->description)
        <p class="item-text">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</p>
        @endempty
    </div>
    {{-- <div class="item-footer">--}}
    {{-- <a class="item-link--more" href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">{{ trans('theme::frontend.read_more') }}</a>--}}
    {{-- </div>--}}
</div>
@endforeach
@else
<div class="col-12 text-center">
    {{ trans('frontend.data_updated') }}
</div>
@endif