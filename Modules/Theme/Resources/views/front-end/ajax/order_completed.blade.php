@if(isset($order))
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
    <div class="driver-panel flex flex-col items-center text-center space-y-2 p-4">
        <img src="{{ url('images/order_success.png') }}" alt="Success" class="w-16 h-16 mb-2">

        <h2 class="text-orange-500 font-bold text-xl">Driver Has Arrived!</h2>
        <p class="text-gray-600">Enjoy your meal!</p>
        <p class="text-gray-600 mb-4">See you in the next order :)</p>

        <button id="doneBtn"
                class="w-full max-w-sm py-2 bg-[#74CA45] hover:bg-primary-700 text-white font-medium rounded-full focus:outline-none">
            Done
        </button>
    </div>
@endif