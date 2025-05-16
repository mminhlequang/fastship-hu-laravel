@if(isset($product))
<!-- Close Button -->


<!-- Product Header -->
<div class="px-8 pt-8 pb-4 mb-3">
    <div class="flex justify-between mb-3">
        <h3 class="text-lg font-medium">
            {{ $product->name }}
        </h3>
        <button
            onclick="toggleModal('modalOverlayProduct')"
            class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Product Content -->
    <!-- Product Image & Price -->
    <div class="flex flex-wrap flex-col items-start">
        <div class="relative block w-full pb-3 border-b border-gray-200 border-dashed mb-3">
            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" data-src="{{ url($product->image) }}"
                class="aspect-square rounded-2xl object-cover w-full h-[300px] lazyload " />
            <div class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10">
                <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                    <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}" class="w-6 h-6 lazyload" />
                    20% off
                </span>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="flex items-center justify-between font-medium">
                <div class="mb-1">
                    <span class="font-medium text-lg">Price:</span>
                </div>
                <div class="flex items-center gap-1 text-base md:text-lg">
                    <span class="text-muted line-through">{{ number_format($product->price + 5, 2) }}&nbsp;Ft</span><span class="text-secondary">{{ number_format($product->price, 2) }}&nbsp;Ft</span>
                </div>
            </div>
            <p class="text-sm text-gray-500 line-clamp-2" id="product-description">
                {{ $product->description }}
            </p>
            <button onclick="toggleDescription()" class="text-primary text-sm mt-1 hover:underline">
                <span id="show-more-text">show more</span>
            </button>
        </div>
    </div>
    @foreach($product->variationsX as $itemV)
    <div class="mb-4" data-group="variation-{{ $itemV->id }}">
        <p class="text-sm font-medium text-gray-700 mb-2">
            {{ $itemV->name }}
        </p>
        <div class="space-y-2">
            @foreach($itemV->values as $keyVL => $itemVL)
            <label class="flex items-center justify-between p-2 border rounded-md {{ ($keyVL == 0) ? 'bg-green-50 border-primary' : '' }}">
                <div>
                    <input
                        type="radio"
                        name="variation_{{ $itemV->id }}"
                        value="{{ $itemVL->id }}"
                        data-price="{{ $itemVL->price }}"
                        class="hidden variation-radio"
                        {{ $keyVL == 0 ? 'checked' : '' }} />
                    <span class="text-sm">{{ $itemVL->value }}</span>
                </div>
                <span class="text-sm text-gray-500">+{{ number_format($itemVL->price, 2) }}&nbsp;Ft</span>
            </label>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
<!-- Quantity and Add to Order -->
<div class="px-8 py-3 -shadow flex gap-6 items-center justify-between">
    <div class="flex items-center border rounded-full p-3">
        <button id="decreaseBtn" class="w-6 h-6 text-lg flex items-center justify-center">
            -
        </button>
        <span id="quantity" class="mx-4 text-lg font-normal">1</span>
        <button id="increaseBtn" class="w-6 h-6 text-lg flex items-center justify-center">
            +
        </button>
    </div>
    <button
        id="addToOrderBtn" data-id="{{ $product->id }}" data-store="{{ $product->store_id }}"
        class="bg-primary text-white w-full py-3 rounded-full hover:bg-primary-700">
        Add to order • {{ number_format($product->price, 1) }}&nbsp;Ft
    </button>
    <input type="hidden" name="inputPrice" id="inputPrice" value="{{ $product->price }}">
</div>

<script>
    function toggleDescription() {
        const description = document.getElementById('product-description');
        const showMoreText = document.getElementById('show-more-text');

        if (description.classList.contains('line-clamp-2')) {
            description.classList.remove('line-clamp-2');
            showMoreText.textContent = 'show less';
        } else {
            description.classList.add('line-clamp-2');
            showMoreText.textContent = 'show more';
        }
    }
</script>

@endif