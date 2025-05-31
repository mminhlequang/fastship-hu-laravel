<div class="grid grid-cols-1 xl:grid-cols-4 gap-4 md:gap-6">
    @forelse($data as $item)
        @include('theme::front-end.components.product')
    @empty
        <div class="flex flex-col items-center justify-center gap-6 mt-4 text-center">
            <img src="{{ url('images/no-data.webp') }}" width="190" height="160" class="mx-auto">
            <h6 class="text-dark font-medium">Nothing to Show</h6>
        </div>
    @endforelse
</div>