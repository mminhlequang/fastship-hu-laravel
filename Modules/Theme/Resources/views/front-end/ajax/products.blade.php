<div class="grid grid-cols-1 xl:grid-cols-4 gap-4 md:gap-6">
    @forelse($data as $item)
        @include('theme::front-end.components.product')
    @empty
        <img data-src="{{ url('images/no-data.webp') }}" class="lazyload" alt="Fast Ship Hu">
    @endforelse
</div>