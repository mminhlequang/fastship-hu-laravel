<style>
    .cart-panel {
        position: absolute;
        top: calc(100%);
        right: -100px;
        width: 380px;
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        z-index: 100;
        overflow: hidden;
    }

    @media (max-width: 800px) {
        .cart-panel {
            position: fixed; /* Fix the panel to the screen */
            top: 0; /* Adjust to appear just below the notification icon */
            left: 50%; /* Center horizontally */
            transform: translateX(-50%); /* Perfectly center it */
            width: 95%; /* You can adjust this to a percentage or any fixed value */
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 100;
            overflow: hidden;
        }

    }

    .cart-panel::before {
        content: "";
        position: absolute;
        top: -8px;
        right: 162px;
        width: 16px;
        height: 16px;
        background-color: white;
        transform: rotate(45deg);
        border-radius: 2px;
    }
</style>
<div id="cart-dropdown" class="cart-panel hidden">
    <div class="flex items-center justify-between p-4 border-b">
        <div class="flex items-center">
            <h3 class="font-medium">{{ __('My cart') }}</h3>
        </div>
        <button id="close-cart" class="text-gray-500" >
            <img src="{{ url('assets/icons/cart/close.svg') }}">
        </button>
    </div>
    <div id="sectionCartDropdown" class="px-4 mb-4">
        @include('theme::front-end.ajax.cart_dropdown')
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cartIcon = document.getElementById("cart-icon");
        const cartDropdown = document.getElementById("cart-dropdown");
        const favoriteDropdown = document.getElementById("favorite-dropdown");
        const notificationDropdown = document.getElementById("notification-dropdown");
        const closeCart = document.getElementById("close-cart");

        cartIcon.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            cartDropdown.classList.toggle("hidden");
            favoriteDropdown.classList.add("hidden");
            notificationDropdown.classList.add("hidden");
        });

        closeCart.addEventListener("click", function (e) {
            e.stopPropagation();
            cartDropdown.classList.add("hidden");
        });

        document.addEventListener("mousedown", function (e) {
            if (!cartDropdown.contains(e.target) && !cartIcon.contains(e.target)) {
                cartDropdown.classList.add("hidden");
            }
        });

        cartDropdown.addEventListener("click", function (e) {
            e.stopPropagation();
        });

        cartDropdown.addEventListener("click", function (e) {
            const incrementBtn = e.target.closest('.increment-drop');
            const decrementBtn = e.target.closest('.decrement-drop');

            if (incrementBtn) {
                const parentDiv = incrementBtn.closest('.divAction');
                const counter = parentDiv?.querySelector('.counter');
                if (counter) {
                    const currentValue = parseInt(counter.textContent);
                    const newQuantity = currentValue + 1;
                    const cartId = incrementBtn.getAttribute("data-id");
                    updateCartQuantity(cartId, newQuantity);
                }
            }

            if (decrementBtn) {
                const parentDiv = decrementBtn.closest('.divAction');
                const counter = parentDiv?.querySelector('.counter');
                if (counter) {
                    const currentValue = parseInt(counter.textContent);
                    if (currentValue < 1) return;
                    const newQuantity = currentValue - 1;
                    const cartId = decrementBtn.getAttribute("data-id");
                    updateCartQuantity(cartId, newQuantity);
                }
            }

            function updateCartQuantity(cart_id, quantity) {
                const url = new URL('{{ url('ajaxFE/updateCartDropdown') }}');
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
                            $('#cart-badge').text(data.data);
                            $('#sectionCart').html(data.view);
                            $('#sectionCartDropdown').html(data.view2);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

        });



    });


</script>
