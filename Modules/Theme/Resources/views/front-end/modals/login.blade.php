<style>
    /* Khung selected item */
    .select2-container .select2-selection--single {
        height: 48px !important;
        display: flex !important;
        align-items: center;
        border-radius: 1.5rem; /* rounded-lg */
        border: 1px solid #d1d5db; /* border-gray-300 */
        padding: 0 1rem;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 0.75rem !important; /* rounded-lg */
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #F17228 !important;
        padding-left: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        display: none;
    }

    .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 10px !important;
    }

    .select2-results__option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .select2-container .select2-search--dropdown .select2-search__field {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }

    .arrow-replace-select2 {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
    }

    .login-container {
        background: white; 
        max-width: 1232px;
        width: 100%;   
        height: 100%;
        overflow-y: auto;              
        padding: 32px 24px 24px;             
        gap: 24px;              
    }

    .login-container .text-welcome {
        color: #74CA45;
    }

    .login-container .text-description {
        font-size: 24px; 
        color: #0E0D0A; 
        line-height: 140%; 
        margin-bottom: 16px;
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
    }


    @media (min-width: 768px) {
        .login-container {
            background: #F9F8F6; 
            padding: 46px 60px;   
            gap: 92px;
            height: auto;
            
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
        }

    }
</style>


<!-- Modal Background Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayLogin z-10 overflow-auto">
    <!-- Modal Container -->
    <div class="login-container w-full md:rounded-2xl grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="login-header-mobile">
                <a href="{{ url('/') }}" class="logo" style="background:white !important">
                    <img class="dashboard-image logo-lg" src="{{ url('images/logo.svg') }}" style="width:170px;">
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
                <a href="{{ url('/') }}" class="logo" style="background:white !important">
                    <img class="dashboard-image logo-lg" src="{{ url('images/logo.svg') }}" style="width:258px;">
                </a>
                <button onclick="toggleModal('modalOverlayLogin')" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98495 8.63224L16.015 16.6623M16.015 8.63224L7.98495 16.6623" stroke="#847D79" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Close</span>
                </button>
            </div>
            <!-- Registration Form -->
            <form id="loginForm" class="form-container bg-white grid grid-cols-1 gap-4 rounded-xl" method="POST">

                <div style="color: #222430;" class="text-2xl md:text-4xl font-medium mb-2">Log in</div>

                <div style="color: #847D79;">Log in to order your favorite products with just a few short details</div>
                @csrf
                <!-- Name Fields -->
                <!-- <div class="grid grid-cols-2 gap-4 mb-4"></div> -->

                <!-- Phone Field -->
                <div class="flex">
                    <div class="w-1/3 mr-2 relative">
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
                {{--            <div class="relative">--}}
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

                <!-- <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="checkbox" id="rememberLogin" class="hidden" />
                        <button
                                type="button"
                                id="customCheckboxRemember"
                                class="w-5 h-5 border border-gray-300 rounded-full focus:outline-none focus:ring-0 focus:ring-primary bg-white flex items-center justify-center"
                        >
                            <div
                                class="h-3 w-3 rounded-full bg-primary hidden checkRemember"
                            ></div>
                        </button>
                    </div>
                    <label for="rememberLogin" class="text-gray-500">
                        Remember me
                    </label>
                </div> -->


                <!-- Sign Up Button -->
                <button class="w-full h-12 bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-full transition" >
                    Log in
                </button>

            </form>
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


    
    const customCheckboxRemember = document.getElementById("customCheckboxRemember");
    const rememberLogin = document.getElementById("rememberLogin");
    const checkRemember = document.querySelector(".checkRemember");

    customCheckboxRemember.addEventListener("click", () => {
        rememberLogin.checked = !rememberLogin.checked;
        if (rememberLogin.checked) {
            checkRemember.classList.remove("hidden");
        } else {
            checkRemember.classList.add("hidden");
        }
    });

</script>