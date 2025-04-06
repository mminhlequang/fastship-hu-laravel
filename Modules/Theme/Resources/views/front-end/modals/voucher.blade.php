<!-- modal voucher -->
<div
        id="modal-voucher"
        class="fixed inset-0 z-[9999] hidden justify-end items-start"
>
    <div
            id="overlay-voucher"
            class="fixed inset-0 bg-black bg-opacity-50"
    ></div>
    <div
            class="bg-white rounded-2xl shadow-lg w-[90%] lg:w-[520px] right-[20px] top-[50px] lg:top-[100px] p-6 relative"
    >
        <!-- Modal Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl lg:text-2xl">Vouchers</h2>
            <button
                    class="text-gray-500 hover:text-gray-700 close-modal-location"
                    onclick="closeModal()"
            >
                <img
                        src="./assets/icons/cart/close.svg"
                        alt="Close"
                        class="close-voucher"
                />
            </button>
        </div>
        <!-- Address Input -->
        <div
                class="mt-4 flex items-end flex-col lg:flex-row lg:items-start gap-3"
        >
            <div
                    class="flex items-center gap-2 h-10 lg:h-12 rounded-xl px-4 bg-[#F9F8F6] w-full"
            >
                <input
                        type="text"
                        id="voucher-input"
                        placeholder="Enter promo code"
                        class="w-[320px] focus:outline-none bg-[#F9F8F6] hidden"
                />
            </div>
            <button
                    class="bg-[#F17228] hover:bg-[#F17228]/80 transition-colors duration-300 ease-in-out w-[141px] h-10 lg:h-12 flex items-center justify-center gap-2 text-white px-3 py-1 rounded-xl"
                    onclick="applyVoucher()"
            >
                Apply
            </button>
        </div>
        <!-- Tab Content -->
        <div class="mt-4 p-3 rounded-xl border border-dashed border-[#CEC6C5]">
            <div id="voucher-list" class="voucher-list">
                <!-- Voucher items will be rendered here by JavaScript -->
            </div>
        </div>
    </div>
</div>