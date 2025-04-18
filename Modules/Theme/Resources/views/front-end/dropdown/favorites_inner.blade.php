@forelse($products as $item)
    <div class="flex flex-col md:flex-row justify-between items-start gap-3 mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start gap-3 w-full">
            <!-- Left: Image + Info -->
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                <!-- Image -->
                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" src="{{ url($item->image) }}" class="w-9 h-9 object-cover" alt="{{ $item->name }}">
                <!-- Text + Price -->
                <div class="flex flex-col gap-1 w-full">
                    <p class="text-[#14142A] text-sm md:text-base">{{ $item->name }}</p>
                    <div class="flex ">
                        <p class="text-base text-black">{{ number_format($item->price + 5, 2) }} €</p>&nbsp;
                        <p class="text-base text-secondary">{{ number_format($item->price, 2) }} €</p>
                    </div>
                </div>
            </div>
            <!-- Right: Delete button -->
            <div class="removeFavoriteProduct" data-id="{{ $item->id }}">
                <img src="{{ url('assets/icons/cart/close.svg') }}" alt="Delete" >
            </div>
        </div>
    </div>
@empty
    <div class="flex justify-items-center">
        <img src="{{ url('images/no-data.webp') }}">
    </div>
@endforelse
