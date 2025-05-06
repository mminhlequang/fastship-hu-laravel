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
</style>


<!-- Modal Background Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayLogin z-10">
    <!-- Modal Container -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-10 relative">
        <!-- Close Button -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-3xl font-normal text-black">Login</h2>
            <button onclick="toggleModal('modalOverlayLogin')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        <!-- Registration Form -->
        <form id="loginForm" method="POST">
        @csrf
        <!-- Name Fields -->
            <div class="grid grid-cols-2 gap-4 mb-4"></div>

            <!-- Phone Field -->
            <div class="flex mb-4">
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