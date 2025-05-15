@if(isset($order) && $order->progress_status === 'completed')
    <div class="flex flex-col items-center text-center space-y-2 p-4">
        <img src="{{ url('images/order_success.png') }}" alt="Success" class="w-16 h-16 mb-2">

        <h2 class="text-orange-500 font-bold text-xl">Driver Has Arrived!</h2>
        <p class="text-gray-600">Enjoy your meal!</p>
        <p class="text-gray-600 mb-4">See you in the next order :)</p>

        <a href="{{ url('') }}" class="w-full max-w-sm py-2 bg-[#74CA45] hover:bg-green-600 text-white font-medium rounded-full focus:outline-none">
            Done
        </a>
    </div>
@endif
