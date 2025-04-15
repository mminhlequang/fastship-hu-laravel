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
                        <div class="grid grid-cols-1 gap-2 mt-3 md:grid-cols-2 md:gap-6">
                            <div data-type="ship"
                                 class="optionS flex flex-col w-full justify-between h-auto border border-gray-300 bg-white rounded-xl px-3 py-[10px] cursor-pointer transition-all"
                                 onclick="selectOptionShip(this)">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <img data-src="{{ url('assets/icons/cart/addr.svg') }}" alt="address icon"
                                                 class="lazyload w-5 h-5"/>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">Delivery location</p>
                                                <div id="textLocation" class="text-sm font-medium text-gray-900">
                                                    {{ $_COOKIE['address'] ?? '3831 Cedar Lane, MA 02143' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div id="openModalLocationBtn" class="text-gray-500 hover:text-gray-700">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.5 7L14.5 12L9.5 17" stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <img data-src="{{ url('assets/icons/pickup.svg') }}" alt="pickup icon"
                                             class="lazyload w-4 h-4 text-secondary"/>
                                        <span class="text-sm font-medium text-secondary"
                                              id="textEstimate">(0 min, 0 km)</span>
                                    </div>
                                </div>
                            </div>
                            @include('theme::front-end.modals.pick_location')
                            <div data-type="pickup"
                                 class="optionS flex items-center w-full justify-between h-11 border rounded-xl px-3 py-[10px] cursor-pointer
                                    border-[#74CA45] bg-green-100"
                                 onclick="selectOptionShip(this)">
                                <div class="flex items-center gap-2">
                                    <img data-src="{{ url('assets/icons/pickup.svg') }}" alt="addr" class="lazyload"/>
                                    <h5 class="text-sm text-[#847D79]">Pick up yourself</h5>
                                </div>
                            </div>

                        </div>


                        <!-- tip input  -->
                        <div>
                            <div class="flex flex-col items-start gap-2 mt-4 text-sm md:text-base">
                                <h6>Add courier tip</h6>
                                <p>100% of the tip goes to your courier</p>
                            </div>
                            <div class="grid grid-col-3 md:grid-cols-7 gap-2 mt-3">
                                @php $tipOptions = [0, 5, 10, 15, 20]; @endphp
                                @foreach ($tipOptions as $index => $tip)
                                    <div
                                            data-value="{{ $tip }}"
                                            class="option flex items-center w-full justify-between h-11 border rounded-xl px-3 py-[10px]  cursor-pointer
                                            {{ $index === 0 ? 'border-[#74CA45] bg-green-100' : 'border-gray-400 bg-white' }}"
                                            onclick="selectOption(this)">
                                        <p class="text-[#3C3836] font-medium">+{{ $tip }},00 €</p>
                                    </div>
                                @endforeach
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
                            <a href="{{ url('foods') }}" class="text-sm md:text-base text-[#74CA45]">
                                Add List
                            </a>
                        </div>
                    </div>
                    <!-- list item cart -->
                    <div id="sectionCart" class="mt-3 bg-[#faf9f7] w-full rounded-2xl">
                        @include('theme::front-end.ajax.check_out')
                    </div>
                    <!-- payment -->
                    <div>
                        <h4 class="text-[#14142A] mb-3 font-medium text-sm lg:text-base">
                            Choose another payment method
                        </h4>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="payment-option flex items-center justify-between py-3 px-4 rounded-2xl border border-[#74CA45] cursor-pointer"
                                 data-payment="5" data-method="pay_cash" onclick="selectPaymentMethod(this)">
                                <div class="flex items-center gap-2">
                                    <input
                                            type="radio"
                                            name="payment_id"
                                            class="accent-[#333333]"
                                            value="4"
                                            checked
                                    />
                                    <label class="text-[#333333] text-sm">Cash</label>
                                </div>
                                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                     data-src="{{ url('storage/images/news/cash.png') }}"
                                     alt=""
                                     class="w-full object-cover rounded-full lazyload"
                                     style="width: 35px; height: 35px;"
                                />
                            </div>
                            <div class="payment-option flex items-center justify-between py-3 px-4 rounded-2xl bg-[#F9F8F6] border cursor-pointer"
                                 data-payment="4" data-method="pay_stripe" onclick="selectPaymentMethod(this)">
                                <div class="flex items-center gap-2">
                                    <input
                                            type="radio"
                                            name="payment_id"
                                            value="5"
                                            class="accent-[#333333]"
                                    />
                                    <label class="text-[#333333] text-sm">Credit Card</label>
                                </div>
                                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                     data-src="{{ url('storage/images/news/stripe.jpg') }}"
                                     alt=""
                                     class="w-full object-cover rounded-full lazyload"
                                     style="width: 35px; height: 35px;"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- total -->
                <div>
                    <h3 class="text-lg mt-6 lg:text-xl text-[#120F0F]">Summary</h3>
                    <div id="sectionSummary" class="bg-[#F9F8F6] mt-4 rounded-[20px] h-fit p-4">
                        @include('theme::front-end.ajax.cart_summary')
                    </div>
                    <form method="POST" id="formCheckout">
                        @csrf
                        <input type="hidden" name="ship_distance" value="" id="inputShipDistance">
                        <input type="hidden" name="ship_estimate_time" value="" id="inputEstimateTime">
                        <input type="hidden" name="lat" value="" id="inputLat">
                        <input type="hidden" name="lng" value="" id="inputLng">
                        <input type="hidden" name="address" value="" id="inputAddress">
                        <input type="hidden" name="payment_id" value="5" id="inputPayment">
                        <input type="hidden" name="delivery_type" value="pickup" id="inputPaymentType">
                        <input type="hidden" name="payment_method" value="pay_cash" id="inputPaymentMethod">
                        <input type="hidden" name="price_tip" value="0" id="inputTip">
                        <input type="hidden" name="fee" value="0" id="inputFee">
                        <input type="hidden" name="voucher_id" id="inputVoucherId">
                        <input type="hidden" name="voucher_value" value="0" id="inputVoucherValue">
                        <input type="hidden" name="store_id" value="{{ $storeId }}">
                        <button class="bg-[#74CA45] text-white w-full rounded-[120px] py-3 px-4 hover:bg-[#74CA45]/80 transition duration-300 ease-in-out">
                            Check Out
                        </button>
                    </form>
                    <div class="py-4 px-3 rounded-2xl bg-[#F1EFE9] mt-2">
                        <div class="flex items-center justify-between cursor-pointer" onclick="toggleModal('modalOverlayVoucher');">
                            <div class="flex items-center gap-1 text-sm lg:text-base text-[#F17228]">
                                <img data-src="{{ url('assets/icons/cart/Ticket.svg') }}" alt="ticket" class="lazyload"/>Voucher
                            </div>
                            <div>
                                <img data-src="{{ url('assets/icons/cart/left.svg') }}" alt="" class="lazyload"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-[2fr,1fr] gap-2 mt-2">
                            <input type="text" placeholder="Enter promo code" class="text-[#847D79] text-sm px-3 lg:text-basse outline-none rounded-2xl"/>
                            <button class="p-3 rounded-2xl bg-[#F17228] text-white hover:bg-[#F17228]/80 transition duration-300 ease-in-out">
                                Apply
                            </button>
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
                                        <span class="w-9 h-9 flex rounded-full bg-black/30 favoriteIcon"
                                              data-id="{{ $itemP->id }}"><img
                                                    data-src="{{ url(($itemP->isFavoritedBy(auth()->guard('loyal_customer')->id()) ? 'assets/icons/heart_check.svg': 'assets/icons/heart_line_icon.svg')) }}"
                                                    class="m-auto lazyload"></span>
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
                                                <span class="text-muted line-through">{{ number_format($itemP->price ?? 0 + 5, 2) }}&nbsp;€</span>
                                                <span class="text-secondary">{{ number_format($itemP->price ?? 0, 2) }}&nbsp;€</span>
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
    @include('theme::front-end.modals.voucher')
@endsection
@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ url('assets/js/local-favorite-slider.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var stripe = Stripe('pk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd');
            $('#formCheckout').submit(function (e) {
                e.preventDefault();
                $('.loading').addClass('loader');
                $.ajax({
                    url: '{{ url('ajaxFE/submitOrder') }}',
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        const data = response;
                        if (data.status) {
                            $('.loading').removeClass('loader');
                            let payment = parseInt(data.payment) ?? 5;
                            if (payment !== 5) {
                                return stripe.redirectToCheckout({sessionId: data.session_id});
                            } else {
                                toastr.success(data.message);
                                window.location.href = '{{ url('find-store') }}';
                            }
                        } else {
                            toastr.error(data.message);
                            $('.loading').removeClass('loader');
                        }
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        function selectPaymentMethod(selected) {
            const options = document.querySelectorAll('.payment-option');
            options.forEach(option => {
                option.classList.remove("border-[#74CA45]", "bg-green-100");
                option.querySelector('input[type="radio"]').checked = false;
            });
            let value = selected.getAttribute("data-method");
            let paymentId = selected.getAttribute("data-payment");
            $('#inputPaymentMethod').val(value);
            $('#inputPayment').val(paymentId);
            selected.classList.add('border-[#74CA45]');
            selected.querySelector('input[type="radio"]').checked = true;
        }

        function selectOption(selected) {
            let value = selected.getAttribute("data-value");
            $('#inputTip').val(value);
            document.querySelectorAll(".option").forEach(option => option.classList.remove("border-[#74CA45]", "bg-green-100"));
            selected.classList.add("border-[#74CA45]", "bg-green-100");
            selected.classList.remove("border-gray-400");

            let storeId = '{{ $storeId ?? 0 }}';
            let lat = $('#inputLat').val();
            let lng = $('#inputLng').val();
            let type = $('#inputPaymentType').val();
            previewCalculator(storeId, value, lat, lng, type);

        }

        function previewCalculator(storeId, tip, lat, lng, type) {
            $('.loading').addClass('loader');
            const url = new URL('{{ url('ajaxFE/previewCalculate') }}');
            url.searchParams.append('store_id', storeId);
            url.searchParams.append('tip', tip ?? 0);
            url.searchParams.append('lat', lat);
            url.searchParams.append('lng', lng);
            url.searchParams.append('type', type);
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        $('#sectionSummary').html(data.view);
                        $('#textEstimate').html(data.text);
                        $('#inputShipDistance').val(data.distance);
                        $('#inputEstimateTime').val(data.time);
                        $('#inputFee').val(data.fee);
                        $('.loading').removeClass('loader');
                    }
                    $('.loading').removeClass('loader');
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('.loading').removeClass('loader');
                });
        }

        function selectOptionShip(selected) {
            document.querySelectorAll(".optionS").forEach(option => {
                option.classList.remove("border-[#74CA45]", "bg-green-100");
                option.classList.add("border-gray-300", "bg-white");
            });

            const value = selected.getAttribute("data-type");
            $('#inputPaymentType').val(value);

            selected.classList.add("border-[#74CA45]", "bg-green-100");
            selected.classList.remove("border-gray-300", "bg-white");

            let storeId = '<?php echo e($storeId ?? 0); ?>';
            let tip = $('#inputTip').val();
            let lat = $('#inputLat').val();
            let lng = $('#inputLng').val();
            let type = $('#inputPaymentType').val();
            previewCalculator(storeId, tip, lat, lng, type);
        }


    </script>
    <script type="text/javascript">

    </script>

@endsection