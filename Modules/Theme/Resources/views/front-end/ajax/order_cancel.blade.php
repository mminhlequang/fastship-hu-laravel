<style>
    .driver-panel {
        position: absolute;
        top: 60%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 400px;
        z-index: 10;
    }
</style>
@if(isset($order) && $order->process_status === 'cancelled')
    <div class="driver-panel flex flex-col items-center text-center space-y-2 p-4">
        <img src="{{ url('images/order_cancelled.png') }}" alt="Cancelled" class="w-16 h-16 mb-2">

        <h2 class="text-red-500 font-bold text-xl">Order Cancelled</h2>
        <p class="text-gray-600">Sorry! The driver has cancelled your order.</p>
        <p class="text-gray-600 mb-4">Please try placing a new order.</p>

        <button id="doneBtn" class="w-full max-w-sm py-2 bg-[#74CA45] hover:bg-primary-700 text-white font-medium rounded-full focus:outline-none">
            Go Back
        </button>
    </div>
@endif
