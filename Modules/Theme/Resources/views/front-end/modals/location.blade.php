<!-- modal location -->
<div
    id="modal-container"
    class="fixed inset-0 z-[9999] items-center justify-center hidden"
>
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div
        class="bg-white rounded-2xl shadow-lg w-[90%] mx-auto lg:w-[648px] p-6 relative"
    >
        <!-- Modal Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl lg:text-2xl">Change Location</h2>
            <button
                class="text-gray-500 hover:text-gray-700 close-modal-location"
            >
                <img src="./assets/icons/cart/close.svg" alt="Close" />
            </button>
        </div>
        <!-- Address Input -->
        <div
            class="mt-4 flex items-end flex-col lg:flex-row lg:items-start gap-3"
        >
            <div
                class="flex items-center gap-2 h-12 rounded-xl px-4 bg-[#F9F8F6] w-full"
            >
                <img src="./assets/icons/cart/addr.svg" alt="addr" />
                <input
                    type="text"
                    id="location-input"
                    placeholder="3831 Cedar Lane, Somerville, MA 02143"
                    class="w-[420px] focus:outline-none bg-[#F9F8F6]"
                />
            </div>
            <button
                class="bg-[#F17228] hover:bg-[#F17228]/80 transition-colors duration-300 ease-in-out w-[141px] h-10 lg:h-12 flex items-center justify-center gap-2 text-white px-3 py-1 rounded-xl"
            >
                <p>+</p>
                Add new
            </button>
        </div>
        <!-- Tabs -->
        <div class="mt-4 flex border-b">
            <button
                class="tab-btn flex-1 py-2 text-center border-b-2 text-[#120F0F]"
                data-tab="recent"
            >
                Recently used
            </button>
            <button
                class="tab-btn flex-1 py-2 text-center text-gray-500 border-b-2 border-transparent"
                data-tab="propose"
            >
                Propose
            </button>
            <button
                class="tab-btn flex-1 py-2 text-center text-gray-500 border-b-2 border-transparent"
                data-tab="saved"
            >
                Saved
            </button>
        </div>
        <!-- Tab Content -->
        <div class="mt-4 p-3 rounded-xl border border-dashed border-[#CEC6C5]">
            <div class="tab-content" id="recent">
                <div class="space-y-3" id="recent-list"></div>
            </div>
            <div class="tab-content hidden" id="propose">
                <div class="space-y-3" id="propose-list"></div>
            </div>
            <div class="tab-content hidden" id="saved">
                <div class="" id="saved-list"></div>
            </div>
        </div>
    </div>
</div>