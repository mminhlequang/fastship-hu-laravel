<header>
    <div class="top-bar bg-[#191720] responsive-px py-3">
        <div class="flex items-center justify-between flex-col md:flex-row">
            <div class="text-white text-sm flex items-center gap-2 cursor-pointer">
                <img alt="Fast Ship Hu" src="{{ url('assets/icons/map_top_bar_icon.svg') }}" class="w-6 h-6"/>
                <span id="location"
                      class="currentLocationText">{{ $_COOKIE['address'] ?? __('theme::web.header_location_not') }}</span>
                <span class="cursor-pointer text-secondary underline text-clifford changeLocationBtn"> {{ __('theme::web.header_location') }}</span>

            </div>
            <div class="text-white text-sm flex items-center gap-2 cursor-pointer">
                <span> {{ __('theme::web.header_promotion') }}</span>
                <span class="cursor-pointer text-secondary underline">{{ __('theme::web.header_promo') }}: <span
                            class="font-medium">{{ __('theme::web.header_order') }}</span></span>
            </div>
        </div>
    </div>
    <nav class="border border-solid border-black/05 responsive-px">
        <div class="flex flex-wrap items-center justify-between">
            <div class="py-4">
                <a href="{{ url('') }}">
                    <img src="{{ url('assets/images/logo_main.svg') }}" alt="FastShipHu Logo" class="h-8 md:h-8"/>
                </a>
            </div>
            <div class="flex flex-wrap items-center">
                @if(\Auth::guard('loyal_customer')->check())
                    <span id="cart-icon"
                          class="relative px-5 py-5 border-l border-r border-solid border-gray flex cursor-pointer items-center justify-center">
                        <div class="relative">
                            <img alt="Fast Ship Hu" src="{{ url('assets/icons/shopping_bag_icon.svg') }}" class="w-6 h-6" />
                            <span id="cart-badge"
                                  class="absolute -top-1 -right-1 bg-secondary text-white text-xs rounded-full w-4 h-4 flex items-center justify-center shadow">
                                @if(!empty($carts))
                                    {{ $carts->flatMap->cartItems->sum('quantity') }}
                                @else
                                    0
                                @endif
                            </span>
                        </div>

                        @include('theme::front-end.dropdown.cart')
                    </span>
                    <span id="notification-container"
                          class="relative px-5 py-5 border-l border-r border-solid border-gray flex cursor-pointer">
                        <img id="notification-icon" src="{{ url('assets/icons/bell.svg') }}" class="relative m-auto"/>
                        @include('theme::front-end.dropdown.notification')

                    </span>
                    <span id="favorite-container"
                          class="relative px-5 py-5 border-l border-r border-solid border-gray flex cursor-pointer">
                        <img alt="Fast Ship Hu" id="favorite-icon" src="{{ url('assets/icons/heart.svg') }}"
                             class="m-auto"/>
                        @include('theme::front-end.dropdown.favorites')
                    </span>
                @endif
                @if(!\Auth::guard('loyal_customer')->check())
                    <span class="border-l border-r border-solid border-gray flex px-5 py-5 cursor-pointer" onclick="toggleModal('modalOverlayLogin')">
                        <img alt="Fast Ship Hu" src="{{ url('assets/icons/shopping_bag_icon.svg') }}" class="w-6 h-6"/>
                    </span>
                @endif
                @if(\Auth::guard('loyal_customer')->check())
                    <div onclick="toggleUserDropdown()"
                         class="relative user-selector cursor-pointer flex items-center ml-4 mr-2 bg-gray-100 rounded-3xl p-2">
                        <img style="border-radius: 100%;" width="30" height="30" alt="Fast Ship Hu"
                             src="{{ url(\Auth::guard('loyal_customer')->user()->getAvatarDefault()) }}"
                             class="avatarUser m-auto"/>
                        &nbsp;
                        <span class="text-black-50">{{ \Auth::guard('loyal_customer')->user()->name ?? '' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                        <!-- User Dropdown -->
                        <div id="userDropdown" style="top: 50px;"
                             class="absolute left-0 w-full bg-white shadow-lg rounded-lg py-1 z-50 hidden mt-2">
                            <!-- Language Options -->
                            <a href="{{ url('my-account') }}"
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="text-black">{{ __('theme::web.header_my_account') }}</span>
                            </a>
                            <a href="{{ url('my-order') }}"
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="text-black">{{ __('theme::web.header_my_order') }}</span>
                            </a>
                            <a href="{{ url('my-voucher') }}"
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="text-black">{{ __('theme::web.header_my_voucher') }}</span>
                            </a>
                            <a href="{{ url('logout/customer') }}"
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="text-black">{{ __('theme::web.header_logout') }}</span>
                            </a>
                        </div>
                        <script type="text/javascript">
                            window.addEventListener('click', function (e) {
                                if (!e.target.closest('.user-selector')) {
                                    const dropdown = document.getElementById('userDropdown');
                                    if (!dropdown.classList.contains('hidden')) {
                                        dropdown.classList.add('hidden');
                                    }
                                }
                            });
                        </script>
                    </div>

                @else
                    <span class="flex items-center ml-4 mr-2">
                        <button onclick="toggleModal('modalOverlayLogin')"
                                class="inline-block rounded-full bg-primary py-2 px-4 text-white hover:bg-primary-700">
                            {{ __('theme::web.login') }}
                        </button>
                </span>
            @endif

            <!-- Language selector with dropdown -->
                <div class="relative language-selector ml-2">
                    <button class="flex items-center space-x-1 focus:outline-none" onclick="toggleLanguageDropdown()">
                        <img src="{{ url('img/'. (session('language') ?? app()->getLocale()).'.png') }}" alt="Fast ship"
                             class="w-6 h-6 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Language Dropdown -->
                    <div id="languageDropdown"
                         class="absolute right-0 mt-4 w-48 bg-white shadow-lg rounded-lg py-1 z-50 hidden">
                        <h5 class="px-4 py-2 text-sm text-black-500 font-bold py-4">{{ __('theme::web.header_select') }}</h5>

                        <!-- Language Options -->
                        <a onclick="setLanguageAndSubmit('vi'); return false;" href="javascript:;"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="{{ url('img/vi.png') }}" alt="Vietnamese" class="w-6 h-6 mr-2 rounded">
                            <span>Tiếng việt</span>
                        </a>
                        <a onclick="setLanguageAndSubmit('en'); return false;" href="javascript:;"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="{{ url('img/en.png') }}" alt="English" class="w-6 h-6 mr-2 rounded">
                            <span>English</span>
                        </a>
                        <a onclick="setLanguageAndSubmit('zh'); return false;" href="javascript:;"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="{{ url('img/zh.png') }}" alt="Chinese" class="w-6 h-6 mr-2 rounded">
                            <span>China</span>
                        </a>
                        <a onclick="setLanguageAndSubmit('hu'); return false;" href="javascript:;"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="{{ url('img/hu.png') }}" alt="Hungary" class="w-6 h-6 mr-2 rounded">
                            <span>Hungary</span>
                        </a>

                        <!-- Hidden Form to Submit Locale -->
                        {!! Form::open(['method' => 'GET', 'url' => 'change_locale', 'class' => 'form-inline navbar-select', 'id' => 'frmLag']) !!}
                        <input type="hidden" id="locale_client" name="language" value="">
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </nav>
</header>