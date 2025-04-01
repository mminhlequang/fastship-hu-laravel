<header>
    <div
            class="top-bar bg-[#191720] px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 py-3"
    >
        <div class="flex items-center justify-between flex-col md:flex-row">
            <div class="text-white text-sm flex items-center gap-2">
                <img src="{{ url('assets/icons/map_top_bar_icon.svg') }}" class="w-6 h-6"/>
                <span id="location">{{ __('No location') }}</span>
                <span class="cursor-pointer text-secondary underline text-clifford"
                >Change Location</span
                >

            </div>
            <div class="text-white text-sm flex items-center gap-2">
                <span> Get 5% Off your first order</span>
                <span class="cursor-pointer text-secondary underline"
                >Promo: <span class="font-medium">ORDER5</span></span
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
                  <button
                          class="inline-block rounded-full border border-solid border-black/05 text-primary hover:bg-primary hover:text-white py-2 px-4"
                  >
                    Login up
                  </button>
                  <button
                          class="inline-block rounded-full bg-primary py-2 px-4 text-white hover:bg-primary-700"
                  >
                    Sign up
                  </button>
                </span>
                <!-- Language selector with dropdown -->
                <div class="relative language-selector">
                    <button
                            class="flex items-center space-x-1 focus:outline-none"
                            onclick="toggleLanguageDropdown()"
                    >
                        <img src="https://flagcdn.com/w40/hu.png" alt="Hungarian" class="w-6 h-4 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="languageDropdown" class="absolute right-0 mt-4 w-48 bg-white shadow-lg rounded-lg py-1 z-50 hidden">
                        <h5 class="px-4 py-2 text-sm text-black-500 font-bold py-4">Select language</h5>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="https://flagcdn.com/w40/hu.png" alt="Hungarian" class="w-6 h-4 mr-2 rounded">
                            <span>Magyar</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="https://flagcdn.com/w40/gb.png" alt="English" class="w-6 h-4 mr-2 rounded">
                            <span>English</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="https://flagcdn.com/w40/de.png" alt="German" class="w-6 h-4 mr-2 rounded">
                            <span>Deutsch</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="https://flagcdn.com/w40/fr.png" alt="French" class="w-6 h-4 mr-2 rounded">
                            <span>Français</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="https://flagcdn.com/w40/es.png" alt="Spanish" class="w-6 h-4 mr-2 rounded">
                            <span>Español</span>
                        </a>
                    </div>
                </div>

            </div>
    </nav>
</header>