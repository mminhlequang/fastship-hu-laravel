<button
        id="openModalBtn"
        class="bg-primary text-white px-4 py-2 rounded-lg"
>
    Show Order Details
</button>
<!-- Modal Background Overlay -->
<div
        id="modalOverlay"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center"
>
    <!-- Modal Content -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-medium">Order details</h3>
            <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                >
                    <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                    />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 overflow-y-auto max-h-[80vh]">
            <!-- Order Number -->
            <div class="flex flex-wrap items-center justify-between mb-4">
                <div class="text-gray-600">Order code:</div>
                <div class="flex items-center">
              <span class="text-secondary font-medium" id="referralCode"
              >GA107H-ANS-M92642</span
              >
                    <button
                            id="copyButton"
                            class="p-1 rounded hover:bg-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="text-gray-700"
                        >
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path
                                    d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Restaurant Info -->
            <div class="flex items-start gap-3 mb-4 rounded-2xl bg-gray-50 p-4">
                <div
                        class="rounded-full flex items-center justify-center flex-shrink-0"
                >
                    <img
                            src="https://upload.wikimedia.org/wikipedia/sco/thumb/b/bf/KFC_logo.svg/1024px-KFC_logo.svg.png"
                            alt="KFC Logo"
                            class="w-10 h-10"
                    />
                </div>
                <div class="flex-1">
                    <div class="font-medium">Kentucky Fried Chicken Patterson...</div>
                    <div class="text-gray-500 text-sm">
                        Delivered · Mar 3, 2024, 7:14 pm
                    </div>
                </div>
            </div>

            <!-- Addresses -->
            <div class="space-y-4 mb-4 border-2 border-dashed p-6 rounded-lg">
                <!-- Pickup Address -->
                <div class="flex items-center gap-3">
                    <div class="text-secondary mt-1">
                        <img
                                src="./assets/icons/icon_pin.svg"
                                alt="Pickup Truck"
                                class="w-4 h-4"
                        />
                    </div>
                    <div>
                        <p class="text-sm">1650 Nelson Street, Auburn, OR 97423</p>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="flex gap-3">
                    <div class="text-secondary mt-1">
                        <img
                                src="./assets/icons/map_top_bar_icon.svg"
                                alt="Pickup Truck"
                                class="w-4 h-4"
                        />
                    </div>
                    <div>
                        <p class="text-sm">7839 Dean Drive, Florence, AZ 85201</p>
                    </div>
                </div>
            </div>

            <!-- Driver Info -->
            <div
                    class="flex items-center gap-3 mb-4 border-2 border-dashed p-6 rounded-lg"
            >
                <img
                        src="https://upload.wikimedia.org/wikipedia/sco/thumb/b/bf/KFC_logo.svg/1024px-KFC_logo.svg.png"
                        alt="KFC Logo"
                        class="w-10 h-10"
                />
                <div class="flex-1">
                    <div class="text-sm font-medium">
                        Delivery driver: <span class="font-normal">Ricky Smith</span>
                    </div>
                    <div class="text-xs text-gray-500">Service number</div>
                </div>
                <div class="text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mb-4">
                <h4 class="font-medium mb-3">Order summary</h4>

                <div class="border-2 border-dashed p-6 rounded-lg">
                    <!-- Item 1 -->
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-md flex items-center">
                                <img src="./assets/icons/cart/pr.png" alt="Burger" />
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm">1x chicken burger and drink set</div>
                                <div>$8.95</div>
                            </div>
                        </div>
                        <span class="text-sm border-2 rounded-2xl bg-gray-200 p-2"
                        >3x</span
                        >
                    </div>

                    <!-- Item 2 -->
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-md flex items-center">
                                <img src="./assets/icons/cart/pr.png" alt="Burger" />
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm">1x chicken burger and drink set</div>
                                <div>$8.95</div>
                            </div>
                        </div>
                        <span class="text-sm border-2 rounded-2xl bg-gray-200 p-2"
                        >4x</span
                        >
                    </div>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div
                    class="space-y-2 text-sm mb-4 border-2 border-dashed p-6 rounded-lg"
            >
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>$ 26.90</span>
                </div>
                <div class="flex justify-between">
                    <span>Application fee</span>
                    <span>$ 2.00</span>
                </div>
                <div class="flex justify-between text-primary">
                    <span>15% off, new deals below</span>
                    <span>- $ 4.35</span>
                </div>
                <!-- Total -->
                <div class="flex justify-between font-medium">
                    <span>Total</span>
                    <span>$ 24.60</span>
                </div>
            </div>

            <!-- Payment Method -->
            <div
                    class="flex justify-between items-center mt-4 text-sm text-gray-600 border-2 border-dashed px-6 py-2 rounded-lg"
            >
                <span>PayPal</span>
                <span>Personal</span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="border-t flex flex-wrap justify-between">
            <button
                    class="w-full flex-1 border border-secondary rounded-full px-6 py-2 text-secondary"
            >
                Review
            </button>
            &nbsp;
            <button
                    class="w-full flex-1 bg-primary transition-all hover:bg-primary-700 text-white rounded-full px-6 py-2"
            >
                Buy back
            </button>
        </div>
    </div>
</div>

<script>
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const modalOverlay = document.getElementById("modalOverlay");

    openModalBtn.addEventListener("click", () => {
        modalOverlay.classList.remove("hidden");
    });

    closeModalBtn.addEventListener("click", () => {
        modalOverlay.classList.add("hidden");
    });

    modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.add("hidden");
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const copyButton = document.getElementById("copyButton");
        const referralCode = document.getElementById("referralCode");
        copyButton.addEventListener("click", async function () {
            try {
                await navigator.clipboard.writeText(referralCode.textContent);

                const originalSVG = copyButton.innerHTML;
                copyButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    `;

                const parentDiv = referralCode.closest(".bg-gray-50");
                parentDiv.classList.add("copy-animation");

                setTimeout(() => {
                    copyButton.innerHTML = originalSVG;
                    parentDiv.classList.remove("copy-animation");
                }, 1500);
            } catch (err) {
                console.error("Failed to copy text: ", err);

                const originalSVG = copyButton.innerHTML;
                copyButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    `;

                setTimeout(() => {
                    copyButton.innerHTML = originalSVG;
                }, 1500);
            }
        });

        copyButton.addEventListener("keydown", function (e) {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                copyButton.click();
            }
        });

        copyButton.setAttribute("tabindex", "0");
        copyButton.setAttribute("aria-label", "Copy referral code");
    });
</script>