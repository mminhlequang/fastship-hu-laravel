@extends('theme::front-end.master')
@section('style')

@endsection

@section('content')
    <main>
        <section class="pb-4 w-full">
            <div
                    id="status"
                    class="py-2 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 shadow-[0px_4px_20px_0px_rgba(0,0,0,0.1)]"
            >
                <div
                        class="grid grid-cols-2 lg:grid-cols-4 gap-y-2 lg:gap-y-0 items-center"
                >
                    <div class="flex items-center">
                        <div
                                class="flex w-full flex-col border border-primary-700 items-center gap-2 px-1 py-2 rounded-xl"
                        >
                            <img src="./assets/icons/cart/Paper.svg" />
                            <span class="text-sm lg:text-base text-primary-700"
                            >Confirming</span
                            >
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
                    </div>
                    <div class="flex items-center">
                        <div
                                class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl"
                        >
                            <img src="./assets/icons/cart/Bag.svg" />

                            <span class="text-sm lg:text-base text-[#847D79]"
                            >preparing food</span
                            >
                        </div>
                        <div
                                class="w-11 border-t-2 border-dashed border-gray-400 hidden lg:block"
                        ></div>
                    </div>
                    <div class="flex items-center">
                        <div
                                class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl"
                        >
                            <img src="./assets/icons/cart/deliver.svg" />
                            <span class="text-sm lg:text-base text-[#847D79]"
                            >In progress</span
                            >
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
                    </div>
                    <div class="flex items-center">
                        <div
                                class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl"
                        >
                            <img src="./assets/icons/cart/box.svg" />
                            <span class="text-sm lg:text-base text-[#847D79]"
                            >Delivered</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section
                class="py-2 px-4 bg-[#fcfcfc] lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
        >
            <!-- Order Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-[1.5fr,1fr] gap-4">
                <div class="mt-6">
                    <div>
                        <h2
                                class="text-lg font-normals tracking-tighte-[1%] text-[#120F0F] leading-[120%] lg:text-xl"
                        >
                            Order Confirmation
                        </h2>

                        <!-- Address Input -->
                        <div
                                class="flex flex-col gap-2 mt-4 items-end md:items-center md:flex-row justify-between"
                        >
                            <div
                                    class="flex items-center w-full justify-between md:w-[70%] h-11 border border-[#74CA45] rounded-xl px-3 py-[10px] bg-[#F9F8F6]"
                            >
                                <div class="flex items-center gap-3">
                                    <div>
                                        <img src="./assets/icons/cart/addr.svg" alt="addr" />
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
                                            src="./assets/icons/cart/Edit.svg"
                                            alt="edit"
                                            id="open-modal-location"
                                    />
                                </button>
                            </div>
                            <button
                                    class="h-11 text-[#3C3836] w-fit px-[10px] py-3 rounded-xl bg-[#F9F8F6]"
                            >
                                Direct to <restaurant></restaurant>
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
                            <img src="./assets/icons/cart/Plus.svg" alt="" />
                            <button class="text-sm md:text-base text-[#74CA45]">
                                Add List
                            </button>
                        </div>
                    </div>
                    <!-- list item cart -->
                    <div class="mt-3 p-4 bg-[#faf9f7] w-full rounded-2xl">
                        <!--list Items  -->
                        <div
                                class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1]"
                        >
                            <div class="flex flex-col md:flex-row items-center gap-3">
                                <img src="./assets/icons/cart/close.svg" alt="Burger" />
                                <img src="./assets/icons/cart/pr.png" alt="Burger" />
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
                                    <p class="counter">3</p>
                                    <button class="text-[#D5D5D5] text-xl rounded decrement">
                                        -
                                    </button>
                                </div>

                                <img
                                        src="./assets/icons/cart/Edit.svg"
                                        alt="edit"
                                        class="size-6 object-cover"
                                />
                            </div>
                        </div>
                        <div
                                class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1]"
                        >
                            <div class="flex flex-col md:flex-row items-center gap-3">
                                <img src="./assets/icons/cart/close.svg" alt="Burger" />
                                <img src="./assets/icons/cart/pr.png" alt="Burger" />
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
                                    <p class="counter">3</p>
                                    <button class="text-[#D5D5D5] text-xl rounded decrement">
                                        -
                                    </button>
                                </div>

                                <img
                                        src="./assets/icons/cart/Edit.svg"
                                        alt="edit"
                                        class="size-6 object-cover"
                                />
                            </div>
                        </div>
                        <div
                                class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1]"
                        >
                            <div class="flex flex-col md:flex-row items-center gap-3">
                                <img src="./assets/icons/cart/close.svg" alt="Burger" />
                                <img src="./assets/icons/cart/pr.png" alt="Burger" />
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
                                    <p class="counter">3</p>
                                    <button class="text-[#D5D5D5] text-xl rounded decrement">
                                        -
                                    </button>
                                </div>

                                <img
                                        src="./assets/icons/cart/Edit.svg"
                                        alt="edit"
                                        class="size-6 object-cover"
                                />
                            </div>
                        </div>
                        <div
                                class="flex justify-between flex-col gap-3 md:flex-row p-3 rounded-lg border-b border-dashed border-b-[#D1D1D1]"
                        >
                            <div class="flex flex-col md:flex-row items-center gap-3">
                                <img src="./assets/icons/cart/close.svg" alt="Burger" />
                                <img src="./assets/icons/cart/pr.png" alt="Burger" />
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
                                    <p class="counter">3</p>
                                    <button class="text-[#D5D5D5] text-xl rounded decrement">
                                        -
                                    </button>
                                </div>

                                <img
                                        src="./assets/icons/cart/Edit.svg"
                                        alt="edit"
                                        class="size-6 object-cover"
                                />
                            </div>
                        </div>
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
                                        src="./assets/icons/cart/pay.png"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6"
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
                                        src="./assets/icons/cart/pay.png"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6"
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
                                        src="./assets/icons/cart/pay.png"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6"
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
                                        src="./assets/icons/cart/pay.png"
                                        alt=""
                                        class="w-full object-cover max-w-[47px] h-6"
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
                                    <img src="./assets/icons/cart/Ticket.svg" alt="ticket" />
                                    Voucher
                                </div>
                                <div>
                                    <img src="./assets/icons/cart/Arrow - Left.svg" alt="" />
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
            <div class="flex flex-col gap-10">
                <div class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
                    <div class="flex items-center justify-between">
                        <h2 class="capitalize text-3xl md:text-4xl font-medium">
                            Other offers
                        </h2>
                        <a href="#" class="text-base text-[#74CA45]">See all</a>
                    </div>
                </div>
                <div
                        class="swiper other-slider px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
                >
                    <div class="swiper-wrapper">
                        <!-- Slides will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('theme::front-end.modals.location')
    @include('theme::front-end.modals.voucher')
@endsection
@section('script')
    <script src="{{ url('assets/js/cart.js') }}"></script>
    <script src="{{ url('assets/js/handle-voucher-cart-page.js') }}"></script>

@endsection