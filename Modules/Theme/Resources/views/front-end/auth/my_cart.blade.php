@extends('theme::front-end.master')
@section('style')

@endsection

@section('content')
    <main>
        <section class="pb-4 w-full">
            <div id="status"
                 class="py-2 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 shadow-[0px_4px_20px_0px_rgba(0,0,0,0.1)]">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-2 lg:gap-y-0 items-center">
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-primary-700 items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/Paper.svg') }}" class="lazyload"/>
                            <span class="text-sm lg:text-base text-primary-700">Confirming</span>
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/Bag.svg') }}" class="lazyload"/>

                            <span class="text-sm lg:text-base text-[#847D79]">preparing food</span>
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400 hidden lg:block"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/deliver.svg') }}" class="lazyload"/>
                            <span class="text-sm lg:text-base text-[#847D79]">In progress</span>
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/box.svg') }}" class="lazyload"/>
                            <span class="text-sm lg:text-base text-[#847D79]">Delivered</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-2 px-4 bg-[#fcfcfc] lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <!-- Order Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-[1.5fr,1fr] gap-4">
                <div class="mt-6">
                    <div>
                        <h2 class="text-lg font-normals tracking-tighte-[1%] text-[#120F0F] leading-[120%] lg:text-xl">
                            Order Confirmation
                        </h2>

                        <!-- Address Input -->
                        <div class="flex flex-col gap-2 mt-4 items-end md:items-center md:flex-row justify-between">
                            <div class="flex items-center w-full justify-between md:w-[70%] h-11 border border-[#74CA45] rounded-xl px-3 py-[10px] bg-[#F9F8F6]">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <img data-src="{{ url('assets/icons/cart/addr.svg') }}" alt="addr"
                                             class="lazyload"/>
                                    </div>
                                    <input
                                            type="text"
                                            class="w-full text-[#3C3836] outline-none bg-[#F9F8F6]"
                                            value="3831 Cedar Lane
                      , MA 02143 "
                                    />
                                </div>
                                <button class="text-white px-3 py-1 rounded-lg ml-2">
                                    <img
                                            data-src="{{ url('assets/icons/cart/Edit.svg') }}"
                                            alt="edit"
                                            id="open-modal-location" class="lazyload"
                                    />
                                </button>
                            </div>
                            <button
                                    class="h-11 text-[#3C3836] w-fit px-[10px] py-3 rounded-xl bg-[#F9F8F6]"
                            >
                                Direct to
                                <restaurant></restaurant>
                            </button>
                        </div>
                        <!-- shipping input  -->
                        <div>
                            <div
                                    class="flex items-center gap-2 mt-4 justify-between text-sm md:text-base"
                            >
                                <h6>Shipping options</h6>
                                <p>Distance : 1,2 km</p>
                            </div>
                            <div
                                    class="grid grid-cols-1 gap-2 mt-3 md:grid-cols-2 md:gap-6"
                            >
                                <div
                                        class="option flex items-center w-full justify-between h-11 border border-[#74CA45] rounded-xl px-3 py-[10px] bg-green-100 cursor-pointer"
                                        onclick="selectOption(this)"
                                >
                                    <div class="flex items-center gap-2">
                                        <h5 class="text-sm text-[#847D79]">Super fast</h5>
                                        <p class="text-[#3C3836] font-medium">10 mins</p>
                                    </div>
                                    <p class="text-[#3C3836] font-medium">$ 1,00</p>
                                </div>
                                <div
                                        class="option flex items-center w-full justify-between h-11 border border-[#74CA45] rounded-xl px-3 py-[10px] bg-[#F9F8F6] cursor-pointer"
                                        onclick="selectOption(this)"
                                >
                                    <div class="flex items-center gap-2">
                                        <h5 class="text-sm text-[#847D79]">Basic</h5>
                                        <p class="text-[#3C3836] font-medium">25 mins</p>
                                    </div>
                                    <p class="text-[#3C3836] font-medium">$ 1,00</p>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Options -->
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <h3 class="text-md text-[#14142A] text-base font-medium">
                            Order Summary
                        </h3>
                        <div class="flex items-center gap-1 cursor-pointer">
                            <img data-src="./assets/icons/cart/Plus.svg" alt=""/>
                            <button class="text-sm md:text-base text-[#74CA45]">
                                Add List
                            </button>
                        </div>
                    </div>
                    <!-- list item cart -->
                    <div class="mt-3 p-4 bg-[#faf9f7] w-full rounded-2xl">
                        @include('theme::front-end.ajax.cart')

                    </div>
                    <!-- payment -->
                    <div>
                        <h4 class="text-[#14142A] mb-3 font-medium text-sm lg:text-base">
                            Choose another payment method
                        </h4>
                        <div class="grid grid-cols-2 gap-6">
                            <div
                                    class="payment-option flex items-center justify-between py-3 px-4 rounded-2xl border border-[#74CA45] cursor-pointer"
                                    data-method="cash"
                            >
                                <div class="flex items-center gap-2">
                                    <input
                                            type="radio"
                                            name="payment"
                                            class="accent-[#333333]"
                                    />
                                    <label class="text-[#333333] text-sm">Cash</label>
                                </div>
                                <img
                                        data-src="{{ url('assets/icons/cart/pay.png') }}"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6 lazyload"
                                />
                            </div>
                            <div
                                    class="payment-option flex items-center justify-between py-3 px-4 rounded-2xl bg-[#F9F8F6] border cursor-pointer"
                                    data-method="credit"
                            >
                                <div class="flex items-center gap-2">
                                    <input
                                            type="radio"
                                            name="payment"
                                            class="accent-[#333333]"
                                    />
                                    <label class="text-[#333333] b text-sm">Credit Card</label>
                                </div>
                                <img
                                        data-src="{{ url('assets/icons/cart/pay.png') }}"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6 lazyload"
                                />
                            </div>
                            <div
                                    class="payment-option flex items-center justify-between py-3 px-4 rounded-2xl border bg-[#F9F8F6] cursor-pointer"
                                    data-method="bank"
                            >
                                <div class="flex items-center gap-2">
                                    <input
                                            type="radio"
                                            name="payment"
                                            class="accent-[#333333]"
                                    />
                                    <label class="text-[#333333] text-sm">Bank Transfer</label>
                                </div>
                                <img
                                        data-src="{{ url('assets/icons/cart/pay.png') }}"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6 lazyload"
                                />
                            </div>
                            <div
                                    class="payment-option flex items-center justify-between py-3 px-4 rounded-2xl border bg-[#F9F8F6] cursor-pointer"
                                    data-method="bank"
                            >
                                <div class="flex items-center gap-2">
                                    <input
                                            type="radio"
                                            name="payment"
                                            class="accent-[#333333]"
                                    />
                                    <label class="text-[#333333] text-sm">Bank Transfer</label>
                                </div>
                                <img
                                        data-src="{{ url('assets/icons/cart/pay.png') }}"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6 lazyload"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- total -->
                <div>
                    <h3 class="text-lg mt-6 lg:text-xl text-[#120F0F]">Summary</h3>
                    <div class="bg-[#F9F8F6] mt-4 rounded-[20px] h-fit p-4">
                        <h6
                                class="text-[#363E57] mb-3 text-base lg:text-lg tracking-[1%]"
                        >
                            Orders (3 dishes)
                        </h6>
                        <div
                                class="flex flex-col gap-2 py-4 border-b border-b-[#CEC6C5] border-t border-t-[#CEC6C5]"
                        >
                            <div
                                    class="flex text-sm lg:text-base text-[#847D79] justify-between mt-2"
                            >
                                <span>Subtotal</span>
                                <span class="text-[#091230] font-medium text-sm lg:text-base"
                                >$12.00</span
                                >
                            </div>
                            <div
                                    class="flex text-sm lg:text-base text-[#847D79] justify-between"
                            >
                                <span>Discount</span>
                                <span class="text-[#F17228] font-medium text-sm lg:text-base"
                                >-$2.00</span
                                >
                            </div>
                            <div
                                    class="flex text-sm lg:text-base text-[#847D79] justify-between"
                            >
                                <span>Shipping Fee</span>
                                <span class="text-[#091230] font-medium text-sm lg:text-base"
                                >$1.00</span
                                >
                            </div>
                        </div>
                        <div
                                class="flex justify-between text-base lg:text-lg text-[#120F0F] font-medium mt-4 mb-6"
                        >
                            <span>Total</span>
                            <span>$11.00</span>
                        </div>
                        <button
                                class="bg-[#74CA45] text-white w-full rounded-[120px] py-3 px-4 hover:bg-[#74CA45]/80 transition duration-300 ease-in-out"
                        >
                            Check Out
                        </button>
                        <div class="py-4 px-3 rounded-2xl bg-[#F1EFE9] mt-4">
                            <div
                                    class="flex items-center justify-between cursor-pointer"
                                    id="open-modal-voucher"
                            >
                                <div
                                        class="flex items-center gap-1 text-sm lg:text-base text-[#F17228]"
                                >
                                    <img data-src="{{ url('assets/icons/cart/Ticket.svg') }}" alt="ticket"
                                         class="lazyload"/>
                                    Voucher
                                </div>
                                <div>
                                    <img data-src="{{ url('assets/icons/cart/left.svg') }}" alt="" class="lazyload"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-[2fr,1fr] gap-2 mt-2">
                                <input
                                        type="text"
                                        placeholder="Enter promo code"
                                        class="text-[#847D79] text-sm px-3 lg:text-basse outline-none rounded-2xl"
                                />
                                <button
                                        class="p-3 rounded-2xl bg-[#F17228] text-white hover:bg-[#F17228]/80 transition duration-300 ease-in-out"
                                >
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- slider other -->
        <section id="other" class="py-12">
            <div class="flex flex-col gap-2">
                <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    <div class="flex items-center justify-between">
                        <h2 class="capitalize text-3xl md:text-4xl font-medium">
                            Other offers
                        </h2>
                        <a href="{{ url('foods') }}" class="text-base text-primary font-medium">See all</a>
                    </div>
                </div>
                <div class="swiper local-favorites-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    <div class="swiper-wrapper my-4">
                        <!-- Start of one local favorite item -->
                        @forelse($productsFavorite as $itemP)
                            <div class="swiper-slide">
                                <div data-id="{{ $itemP->id }}"
                                     class="selectProduct cursor-pointer relative block rounded-xl overflow-hidden pt-2 px-2 pb-3 w-full border border-solid border-black/10 transition-all hover:shadow-[0_2px_0_0_#75ca45,0_-2px_0_0_#75ca45,-2px_0_0_0_#75ca45,2px_0_0_0_#75ca45,0_5px_0_0_#75ca45]">
                                    <div class="skeleton absolute inset-0 bg-gray-200 z-50"></div>
                                    <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                         data-src="{{ url($itemP->image) }}"
                                         class="aspect-square rounded-2xl object-cover w-full lazyload"/>
                                    <div class="p-3 absolute top-2 left-0 right-0 flex items-start md:items-center justify-between z-10">
                                      <span class="w-9 h-9 flex rounded-full bg-black/30">
                                        <img data-src="{{ url('assets/icons/heart_line_icon.svg') }}"
                                             class="m-auto lazyload"/>
                                      </span>
                                        <div class="flex items-center flex-col md:flex-row gap-1">
                                        <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto w-full md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                                          <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}"
                                               class="w-6 h-6 lazyload"/> 20% off </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <h3 class="text-left font-medium text-lg md:text-[22px] leading-snug capitalize">{{ $itemP->name }}</h3>
                                        <div class="flex items-center justify-between font-medium">
                                            <div class="flex items-center gap-1 text-base md:text-lg">
                                                <span class="text-muted line-through">${{ number_format($itemP->price ?? 0 + 5, 2) }}</span>
                                                <span class="text-secondary">${{ number_format($itemP->price ?? 0, 2) }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-400">
                                                <img data-src="{{ url('assets/icons/cart.svg') }}"
                                                     class="w-8 h-8 lazyload"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <img data-src="{{ url('images/no-data.webp') }}" class="lazyload">
                        @endforelse
                    </div>
                </div>

            </div>
        </section>
    </main>
    @include('theme::front-end.modals.location')
    @include('theme::front-end.modals.voucher')
@endsection
@section('script')
    <script src="{{ url('assets/js/local-favorite-slider.js') }}"></script>
    <script src="{{ url('assets/js/cart.js') }}"></script>
    <script src="{{ url('assets/js/handle-voucher-cart-page.js') }}"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".increment").forEach((button) => {
                button.addEventListener("click", function () {
                    let counter = this.nextElementSibling;
                    counter.textContent = parseInt(counter.textContent) + 1;
                });
            });

            document.querySelectorAll(".decrement").forEach((button) => {
                button.addEventListener("click", function () {
                    let counter = this.previousElementSibling;
                    let currentValue = parseInt(counter.textContent);
                    if(currentValue === 1) return;
                    if (currentValue > 0) {
                        counter.textContent = currentValue - 1;
                    }
                });
            });
        });
    </script>

@endsection