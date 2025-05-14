@if(isset($order))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-2 lg:gap-y-0 items-center">
        <div class="flex items-center">
            <div class="flex w-full flex-col border border-primary-700 items-center gap-2 px-1 py-2 rounded-xl">
                <img data-src="{{ url('assets/icons/cart/Paper.svg') }}" class="lazyload"/>
                <span class="text-sm lg:text-base text-primary-700">Confirming</span>
            </div>
            <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
        </div>
        <div class="flex items-center">
            <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                <img data-src="{{ url('assets/icons/cart/Bag.svg') }}" class="lazyload"/>

                <span class="text-sm lg:text-base text-[#847D79]">preparing food</span>
            </div>
            <div class="w-11 border-t-2 border-dashed border-gray-400 hidden lg:block"></div>
        </div>
        <div class="flex items-center">
            <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                <img data-src="{{ url('assets/icons/cart/deliver.svg') }}" class="lazyload"/>
                <span class="text-sm lg:text-base text-[#847D79]">In progress</span>
            </div>
            <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
        </div>
        <div class="flex items-center">
            <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                <img data-src="{{ url('assets/icons/cart/box.svg') }}" class="lazyload"/>
                <span class="text-sm lg:text-base text-[#847D79]">Delivered</span>
            </div>
        </div>
    </div>
@endif
