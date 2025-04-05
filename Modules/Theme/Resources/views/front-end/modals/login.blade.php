<!-- Modal Background Overlay -->
<div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayLogin"
>
    <!-- Modal Container -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Close Button -->
        <button
                onclick="toggleModal('modalOverlayLogin')"
                class="absolute right-4 top-4 text-gray-500 hover:text-gray-700"
        >
            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
            >
                <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                />
            </svg>
        </button>

        <!-- Header -->
        <h2 class="text-3xl font-normal text-black mb-6">Login</h2>

        <!-- Registration Form -->
        <form id="loginForm" method="POST">
        @csrf
        <!-- Name Fields -->
            <div class="grid grid-cols-2 gap-4 mb-4"></div>

            <!-- Phone Field -->
            <div class="flex mb-4">
                <div class="w-1/3 mr-2">
                    <!-- Replace button with proper select element -->
                    <select name="code"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left appearance-none focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                    >
                        <option value="+84" selected>Hungary</option>
                        <option value="+84">Austria</option>
                        <option value="+84">Germany</option>
                        <option value="+84">France</option>
                        <option value="+84">Italy</option>
                        <option value="+84">Spain</option>
                        <option value="+84">United Kingdom</option>
                        <option value="+84">United States</option>
                    </select>
                    <div class="relative">
                        <div
                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
                                style="margin-top: -30px"
                        >
                            <svg
                                    class="w-4 h-4 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                            >
                                <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <input
                            name="phone"
                            autocomplete="phone"
                            type="tel"
                            placeholder="Number phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                            required
                    />
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-4 relative">
                <input
                        autocomplete="current-password"
                        type="password"
                        id="password"
                        placeholder="Password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                        required
                />
                <button
                        type="button"
                        id="togglePassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                >
                    <img src="{{ url('assets/icons/icon_eye.svg') }}">
                </button>
            </div>

            <!-- Terms and Conditions with Radio style checkbox -->
            <div class="flex items-center mb-6">
                <div class="relative">
                    <input type="checkbox" id="termsCheck" class="hidden"/>
                    <button
                            type="button"
                            id="customCheckbox"
                            class="w-5 h-5 border border-gray-300 rounded-full focus:outline-none focus:ring-0 focus:ring-primary bg-white flex items-center justify-center"
                    >
                        <div
                                class="h-3 w-3 rounded-full bg-primary hidden checkmark"
                        ></div>
                    </button>
                </div>
                <label for="termsCheck" class="ml-2 text-sm text-gray-600">
                    Remember me
                </label>
            </div>
            <div id="recaptcha-container"></div>
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <!-- Sign Up Button -->
            <button
                    class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition mb-4"
            >
                Log in
            </button>


            <!-- Login Link -->
            <div class="text-center text-sm text-gray-600">
                Already have an account? 
                <a onclick="toggleModal('modalOverlayRegister')" class="text-secondary hover:underline">Sign up</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("password");

    togglePassword.addEventListener("click", () => {
        const type =
            password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        togglePassword.innerHTML =
            type === "password"
                ? `<img src="{{ url('assets/icons/icon_eye.svg') }}" alt="eye off">`
                : `<img src="{{ url('assets/icons/icon_eye_off.svg') }}" alt="eye">`;
    });

    const customCheckbox = document.getElementById("customCheckbox");
    const termsCheck = document.getElementById("termsCheck");
    const checkmark = document.querySelector(".checkmark");

    customCheckbox.addEventListener("click", () => {
        termsCheck.checked = !termsCheck.checked;
        if (termsCheck.checked) {
            checkmark.classList.remove("hidden");
        } else {
            checkmark.classList.add("hidden");
        }
    });

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