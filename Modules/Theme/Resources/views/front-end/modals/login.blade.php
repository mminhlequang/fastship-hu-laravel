<style>
    /* Khung selected item */
    .select2-container .select2-selection--single {
        height: 48px !important;
        display: flex !important;
        align-items: center;
        border-radius: 12px !important;
        border: 1px solid #d1d5db !important;
        padding: 0 40px 0 16px !important;
        background: white !important;
        transition: all 0.2s ease !important;
        outline: none !important;
        min-width: 200px;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--open .select2-selection--single {
        border-color: #F17228 !important;
        box-shadow: 0 0 0 3px rgba(241, 114, 40, 0.1) !important;
    }

    .select2-container--default .select2-selection--single:hover {
        border-color: #F17228 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        color: #F17228 !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 12px !important;
        width: 16px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        display: none !important;
    }

    /* Custom Arrow for Select2 */
    .arrow-replace-select2 {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6b7280;
        transition: all 0.2s ease;
        z-index: 10;
    }

    .select2-container--open + .arrow-replace-select2 {
        transform: translateY(-50%) rotate(180deg);
        color: #F17228;
    }

    /* Select2 Dropdown Styling */
    .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12) !important;
        overflow: hidden !important;
    }

    .select2-results__option {
        padding: 12px 16px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        font-size: 14px !important;
        color: #374151 !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
    }

    .select2-results__option:hover,
    .select2-results__option--highlighted {
        background: #f3f4f6 !important;
        color: #F17228 !important;
    }

    .select2-results__option--selected {
        background: #fef3e2 !important;
        color: #F17228 !important;
        font-weight: 600 !important;
    }

    .select2-search--dropdown .select2-search__field {
        padding: 12px 16px !important;
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        border-radius: 0 !important;
        font-size: 14px !important;
        outline: none !important;
    }

    /* Modal Overlay */
    .modalOverlay {
        backdrop-filter: blur(4px);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Login Container */
    .login-container {
        background: white;
        max-width: 1232px;
        width: 100%;
        height: 100%;
        overflow-y: auto;
        padding: 32px 24px 24px;
        gap: 24px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .login-container .text-welcome {
        color: #74CA45;
        font-weight: 500;
    }

    .login-container .text-description {
        font-size: 24px;
        color: #222430;
        line-height: 140%;
        margin-bottom: 16px;
        font-weight: 500;
    }

    .login-container .login-header-mobile {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .login-container .form-container {
        padding: 0;
        background: white !important;
        border-radius: 12px;
        box-shadow: none;
    }

    /* Form Title Styling */
    .login-container .form-container > div:first-child {
        color: #222430 !important;
        font-size: 2rem !important;
        font-weight: 500 !important;
        margin-bottom: 8px !important;
    }

    /* Form Subtitle Styling */
    .login-container .form-container > div:nth-child(2) {
        color: #847D79 !important;
        margin-bottom: 24px !important;
        line-height: 1.5 !important;
    }

    /* Phone Input Field */
    .phone-input-field {
        flex: 1;
        height: 48px !important;
        padding: 0 16px !important;
        background: #F9F8F6 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 12px !important;
        font-size: 14px !important;
        color: #222430 !important;
        outline: none !important;
        transition: all 0.2s ease !important;
    }

    .phone-input-field:focus {
        border-color: #F17228 !important;
        box-shadow: 0 0 0 3px rgba(241, 114, 40, 0.1) !important;
        background: white !important;
    }

    .phone-input-field::placeholder {
        color: #9ca3af !important;
    }

    /* Phone Field Container */
    .phone-field-container {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    /* Submit Button */
    .submit-btn {
        width: 100% !important;
        height: 48px !important;
        background: #74CA45 !important;
        color: white !important;
        border: none !important;
        border-radius: 24px !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin-top: 16px !important;
    }

    .submit-btn:hover {
        background: #68b83e !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(116, 202, 69, 0.3) !important;
    }

    .submit-btn:active {
        transform: translateY(0) !important;
    }

    /* Close Button Styling */
    .close-button {
        color: #847D79;
        transition: color 0.2s ease;
    }

    .close-button:hover {
        color: #222430;
    }

    /* Logo Styling */
    .logo img {
        height: 40px;
        width: auto;
    }

    /* Responsive Design */
    @media (min-width: 768px) {
        .login-container {
            background: #f9f8f6;
            padding: 46px 60px;
            gap: 92px;
            height: auto;
            border-radius: 16px;
        }

        .login-container .text-description {
            font-size: 40px;
            margin-bottom: 44px;
        }

        .login-container .login-header-mobile {
            display: none;
        }

        .login-container .form-container {
            padding: 40px;
            background: white !important;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .login-container .form-container > div:first-child {
            font-size: 2rem !important;
        }
    }

    @media (max-width: 640px) {
        .phone-field-container {
            flex-direction: column;
            gap: 12px;
        }
        
        .select2-container .select2-selection--single {
            min-width: auto;
        }
        
        .login-container .form-container > div:first-child {
            font-size: 1.75rem !important;
        }
    }

    /* Error State Styling */
    .error-state {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }

    /* Loading State */
    .loading-state {
        background: #68b83e !important;
        cursor: not-allowed !important;
    }

    /* Flag Emoji Styling */
    .country-flag {
        font-size: 16px;
        margin-right: 4px;
    }

    /* Enhanced Focus States */
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #F17228 !important;
        box-shadow: 0 0 0 3px rgba(241, 114, 40, 0.1) !important;
    }

    /* Smooth Animations */
    .login-container * {
        transition: all 0.2s ease;
    }

    /* Custom Scrollbar for Select2 Dropdown */
    .select2-results__option::-webkit-scrollbar {
        width: 6px;
    }

    .select2-results__option::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .select2-results__option::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .select2-results__option::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>


<!-- Modal Background Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayLogin z-10 overflow-auto">
    <!-- Modal Container -->
    <div class="login-container w-full md:rounded-2xl grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="login-header-mobile">
                <a href="{{ url('/') }}" class="logo" >
                    <img class="dashboard-image logo-lg" src="{{ url('assets/images/logo_main.svg') }}" >
                </a>
                <button onclick="toggleModal('modalOverlayLogin')" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98495 8.63224L16.015 16.6623M16.015 8.63224L7.98495 16.6623" stroke="#847D79" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Close</span>
                </button>
            </div>
            <div class="text-welcome text-xl mb-2">Welcome Back to FastshipHU  !</div>
                <div class="text-description font-medium">Where Every Meal is a Delicious Adventure !</div>
                <img alt="Fast Ship Hu" data-src="{{ url('assets/images/login_img.svg') }}" class="w-full lazyload" />
            </div>

    <div>
        <div>
            <div style="margin-bottom: 46px" class="hidden md:flex items-center gap-2 justify-between mb-6">
                <a href="{{ url('/') }}" class="logo" >
                    <img class="dashboard-image logo-lg" src="{{ url('assets/images/logo_main.svg') }}" >
                </a>
                <button onclick="toggleModal('modalOverlayLogin')" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98495 8.63224L16.015 16.6623M16.015 8.63224L7.98495 16.6623" stroke="#847D79" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Close</span>
                </button>
            </div>


            <div class="form-container bg-white grid grid-cols-1 gap-4 rounded-xl">
                <div style="color: #222430;" class="text-2xl md:text-4xl font-medium mb-2">Log in</div>

                <div style="color: #847D79;">Log in to order your favorite products with just a few short details</div>

                <!-- Registration Form -->
                <form id="loginForm" method="POST">
                @csrf
                <!-- Name Fields -->
                    <!-- <div class="grid grid-cols-2 gap-4 mb-4"></div> -->

                    <!-- Phone Field -->
                    <div class="flex mb-4">
                        <div class="w-auto mr-2 relative">
                            <select id="country-select" name="code" class="w-full rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200">
                                @foreach(\App\Helper\DataHelper::getCountryCode() as $country)
                                    <option value="{{ $country['dial_code'] }}"
                                            data-image="https://country-code-au6g.vercel.app/{{ $country['image'] }}">
                                        {{ $country['name'] }} ({{ $country['dial_code'] }})
                                    </option>
                                @endforeach
                            </select>

                            <div class="arrow-replace-select2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input
                                    name="phone"
                                    autocomplete="off"
                                    type="tel"
                                    placeholder="Number phone"
                                    class="w-full px-3 py-2 h-12 bg-[#F9F8F6] rounded-xl focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                                    required
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    {{--            <div class="mb-4 relative">--}}
                    {{--                <input--}}
                    {{--                        autocomplete="off"--}}
                    {{--                        type="password"--}}
                    {{--                        id="password"--}}
                    {{--                        placeholder="Password"--}}
                    {{--                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"--}}
                    {{--                        required--}}
                    {{--                />--}}
                    {{--                <button--}}
                    {{--                        type="button"--}}
                    {{--                        id="togglePassword"--}}
                    {{--                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"--}}
                    {{--                >--}}
                    {{--                    <img src="{{ url('assets/icons/icon_eye.svg') }}">--}}
                    {{--                </button>--}}
                    {{--            </div>--}}

                    {{--            <!-- Terms and Conditions with Radio style checkbox -->--}}
                    {{--            <div class="flex items-center mb-6">--}}
                    {{--                <div class="relative">--}}
                    {{--                    <input type="checkbox" id="termsCheck" class="hidden"/>--}}
                    {{--                    <button--}}
                    {{--                            type="button"--}}
                    {{--                            id="customCheckbox"--}}
                    {{--                            class="w-5 h-5 border border-gray-300 rounded-full focus:outline-none focus:ring-0 focus:ring-primary bg-white flex items-center justify-center"--}}
                    {{--                    >--}}
                    {{--                        <div--}}
                    {{--                                class="h-3 w-3 rounded-full bg-primary hidden checkmark"--}}
                    {{--                        ></div>--}}
                    {{--                    </button>--}}
                    {{--                </div>--}}
                    {{--                <label for="termsCheck" class="ml-2 text-sm text-gray-600">--}}
                    {{--                    Remember me--}}
                    {{--                </label>--}}
                    {{--            </div>--}}

                    <div id="recaptcha-container"></div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    <!-- Sign Up Button -->
                    <button class="w-full h-12 bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-full transition mt-4" >
                        Log in
                    </button>

                </form>

            </div>
        </div>

        </div>
      </div>
    </div>
</div>
<script type="text/javascript">

    const allInputs = document.querySelectorAll("input");
    allInputs.forEach((input) => {
        input.addEventListener("focus", () => {
            input.classList.add("ring-0", "ring-primary", "border-primary");
        });

        input.addEventListener("blur", () => {
            if (!input.value) {
                input.classList.remove("ring-0", "ring-primary", "border-primary");
            }
        });
    });

</script>