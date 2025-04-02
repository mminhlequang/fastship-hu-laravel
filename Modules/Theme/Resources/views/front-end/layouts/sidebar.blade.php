<div class="w-full sm:w-1/4">
    <div class="bg-white rounded-lg shadow sm:mr-4 pt-6">
        <div class="avatar-container">
            <div class="avatar-inner">
                <div
                        class="w-32 h-32 bg-[#F1EFE9] rounded-full flex items-center justify-center"
                >
                    <div class="text-4xl font-bold text-primary">PS</div>
                </div>
                <button
                        class="absolute bottom-0 right-0 bg-white rounded-full p-1 border border-gray-300"
                >
                    <img data-src="{{ url('assets/icons/icon_camera.svg') }}" alt="Camera" class="fa-camera lazyload"/>
                </button>
            </div>
        </div>

        <div class="px-4 pb-6">
            <h2 class="text-xl font-medium text-center">User name</h2>
            <div class="flex items-center justify-center mt-2 text-gray-500">
                <img data-src="{{ url('assets/icons/icon_rank.svg') }}" alt="Point" class="w-4 h-4 mr-1 lazyload"/>
                <span
                >Silver Member:
                  <span class="text-black">123 Point</span></span
                >
            </div>

            <hr class="my-4"/>

            <a href="{{ url('my-account') }}" class="menu-item {{ \Request::is('my-account') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu1.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>My account</span>
            </a>

            <a href="{{ url('my-order') }}"class="menu-item {{ \Request::is('my-order') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu2.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>Order information</span>
            </a>

            <a href="{{ url('my-wishlist') }}" class="menu-item {{ \Request::is('my-wishlist') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu3.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>My Wishlist</span>
            </a>

            <a href="{{ url('my-voucher') }}" class="menu-item {{ \Request::is('my-voucher') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu4.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>Vouchers</span>
            </a>

            <a href="{{ url('logout/customer') }}" class="menu-item">
                <img data-src="{{ url('assets/icons/icon_menu5.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>Log out of account</span>
            </a>
        </div>
    </div>
</div>
