<!-- Modal Background Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayRegister">
    <!-- Modal Container -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Close Button -->
        <button
                onclick="toggleModal('modalOverlayRegister')"
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
        <h2 class="text-3xl font-normal text-black mb-6">Create an account</h2>

        <!-- Registration Form -->
        <form id="registrationForm">
            <!-- Name Fields -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <input
                            type="text"
                            placeholder="First name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                    />
                </div>
                <div>
                    <input
                            type="text"
                            placeholder="Last name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                    />
                </div>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <input
                        type="email"
                        placeholder="Email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                />
            </div>

            <!-- Phone Field -->
            <div class="flex mb-4">
                <div class="w-1/3 mr-2">
                    <!-- Replace button with proper select element -->
                    <select
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left appearance-none focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                    >
                        <option value="hungary" selected>Hungary</option>
                        <option value="austria">Austria</option>
                        <option value="germany">Germany</option>
                        <option value="france">France</option>
                        <option value="italy">Italy</option>
                        <option value="spain">Spain</option>
                        <option value="uk">United Kingdom</option>
                        <option value="usa">United States</option>
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
                            autocomplete="phone"
                            type="tel"
                            placeholder="Number phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                    />
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-4 relative">
                <input
                        autocomplete="password"
                        type="password"
                        id="passwordR"
                        placeholder="Password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200 password"
                />
                <button
                        type="button"
                        id="togglePasswordRegister"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 togglePasswordRegister"
                >
                    <img src="{{ url('assets/icons/icon_eye.svg') }}" >
                </button>
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-6 relative">
                <input
                        autocomplete="current-password"
                        type="password"
                        id="confirmPassword"
                        placeholder="Confirm Password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                />
                <button
                        type="button"
                        id="toggleConfirmPassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                >
                    <img src="{{ url('assets/icons/icon_eye.svg') }}" >
                </button>
            </div>

            <!-- Terms and Conditions with Radio style checkbox -->
            <div class="flex items-center mb-6">
                <div class="relative">
                    <input type="checkbox" id="termsCheckRegister" class="hidden" />
                    <button
                            type="button"
                            id="customCheckboxRegister"
                            class="w-5 h-5 border border-gray-300 rounded-full focus:outline-none focus:ring-0 focus:ring-primary bg-white flex items-center justify-center"
                    >
                        <div
                                class="h-3 w-3 rounded-full bg-primary hidden checkmarkRegister"
                        ></div>
                    </button>
                </div>
                <label for="termsCheckRegister" class="ml-2 text-sm text-gray-600">
                    You must accept the
                    <a href="#" class="text-secondary hover:underline"
                    >terms and conditions</a
                    >
                </label>
            </div>

            <!-- Sign Up Button -->
            <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition mb-4"
            >
                Sign up
            </button>


        </form>
        <!-- Login Link -->
        <div class="text-center text-sm text-gray-600">
            Already have an account?
            <button onclick="toggleModal('modalOverlayLogin')" class="text-secondary hover:underline">Log in</button>
        </div>
    </div>
</div>
<script type="text/javascript">

    const togglePasswordRegister = document.getElementById("togglePasswordRegister");

    const passwords = document.getElementsByClassName("password");

    togglePasswordRegister.addEventListener("click", () => {
        for (let i = 0; i < passwords.length; i++) {
            const password = passwords[i];
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
        }

        togglePasswordRegister.innerHTML =
            passwords[0].getAttribute("type") === "password"
                ? `<img src="{{ url('assets/icons/icon_eye.svg') }}" alt="eye off">`
                : `<img src="{{ url('assets/icons/icon_eye_off.svg') }}" alt="eye">`;
    });



    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const confirmPassword = document.getElementById("confirmPassword");

    toggleConfirmPassword.addEventListener("click", () => {
        const type =
            confirmPassword.getAttribute("type") === "password"
                ? "text"
                : "password";
        confirmPassword.setAttribute("type", type);
        toggleConfirmPassword.innerHTML =
            type === "password"
                ? `<img src="{{ url('assets/icons/icon_eye_off.svg') }}" alt="eye off">`
                : `<img src="{{ url('assets/icons/icon_eye.svg') }}" alt="eye">`;
    });

    const customCheckboxRegister = document.getElementById("customCheckboxRegister");
    const termsCheckRegister = document.getElementById("termsCheckRegister");
    const checkmarkRegister = document.querySelector(".checkmarkRegister");

    customCheckboxRegister.addEventListener("click", () => {
        termsCheckRegister.checked = !termsCheckRegister.checked;
        if (termsCheckRegister.checked) {
            checkmarkRegister.classList.remove("hidden");
        } else {
            checkmarkRegister.classList.add("hidden");
        }
    });



</script>