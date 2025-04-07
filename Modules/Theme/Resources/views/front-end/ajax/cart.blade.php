<!--list Items  -->
<div class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1]">
    <div class="flex flex-col md:flex-row items-center gap-3">
        <div class="cursor-pointer">
            <img data-src="{{ url('assets/icons/cart/close.svg') }}" alt="Burger" class="lazyload"/>
        </div>
        <img data-src="{{ url('assets/icons/cart/pr.png') }}" alt="Burger" class="lazyload"/>
        <div class="">
            <p class="text-[#14142A] text-sm md:text-base">
                Pork cutlet burger and drink set
            </p>
            <p
                    class="text text-sm text-[#7D7575] w-full md:w-[306px] line-clamp-2"
            >
                We cannot respond to requests for changes to your order
                once it has We cannot respond to requests for changes to
                your order once it has
            </p>
        </div>
    </div>
    <div
            class="flex flex-row justify-between items-center lg:items-start w-full md:w-[37%] gap-8"
    >
        <p class="text-base md:text-lg font-medium text-[#F17228]">
            $2.20
        </p>
        <div
                class="flex items-center justify-between bg-[#fff] h-[36px] w-full max-w-[128px] px-3 rounded-[46px] gap-3"
        >
            <button class="text-xl rounded increment">+</button>
            <p class="counter">1</p>
            <button class="text-[#D5D5D5] text-xl rounded decrement">
                -
            </button>
        </div>

        <img
                data-src="{{ url('assets/icons/cart/Edit.svg') }}"
                alt="edit"
                class="size-6 object-cover lazyload"
        />
    </div>
</div>
