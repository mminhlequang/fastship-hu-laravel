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
                <input type="text" id="voucher-input" placeholder="Enter promo code" class="codeVoucher w-[320px] focus:outline-none bg-[#F9F8F6]" />
            </div>
            <!-- Button -->
            <button class="btnApplyVoucher bg-secondary hover:bg-secondary-700 transition-all w-[141px] h-10 flex items-center justify-center gap-2 text-white px-3 py-1 rounded-xl" >
                Apply
            </button>
        </div>

        <div class="p-3 rounded-xl border border-dashed border-[#CEC6C5]">
            <div id="voucher-list" class="voucher-list">
                @forelse($vouchers as $itemV)

                    @php
                        $isDisabled = $itemV->is_valid == 0;
                        $disabledClass = $isDisabled ? 'opacity-60 pointer-events-none cursor-not-allowed' : 'cursor-pointer hover:bg-[#F9F8F6]';
                    @endphp

                    <div data-code="{{ $itemV->code }}"
                         data-id="{{ $itemV->id }}"
                         class="voucher-item flex items-center justify-between border-b rounded-lg p-2 {{ $disabledClass }}">
                        <div class="flex flex-col items-start lg:flex-row lg:items-center gap-3">
                            <div class="flex items-center gap-2">
                                <img data-src="{{ url('assets/icons/cart/pr2.png') }}"
                                     alt="Voucher Image"
                                     id="voucher-image-{{ $itemV->id }}"
                                     class="lazyload">
                                <div class="flex flex-col">
                    <span class="text-base lg:text-xl text-[#120F0F]">
                        {{ $itemV->name }}
                        <strong class="text-[#F17228]">{{ number_format($itemV->value) }} € off</strong>
                    </span>
                                    <span class="text-sm text-[#7D7575]">{{ $itemV->description }}</span>
                                </div>
                            </div>
                            <img id="voucher-icon-{{ $itemV->id }}"
                                 data-src="{{ url('assets/icons/cart/1.svg') }}"
                                 alt="Voucher Icon"
                                 class="w-9 h-9 lazyload voucher-icon">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const voucherInput = document.getElementById('voucher-input');
        const voucherItems = document.querySelectorAll('.voucher-item');
        let selectedVoucherId = null;
        voucherItems.forEach(item => {
            item.addEventListener('click', function () {
                const voucherCode = this.getAttribute('data-code');
                const voucherId = this.getAttribute('data-id');
                voucherInput.value = voucherCode;
                document.querySelector('.codeVoucher').value = voucherCode;

                document.querySelectorAll('.voucher-icon').forEach(icon => {
                    icon.src = "{{ url('assets/icons/cart/1.svg') }}";
                });

                const selectedIcon = document.getElementById(`voucher-icon-${voucherId}`);
                if (selectedIcon) {
                    selectedIcon.src = "{{ url('assets/icons/cart/check_voucher.svg') }}";
                }
                selectedVoucherId = voucherId;
            });
        });
    });
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('btnApplyVoucher')) {
                const parent = e.target.closest('.modalOverlayVoucher') || document;
                const voucherInput = parent.querySelector('.codeVoucher');

                if (!voucherInput) return;
                const voucherCode = voucherInput.value.trim();
                if (!voucherCode) return toastr.error('Please enter voucher code');

                const storeId = '{{ $storeId ?? 0 }}';

                fetch('ajaxFE/checkVoucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code: voucherCode, store_id: storeId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            toastr.success(data.message);
                            document.querySelectorAll('.codeVoucher').forEach(input => {
                                input.value = '';
                            });
                            document.querySelectorAll('.modalOverlayVoucher').forEach(modal => {
                                modal.classList.add('hidden');
                            });
                            document.querySelectorAll('.voucher-icon').forEach(icon => {
                                icon.src = "{{ url('assets/icons/cart/1.svg') }}";
                            });
                            document.getElementById('inputVoucherId').value = data.voucher;
                            document.getElementById('inputVoucherValue').value = data.value;

                            const tip = document.getElementById('inputTip').value;
                            const lat = document.getElementById('inputLat').value;
                            const lng = document.getElementById('inputLng').value;
                            const type = document.getElementById('inputPaymentType').value;

                            previewCalculator(storeId, tip, lat, lng, type, data.value);
                        } else {
                            voucherInput.value = '';
                            toastr.error(data.message || 'Voucher not valid');
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi kiểm tra voucher:', error);
                        voucherInput.value = '';
                        toastr.error(error.message || 'Error occurred while checking voucher');
                    });
            }
        });
    });


</script>

