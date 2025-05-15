@if(isset($order) && $order->progress_status === 'cancelled')
    <div class="flex flex-col items-center text-center space-y-2 p-4">
        <img src="{{ url('images/order_cancelled.png') }}" alt="Cancelled" class="w-16 h-16 mb-2">

        <h2 class="text-red-500 font-bold text-xl">Order Cancelled</h2>
        <p class="text-gray-600">Sorry! The driver has cancelled your order.</p>
        <p class="text-gray-600 mb-4">Please try placing a new order.</p>

        <a href="{{ url('') }}" class="w-full max-w-sm py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-full focus:outline-none">
            Go Back
        </a>
    </div>
@endif
