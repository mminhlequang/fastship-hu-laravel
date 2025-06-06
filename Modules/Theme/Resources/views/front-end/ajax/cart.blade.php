<!--list Items  -->
@forelse($carts as $itemC)
    <div class="flex flex-col border border-primary rounded-2xl p-4 mb-4 shadow-lg bg-gray-50">
        <div class="flex flex-col flex-wrap items-start">
            <div class="flex flex-wrap items-center flex-row">
                <img alt="Fast Ship Hu"  onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                     src="{{ url(optional($itemC->store)->avatar_image) }}" class="w-5 h-5 rounded-circle">&nbsp;
                {{ optional($itemC->store)->name }}
            </div>
            <div class="flex flex-wrap items-center justify-center border-b py-2 mt-2 space-x-2 text-sm text-gray-500">
                <div class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2"><span
                            class="text-black">Over 15 mins</span></div>
                <div class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2">
                    <span class="text-black">1.8 km</span></div>
                <div class="flex items-center shadow-sm rounded-full border border-gray-300 px-4 py-2"><img
                            src="{{ url('assets/icons/shipper_icon.svg') }}"
                            class="w-4 h-4 mr-1" alt="Fast Ship Hu"><span
                            class="text-black">) Ft</span></div>
            </div>
        </div>
        @foreach($itemC->cartItems as $item)
            <div class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1] mb-4">
                <div class="flex flex-col md:flex-row items-center gap-3">
                    <div class="cursor-pointer deleteCart" data-id="{{ $item->id }}"><img
                                src="{{ url('assets/icons/cart/close.svg') }}" alt="Burger"></div>
                    <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                         src="{{ url($item->product['image']) }}" class="w-[36px] h-[36px]" alt="Burger">
                    <div class="">
                        <p class="text-[#14142A] text-sm md:text-base">{{ $item->product['name'] ?? '' }}</p>
                        @if($item->variations != null)
                            @foreach($item->variations as $itemV)
                                <span class="text text-sm text-[#14142A]">
                                    {{ $itemV['variation']['name'] ?? '' }}: {{ $itemV['value'] }} {{ $itemV['price'] }} Ft
                                </span>@if(!$loop->last), @endif
                            @endforeach
                        @endif
                        <p class="text text-sm text-[#7D7575] w-full md:w-[306px] line-clamp-2">{{ $item->product['description'] ?? '' }}</p>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center lg:items-start w-full md:w-[37%] gap-8">
                    <p class="text-base md:text-lg font-medium text-black">{{ number_format($item->product['price'], 0, '.', '') }} Ft</p>
                    <div class="flex items-center justify-between bg-[#fff] h-[36px] w-full max-w-[128px] px-3 rounded-[46px] gap-3">
                        <button class="text-xl rounded decrement" data-id="{{ $item->id }}">-</button>
                        <p class="counter">{{ $item->quantity }}</p>
                        <button class="text-xl rounded increment" data-id="{{ $item->id }}">+</button>
                    </div>
                    <p class="text-base md:text-lg font-medium text-[#F17228]">{{ number_format($item->price, 0, '.', '') }} Ft</p>
                </div>
            </div>
        @endforeach
        <form method="POST" action="{{ url('check-out') }}"
              class="rounded-full py-1.5 px-8 border border-primary bg-primary text-white hover:bg-primary-700 text-center">
            @csrf
            <input type="hidden" name="store_id" value="{{ $itemC->store_id }}">
            <button type="submit">
                {{ __('Check out now') }}&nbsp;({{ number_format($itemC->cartItems()->sum('price'), 0, '.', '') }} Ft)
            </button>
        </form>
    </div>

@empty
    <div class="flex flex-col items-center justify-center gap-6 mt-4 text-center">
        <img src="{{ url('assets/images/empty_cart.svg') }}" width="190" height="160" class="mx-auto">
        <h6 class="text-dark font-medium">Nothing to Show</h6>
    </div>
@endforelse
