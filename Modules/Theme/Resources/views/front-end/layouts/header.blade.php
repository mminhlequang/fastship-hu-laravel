<header>
    <div
            class="top-bar bg-[#191720] px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 py-3"
    >
        <div class="flex items-center justify-between flex-col md:flex-row">
            <div class="text-white text-sm flex items-center gap-2">
                <img src="{{ url('assets/icons/map_top_bar_icon.svg') }}" class="w-6 h-6"/>
                <span id="location">{{ __('theme::web.header_location_not') }}</span>
                <span class="cursor-pointer text-secondary underline text-clifford"
                > {{ __('theme::web.header_location') }}</span
                >

            </div>
            <div class="text-white text-sm flex items-center gap-2">
                <span> {{ __('theme::web.header_promotion') }}</span>
                <span class="cursor-pointer text-secondary underline"
                >{{ __('theme::web.header_promo') }}: <span class="font-medium">{{ __('theme::web.header_order') }}</span></span
                >
            </div>
        </div>
    </div>
    <nav
            class="border border-solid border-black/05 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
    >
        <div class="flex flex-wrap items-center justify-between py-2 sm:py-0">
            <a href="/">
                <img
                        src="{{ url('assets/images/logo_main.svg') }}"
                        alt="FastShipHu Logo"
                        class="h-6 md:h-8"
                />
            </a>
            <div class="flex items-center gap-4">
                <span
                        class="h-[66px] w-[50px] border-l border-r border-solid border-black/05 flex"
                >
                  <img src="{{ url('assets/icons/shopping_bag_icon.svg') }}" class="m-auto"/>
                </span>
                <span class="flex items-center gap-2">
                  <button onclick="toggleModal('modalOverlayLogin')"
                          class="inline-block rounded-full bg-primary py-2 px-4 text-white hover:bg-primary-700"
                  >
                    {{ __('theme::web.login') }}
                  </button>
                </span>
                <!-- Language selector with dropdown -->
                <div class="relative language-selector">
                    <button
                            class="flex items-center space-x-1 focus:outline-none"
                            onclick="toggleLanguageDropdown()"
                    >
                        <img src="{{ url('img/'. (session('language') ?? app()->getLocale()).'.png') }}" alt="Fast ship" class="w-6 h-6 rounded">
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
    </nav>
</header>