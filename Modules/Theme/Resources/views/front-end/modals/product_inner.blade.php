@if(isset($product))
    <!-- Close Button -->
    <button
            onclick="toggleModal('modalOverlayProduct')"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
    >
        <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                viewBox="0 0 16 16"
        >
            <path
                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
            />
        </svg>
    </button>

    <!-- Product Header -->
    <div class="p-4">
        <div class="flex flex-col items-start mb-2">
            <h3 class="text-lg font-medium">
                {{ $product->name }}
            </h3>
        </div>
    </div>

    <!-- Product Content -->
    <div class="px-4">
        <!-- Product Image & Price -->
        <div class="flex flex-wrap flex-col items-start mb-6">
            <div
                    class="relative block w-full"
            ><img
                        onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                        data-src="{{ url($product->image) }}"
                        class="aspect-square rounded-2xl object-cover w-full h-[300px] lazyload "

                />
                <div
                        class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                >
                <span
                        class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                ><img
                            data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                            class="w-6 h-6 lazyload"
                    />
                  20% off
                </span>
                </div>
                <div
                        class="flex md:items-center items-start justify-between flex-col md:flex-row gap-1.5 mt-1.5 md:mt-3 mb-1"
                >
                <span class="flex items-center capitalize gap-1.5 text-muted"
                >
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center justify-between font-medium">
                        <div class="mb-1">
                            <span class="font-medium">Price:</span>
                        </div>
                        <div class="flex items-center gap-1 text-base md:text-lg">
                    <span class="text-muted line-through">{{ number_format($product->price + 5, 2) }}&nbsp;Ft</span
                    ><span class="text-secondary">{{ number_format($product->price, 2) }}&nbsp;Ft</span>
                        </div>

                    </div>
                    <p class="text-sm text-gray-500">
                        {{ $product->description }}
                    </p>
                </div>
            </div>

        </div>

        <!-- Option Group 1 -->
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
                                        {{ $keyVL == 0 ? 'checked' : '' }}
                                />
                                <span class="text-sm">{{ $itemVL->value }}</span>
                            </div>
                            <span class="text-sm text-gray-500">+{{ number_format($itemVL->price, 2) }}&nbsp;Ft</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach


    </div>

    <!-- Quantity and Add to Order -->
    <div class="p-4 bg-gray-50 border-t flex items-center justify-between">
        <div class="flex items-center">
            <button
                    id="decreaseBtn"
                    class="w-8 h-8 flex items-center justify-center border rounded-full bg-white"
            >
                <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                >
                    <path
                            d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"
                    />
                </svg>
            </button>
            <span id="quantity" class="mx-4 text-lg font-normal">1</span>
            <button
                    id="increaseBtn"
                    class="w-8 h-8 flex items-center justify-center border rounded-full bg-white"
            >
                <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                >
                    <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
                    />
                </svg>
            </button>
        </div>
        <button
                id="addToOrderBtn" data-id="{{ $product->id }}" data-store="{{ $product->store_id }}"
                class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-700"
        >
            Add to order • {{ number_format($product->price, 2) }}&nbsp;Ft
        </button>
        <input type="hidden" name="inputPrice" id="inputPrice" value="{{ number_format($product->price, 2) }}">
    </div>

@endif