@if(isset($order) && $order->process_status == 'completed')
    <style>
        .driver-panel {
            position: absolute;
            top: 70%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>
    <section class="driver-panel bg-[#FFFFFF] p-6 rounded-2xl max-w-[375px] w-full">
        <div class="flex justify-end items-center">
            <button class="p-[7px]">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.24219 0.757813L0.756906 9.24309" stroke="#7A838C" stroke-width="1.5"
                          stroke-linecap="round"></path>
                    <path d="M0.756836 0.757813L9.24212 9.24309" stroke="#7A838C" stroke-width="1.5"
                          stroke-linecap="round"></path>
                </svg>
            </button>
        </div>
        <div class="flex items-center justify-center mt-[28px]">
            <img src="{{ url('images/order_success.png') }}" alt="icon sad">
        </div>
        <h3 class="text-[32px] font-medium text-[#F17228] leading-[1.2] mt-6">Driver Has Arrived!</h3>
        <p class="text-[20px] leading-[1.4] text-[#847D79] mt-6">Enjoy your meal!</p>
        <p class="text-[20px] leading-[1.4] text-[#847D79]">See you in the next order :)</p>
        <button id="doneBtn" class="py-[13px] w-full bg-[#74CA45] rounded-[120px] text-lg text-[#FFFFFF] leading-[1.22222] mt-[75px] hover:bg-primary-700">
            Done
        </button>
    </section>
@endif