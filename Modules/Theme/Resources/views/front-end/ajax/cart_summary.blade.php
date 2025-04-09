<h6
        class="text-[#363E57] mb-3 text-base lg:text-lg tracking-[1%]"
>
    Orders ({{ count($carts) }} dishes)
</h6>
<div class="flex flex-col gap-2 py-4 border-b border-b-[#CEC6C5] border-t border-t-[#CEC6C5]"
>
    <div class="flex text-sm lg:text-base text-[#847D79] justify-between mt-2">
        <span>Subtotal</span>
        <span class="text-[#091230] font-medium text-sm lg:text-base">{{ isset($subtotal) ? number_format($subtotal, 2) : 0.00 }} €</span>
    </div>
    <div class="flex text-sm lg:text-base text-[#847D79] justify-between mt-2">
        <span>Application Fee</span>
        <span class="text-[#091230] font-medium text-sm lg:text-base">{{ isset($applicationFee) ? number_format($applicationFee, 2) : 0.00 }} €</span>
    </div>
    @if(isset($tip) && $tip > 0)
    <div class="flex text-sm lg:text-base text-[#847D79] justify-between mt-2">
        <span>Tip</span>
        <span class="text-[#091230] font-medium text-sm lg:text-base">{{ isset($tip) ? number_format($tip, 2) : 0.00 }} €</span>
    </div>
    @endif
    <div class="flex text-sm lg:text-base text-[#847D79] justify-between">
        <span>Discount</span>
        <span class="text-[#F17228] font-medium text-sm lg:text-base">-{{ isset($discount) ? number_format($discount, 2) : 0.00 }} €</span>
    </div>
    <div class="flex text-sm lg:text-base text-[#847D79] justify-between">
        <span>Shipping Fee</span>
        <span class="text-[#091230] font-medium text-sm lg:text-base">{{ isset($shipFee) ? number_format($shipFee, 2) : 0.00 }} €</span>
    </div>
</div>
<div
        class="flex justify-between text-base lg:text-lg text-[#120F0F] font-medium mt-4 mb-6"
>
    <span>Total</span>
    <span>{{ isset($total) ? number_format($total, 2) : 0.00 }} €</span>
</div>
<button class="bg-[#74CA45] text-white w-full rounded-[120px] py-3 px-4 hover:bg-[#74CA45]/80 transition duration-300 ease-in-out">
    Check Out
</button>