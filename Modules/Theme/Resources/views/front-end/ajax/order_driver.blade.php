<style>
    .driver-panel {
        position: absolute;
        top: 42%;
        right: 0;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }
</style>
@if(isset($order) && $order->driver_id != null)
    <div class="driver-panel p-4" >
        <div class="text-gray-500 text-sm">Driver Information</div>

        <div class="flex items-center mt-2">
            <div class="w-12 h-12 rounded-full mr-3 overflow-hidden avatar-pulse">
                <img src="{{ url('images/driver.png') }}" alt="Driver" class="w-full h-full object-cover">
            </div>
            <div>
                <div class="font-bold">{{ optional($order->driver)->name }}</div>
                <div class="text-orange-500 text-sm">{{ optional($order->driver)->phone ?? '+1 999 555 1101' }}</div>

                <div class="flex mt-1">
                    <div class="text-yellow-500">
                        {{ optional($order->driver)->averageRating() }}&nbsp;★★★★★
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-4 text-center">
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 005 10a6 6 0 0012 0c0-.352-.035-.696-.1-1.028A5.001 5.001 0 0010 11z"
                              clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text-xs mt-1 text-green-600">Profile</div>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                              d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                              clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text-xs mt-1 text-green-600">Orders</div>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                              clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text-xs mt-1 text-green-600">Track</div>
            </div>
        </div>

        <div class="mt-4 border-t pt-4">
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-gray-500">Member Since:</div>
                <div>{{ \Carbon\Carbon::parse(optional($order->driver)->birthday)->format('d/m/Y') ?? 'July 15, 2019' }}</div>
                <div class="text-gray-500">Motorcycle model:</div>
                <div>Yamaha YBR King</div>
                <div class="text-gray-500">Plate Number:</div>
                <div>MJM 6748 AE</div>
            </div>
        </div>

        <div class="flex mt-6 space-x-2">
            <button class="bg-red-500 text-white flex-1 py-2 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <button class="bg-green-500 text-white flex-1 py-2 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </button>
            <button id="call-button"
                    class="bg-green-500 text-white flex-1 py-2 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </button>
        </div>
    </div>
@endif