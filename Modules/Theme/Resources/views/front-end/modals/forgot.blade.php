<!-- Modal Background Overlay -->
<div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayForgot"
>
    <!-- Modal Container -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Close Button -->
        <button
                onclick="toggleModal('modalOverlayForgot')"
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
        <h2 class="text-3xl font-normal text-black mb-2">Forgot password ?</h2>
        <p class="text-gray-600 mb-4">Enter OTP received by sms</p>
        <!-- Registration Form -->
        <form id="forgotForm">
            <!-- Name Fields -->
            <div class="grid grid-cols-2 gap-4 mb-4"></div>

            <!-- Phone Field -->
            <div class="flex mb-4">
                <div class="flex-1">
                    <input
                            type="tel"
                            placeholder="Your Number phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-primary focus:border-primary transition-all duration-200"
                    />
                </div>
            </div>
            <!-- Terms and Conditions with Radio style checkbox -->
            <div class="flex items-center mb-6">
                <!-- Sign Up Button -->
                <button
                        class="w-full border border-gray-300 hover:bg-primary-700 hover:text-white text-black font-medium py-2 px-4 rounded-lg transition mb-4 closeModalBtnForgot"
                >
                    Cancel
                </button>
                &nbsp;
                <button
                        type="submit"
                        class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition mb-4"
                >
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    const form = document.getElementById("forgotForm");
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        console.log("Form submitted!");
    });
</script>