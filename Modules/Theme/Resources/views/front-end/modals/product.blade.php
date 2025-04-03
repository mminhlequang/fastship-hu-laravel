<!-- Modal Overlay -->
<div
        id="modalOverlayProduct"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 modalOverlay modalOverlayProduct"
>
    <!-- Modal Container -->
    <div
            class="modal-container bg-white max-w-lg rounded-xl shadow-xl w-full relative"
    >
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
                    Pork cutlet burger and drink set
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
                            onerror="this.onerror=null; this.src='https://zennail23.com/images/no-image.png'"
                            data-src="https://zennail23.com/storage/images/products/2025-03-30-23-41-27-image-picker-5554658d-bfb5-4475-8a78-daf176ca89c0-11569-0000027529e4cb33.jpg"
                            class="aspect-square rounded-2xl object-cover w-full h-[300px] lazyloaded "
                            src="https://zennail23.com/storage/images/products/2025-03-30-23-41-27-image-picker-5554658d-bfb5-4475-8a78-daf176ca89c0-11569-0000027529e4cb33.jpg"
                    />
                    <div
                            class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10"
                    >
                <span
                        class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto md:px-3 md:py-1.5 flex items-center text-sm gap-1"
                ><img
                            data-src="https://zennail23.com/assets/icons/ticket_star_icon.svg"
                            class="w-6 h-6 lazyloaded"
                            src="https://zennail23.com/assets/icons/ticket_star_icon.svg"
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
                    <span class="text-muted line-through">$17.50</span
                    ><span class="text-secondary">$12.50</span>
                            </div>

                        </div>
                        <p class="text-sm text-gray-500">
                            We cannot respond to requests such as increase / decrease or non-use of ingredients..show
                            more
                        </p>
                    </div>
                </div>

            </div>

            <!-- Option Group 1 -->
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    Please select the side menu
                </p>
                <div class="space-y-2">
                    <label
                            class="flex items-center justify-between p-2 border rounded-md bg-green-50 border-primary"
                    >
                        <div>
                            <input
                                    type="radio"
                                    name="sideMenu"
                                    value="frenchFriesMedium"
                                    class="hidden sideMenu-radio"
                                    checked
                            />
                            <span class="text-sm">French fries medium</span>
                        </div>
                        <span class="text-sm text-gray-500">+$0</span>
                    </label>
                    <label
                            class="flex items-center justify-between p-2 border rounded-md"
                    >
                        <div>
                            <input
                                    type="radio"
                                    name="sideMenu"
                                    value="chipotleFries"
                                    class="hidden sideMenu-radio"
                            />
                            <span class="text-sm"
                            >Chipotle (French fries and fried onions)</span
                            >
                        </div>
                        <span class="text-sm text-gray-500">+$1</span>
                    </label>
                </div>
            </div>

            <!-- Option Group 2 -->
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    Please choose favorite drink
                </p>
                <div class="space-y-2">
                    <label
                            class="flex items-center justify-between p-2 border rounded-md bg-green-50 border-primary"
                    >
                        <div>
                            <input
                                    type="radio"
                                    name="favoriteDrink"
                                    value="frenchFriesMedium"
                                    class="hidden favoriteDrink-radio"
                                    checked
                            />
                            <span class="text-sm">French fries medium</span>
                        </div>
                        <span class="text-sm text-gray-500">+$0</span>
                    </label>
                    <label
                            class="flex items-center justify-between p-2 border rounded-md"
                    >
                        <div>
                            <input
                                    type="radio"
                                    name="favoriteDrink"
                                    value="chipotleFries"
                                    class="hidden favoriteDrink-radio"
                            />
                            <span class="text-sm"
                            >Chipotle (French fries and fried onions)</span
                            >
                        </div>
                        <span class="text-sm text-gray-500">+$1</span>
                    </label>
                </div>
            </div>
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
                    id="addToOrderBtn"
                    class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-700"
            >
                Add to order • $4.20
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const decreaseBtn = document.getElementById("decreaseBtn");
        const increaseBtn = document.getElementById("increaseBtn");
        const quantityElement = document.getElementById("quantity");
        const addToOrderBtn = document.getElementById("addToOrderBtn");

        const basePrice = 2.2;
        let currentTotal = basePrice;
        let quantity = 1;

        const sideMenuRadios = document.querySelectorAll(".sideMenu-radio");
        const drinkRadios = document.querySelectorAll(".favoriteDrink-radio");

        decreaseBtn.addEventListener("click", function () {
            if (quantity > 1) {
                quantity--;
                updateQuantityAndPrice();
            }
        });

        increaseBtn.addEventListener("click", function () {
            quantity++;
            updateQuantityAndPrice();
        });

        sideMenuRadios.forEach((radio) => {
            radio.addEventListener("change", function () {
                document.querySelectorAll('[name="sideMenu"]').forEach((item) => {
                    const parent = item.closest("label");
                    if (item.checked) {
                        parent.classList.add("bg-green-50", "border-primary");
                    } else {
                        parent.classList.remove("bg-green-50", "border-primary");
                    }
                });
                updateTotalPrice();
            });

            const label = radio.closest("label");
            label.addEventListener("click", function () {
                radio.checked = true;

                const event = new Event("change");
                radio.dispatchEvent(event);
            });
        });

        drinkRadios.forEach((radio) => {
            radio.addEventListener("change", function () {
                document
                    .querySelectorAll('[name="favoriteDrink"]')
                    .forEach((item) => {
                        const parent = item.closest("label");
                        if (item.checked) {
                            parent.classList.add("bg-green-50", "border-primary");
                        } else {
                            parent.classList.remove("bg-green-50", "border-primary");
                        }
                    });
                updateTotalPrice();
            });

            const label = radio.closest("label");
            label.addEventListener("click", function () {
                radio.checked = true;

                const event = new Event("change");
                radio.dispatchEvent(event);
            });
        });

        addToOrderBtn.addEventListener("click", function () {
            const selectedSide = document.querySelector(
                'input[name="sideMenu"]:checked'
            ).value;
            const selectedDrink = document.querySelector(
                'input[name="favoriteDrink"]:checked'
            ).value;

            // Create order data
            const orderData = {
                product: "Pork cutlet burger and drink set",
                sideMenu: selectedSide,
                drink: selectedDrink,
                quantity: quantity,
                totalPrice: currentTotal * quantity,
            };

            console.log("Added to cart:", orderData);

            // Show confirmation and close modal
            alert(
                "Added to cart: " +
                orderData.quantity +
                " x Pork cutlet burger and drink set - $" +
                orderData.totalPrice.toFixed(2)
            );
            modalOverlayProduct.classList.add("hidden");
        });

        function updateQuantityAndPrice() {
            quantityElement.textContent = quantity;
            updateTotalPrice();
        }

        function updateTotalPrice() {
            let additionalPrice = 0;

            if (
                document.querySelector('input[name="sideMenu"]:checked').value ===
                "chipotleFries"
            ) {
                additionalPrice += 1;
            }

            if (
                document.querySelector('input[name="favoriteDrink"]:checked')
                    .value === "chipotleFries"
            ) {
                additionalPrice += 1;
            }

            currentTotal = basePrice + additionalPrice;
            const finalPrice = (currentTotal * quantity).toFixed(2);

            addToOrderBtn.textContent = `Add to order • $${finalPrice}`;
        }

        updateTotalPrice();
    });
</script>