<!--list Items  -->
@forelse($carts as $item)
    <div class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1]">
        <div class="flex flex-col md:flex-row items-center gap-3">
            <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'" src="{{ url($item->product['image']) }}" class="w-[36px] h-[36px]" alt="Burger">
            <div class=""><p class="text-[#14142A] text-sm md:text-base">{{ $item->product['name'] ?? '' }}</p>
                @if($item->variations != null)
                    @foreach($item->variations as $itemV)
                        <span class="text text-sm text-[#14142A]">
                                    {{ $itemV['variation']['name'] ?? '' }}: {{ $itemV['value'] }} {{ $itemV['price'] }} €
                                </span>@if(!$loop->last), @endif
                    @endforeach
                @endif
                <p class="text text-sm text-[#7D7575] w-full md:w-[306px] line-clamp-2">{{ $item->product['description'] ?? '' }}</p>
            </div>
        </div>
        <div class="flex flex-row justify-between items-center lg:items-start w-full md:w-[37%] gap-8">
            <p class="text-base md:text-lg font-medium text-black">{{ number_format($item->product['price'], 2) }} €</p>
            <p class="text-base md:text-lg font-medium text-black">x{{ $item->quantity }} </p>
            <p class="text-base md:text-lg font-medium text-[#F17228]">{{ number_format($item->price, 2) }} €</p>
        </div>
    </div>
@empty
    <div class="flex flex-col justify-items-center items-center">
        <div>
            <img src="{{ url('assets/images/empty_cart.svg') }}" alt="Fast Ship Hu">
        </div>
        <div class="text-2xl font-medium">{{ __('theme::web.cart_empty') }}</div>
    </div>
@endforelse
