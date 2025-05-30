@extends('theme::front-end.master')
@section('style')

@endsection

@section('content')
    <main class="bg-gray-50">
        <section class="py-2 px-4 bg-[#fcfcfc] lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <!-- Order Summary -->
            <div class="my-4 w-100">
                <div>
                    <h2 class="text-lg font-normals tracking-tighte-[1%] text-[#120F0F] leading-[120%] lg:text-xl">
                        Cart information
                    </h2>
                </div>
                <!-- list item cart -->
                <div id="sectionCart" class="py-2 bg-[#faf9f7] w-full rounded-2xl">
                    @include('theme::front-end.ajax.cart')
                </div>

            </div>
        </section>
    </main>
@endsection
@section('script')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            initCartActions();

            function initCartActions() {
                bindDeleteCart();
                bindQuantityChange();
            }

            function bindDeleteCart() {
                document.querySelectorAll(".deleteCart").forEach(button => {
                    button.addEventListener("click", function () {
                        const cartId = this.getAttribute("data-id");
                        $('.loading').addClass('loader');
                        const url = new URL('{{ url('ajaxFE/deleteCart') }}');
                        url.searchParams.append('id', cartId);

                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status) {
                                    toastr.success(data.message);
                                    $('#sectionCart').html(data.view);
                                }
                                initCartActions();
                                $('.loading').removeClass('loader');
                            })
                            .catch(() => $('.loading').removeClass('loader'));
                    });
                });
            }

            function bindQuantityChange() {
                document.querySelectorAll(".increment, .decrement").forEach(button => {
                    button.addEventListener("click", function () {
                        const parent = this.closest('.flex');
                        const counter = parent.querySelector('.counter');
                        const currentValue = parseInt(counter.textContent);
                        const cartId = this.getAttribute("data-id");
                        let newQuantity = this.classList.contains("increment") ? currentValue + 1 : currentValue - 1;
                        if (newQuantity < 1) return;
                        updateCartQuantity(cartId, newQuantity);
                    });
                });
            }

            function updateCartQuantity(cartId, quantity) {
                $('.loading').addClass('loader');
                const url = new URL('{{ url('ajaxFE/updateCart') }}');
                url.searchParams.append('id', cartId);
                url.searchParams.append('quantity', quantity);

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            toastr.success(data.message);
                            $('#cart-badge').text(data.data);
                            $('#sectionCart').html(data.view);
                            $('#sectionCartDropdown').html(data.view2);
                        }
                        initCartActions();
                        $('.loading').removeClass('loader');
                    })
                    .catch(() => $('.loading').removeClass('loader'));
            }
        });

    </script>

@endsection