@if(isset($order))
    @if($order->delivery_type == 'ship')
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-2 lg:gap-y-0 items-center">
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'driverArrivedStore' ||  $order->process_status == 'completed' || $order->store_status == 'completed' || $order->process_status == 'driverArrivedDestination' || $order->process_status == 'storeAccepted') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/Paper.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->process_status == 'driverArrivedStore' ||  $order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverArrivedDestination' || $order->process_status == 'storeAccepted') ? 'text-primary-700' : 'text-[#847D79]' }}">I'm at store</span>
                </div>
                <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
            </div>
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'storeAccepted' || $order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverPicked'|| $order->process_status == 'driverArrivedDestination') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/Bag.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->process_status == 'storeAccepted' || $order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverPicked'|| $order->process_status == 'driverArrivedDestination') ? 'text-primary-700' : 'text-[#847D79]' }}">Picked</span>
                </div>
                <div class="w-11 border-t-2 border-dashed border-gray-400 hidden lg:block"></div>
            </div>
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverArrivedDestination') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/deliver.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->store_status == 'completed'|| $order->process_status == 'driverArrivedDestination') ? 'text-primary-700' : 'text-[#847D79]' }}">I'm at destination</span>
                </div>
                <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
            </div>
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'completed') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/box.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->process_status == 'completed') ? 'text-primary-700' : 'text-[#847D79]' }}">Completed</span>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-2 lg:gap-y-0 items-center">
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'driverArrivedStore' ||  $order->process_status == 'completed' || $order->store_status == 'completed' || $order->process_status == 'driverArrivedDestination') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/Paper.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->process_status == 'driverArrivedStore' ||  $order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverArrivedDestination') ? 'text-primary-700' : 'text-[#847D79]' }}">Confirm</span>
                </div>
                <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
            </div>
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'storeAccepted' || $order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverPicked'|| $order->process_status == 'driverArrivedDestination') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/Bag.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->process_status == 'storeAccepted' || $order->process_status == 'completed' || $order->store_status == 'completed'|| $order->process_status == 'driverPicked'|| $order->process_status == 'driverArrivedDestination') ? 'text-primary-700' : 'text-[#847D79]' }}">Pickup ready</span>
                </div>
                <div class="w-11 border-t-2 border-dashed border-gray-400 hidden lg:block"></div>
            </div>
            <div class="flex items-center">
                <div class="flex w-full flex-col border {{ ($order->process_status == 'completed') ? 'border-primary-700' : 'border-[#F1EFE9]' }} items-center gap-2 px-1 py-2 rounded-xl">
                    <img
                            data-src="{{ url('assets/icons/cart/box.svg') }}" class="lazyload"
                    ><span
                            class="text-sm lg:text-base {{ ($order->process_status == 'completed') ? 'text-primary-700' : 'text-[#847D79]' }}">Completed</span>
                </div>
            </div>
        </div>
    @endif
@endif
