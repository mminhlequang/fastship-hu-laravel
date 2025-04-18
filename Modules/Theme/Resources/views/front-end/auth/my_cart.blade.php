@extends('theme::front-end.master')
@section('style')

@endsection

@section('content')
    <main class="bg-gray-50">
        <section class="py-2 px-4 bg-[#fcfcfc] lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <!-- Order Summary -->
            <div class="my-4 w-100 sm:w-80">
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
            updateQuantity();
            deleteCart();

            function deleteCart(){
                document.querySelectorAll(".deleteCart").forEach((button) => {
                    button.addEventListener("click", function () {
                        let cartId = this.getAttribute("data-id");
                        $('.loading').addClass('loader');
                        const url = new URL('{{ url('ajaxFE/deleteCart') }}');
                        url.searchParams.append('id', cartId);

                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status) {
                                    toastr.success(data.message);
                                    $('#sectionCart').html(data.view);
                                    deleteCart();
                                    updateQuantity();
                                } else {
                                    deleteCart();
                                    updateQuantity();
                                }
                                $('.loading').removeClass('loader');
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                $('.loading').removeClass('loader');
                            });
                    });
                });

            }

            function updateQuantity() {
                document.querySelectorAll(".increment").forEach((button) => {
                    button.addEventListener("click", function () {
                        let parentDiv = this.closest('.flex');
                        let counter = parentDiv.querySelector('.counter');

                        if (counter) {
                            let currentValue = parseInt(counter.textContent);
                            let newQuantity = currentValue + 1;
                            let cartId = this.getAttribute("data-id");

                            updateCartQuantity(cartId, newQuantity);
                        } else {
                            console.error('Counter element not found');
                        }
                    });
                });

                document.querySelectorAll(".decrement").forEach((button) => {
                    button.addEventListener("click", function () {
                        let parentDiv = this.closest('.flex');
                        let counter = parentDiv.querySelector('.counter');

                        if (counter) {
                            let currentValue = parseInt(counter.textContent);
                            if (currentValue === 1) return;

                            let newQuantity = currentValue - 1;
                            let cartId = this.getAttribute("data-id");

                            updateCartQuantity(cartId, newQuantity);
                        } else {
                            console.error('Counter element not found');
                        }
                    });
                });
            }

            function updateCartQuantity(cart_id, quantity, callback) {
                $('.loading').addClass('loader');
                const url = new URL('{{ url('ajaxFE/updateCart') }}');
                url.searchParams.append('id', cart_id);
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
                            $('#sectionCart').html(data.view);
                            updateQuantity();
                            deleteCart();
                        } else {
                            updateQuantity();
                            deleteCart();
                        }
                        $('.loading').removeClass('loader');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $('.loading').removeClass('loader');
                    });

            }
        });


    </script>

@endsection