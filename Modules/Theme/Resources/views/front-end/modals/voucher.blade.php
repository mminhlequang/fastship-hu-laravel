<!-- Voucher Modal -->
<div class="bg-black bg-opacity-50 fixed inset-0 hidden flex justify-center items-center min-h-screen modalOverlay modalOverlayVoucher z-10">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-medium">Vouchers</h2>
            <button onclick="toggleModal('modalOverlayVoucher');" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="my-6 flex items-center gap-3">
            <!-- Input -->
            <div class="flex items-center gap-2 h-10 lg:h-12 rounded-xl px-4 bg-[#F9F8F6]">
                <input type="text" id="voucher-input" placeholder="Enter promo code" class="w-[320px] focus:outline-none bg-[#F9F8F6]" />
            </div>
            <!-- Button -->
            <button class="bg-secondary hover:bg-secondary-700 transition-all w-[141px] h-10 flex items-center justify-center gap-2 text-white px-3 py-1 rounded-xl" onclick="applyVoucher()">
                Apply
            </button>
        </div>

        <div class="p-3 rounded-xl border border-dashed border-[#CEC6C5]">
            <div id="voucher-list" class="voucher-list">
                @forelse($vouchers as $itemV)
                    <div class="voucher-item flex items-center justify-between border-b rounded-lg p-2">
                        <div class="flex flex-col items-start lg:flex-row lg:items-center gap-3">
                            <div class="flex items-center gap-2">
                                <img data-src="{{ url('assets/icons/cart/pr2.png') }}"
                                     alt="Voucher Image"
                                     id="voucher-image-1" class="lazyload">
                                <div class="flex flex-col">
                                                    <span class="text-base lg:text-xl text-[#120F0F]">
                                                       {{ $itemV->name }}
                                                        <strong class="text-[#F17228]">{{ number_format($itemV->value) }} € off</strong>
                                                    </span>
                                    <span class="text-sm text-[#7D7575]">{{ $itemV->description }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>

                @empty
                    <img data-src="{{ url('images/no-data.webp') }}" class="lazyload">
                @endforelse
            </div>
        </div>
    </div>
</div>
