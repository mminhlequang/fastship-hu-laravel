<!-- OTP Modal -->
<div id="otpModal" class="bg-black bg-opacity-50 fixed inset-0 hidden flex justify-center items-center min-h-screen modalOverlay modalOverlayOtp">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <div class="flex items-center mb-2">
            <button id="closeModalBtn" class="text-gray-700 text-2xl mr-3 bg-transparent border-0 cursor-pointer">
                <img data-src="{{ url('assets/icons/icon_left.svg') }}" alt="Close" class="lazyload" />
            </button>
            <h2 class="text-3xl font-normal text-black">Enter OTP code</h2>
        </div>
        <p class="text-gray-500 mb-4">Enter OTP received by SMS</p>
        <form id="otpForm">
            @csrf
            <div class="flex flex-wrap justify-between items-center mb-5">
                <!-- OTP input fields with consistent styling -->
                <input type="text" class="otp-input w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-gray-300 text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" autofocus />
                <input type="text" class="otp-input w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-gray-300 text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                <input type="text" class="otp-input w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-gray-300 text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                <input type="text" class="otp-input w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-gray-300 text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                <input type="text" class="otp-input w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-gray-300 text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                <input type="text" class="otp-input w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-gray-300 text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
            </div>
            <div class="flex justify-center items-center mb-5 text-sm">
                <div class="text-orange-500 mr-2 flex items-center">
                    <span class="inline-block w-3 h-3 border-2 border-gray-200 border-t-orange-500 rounded-full animate-spin mr-1.5"></span>
                    <span id="countdown">15s</span>
                </div>
                <button class="text-gray-500 bg-transparent border-0 p-0 text-sm cursor-pointer disabled:text-gray-300 disabled:cursor-not-allowed disabled:no-underline underline" id="resendBtn" disabled>
                    Code not received
                </button>
            </div>
            <div class="flex justify-between">
                <button id="cancelBtn" type="button" class="flex-1 py-3 rounded-full border border-gray-300 text-gray-700 bg-white text-base cursor-pointer text-center mr-2" id="cancelBtn">Cancel</button>
                <button type="button" class="flex-1 py-3 rounded-full border-0 bg-primary transition-all hover:bg-primary-700 text-white text-base cursor-pointer text-center ml-2" id="verifyBtn" disabled>Verify</button>
            </div>
        </form>
    </div>
</div>
<script>
    const modal = document.getElementById('otpModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    const otpInputs = document.querySelectorAll('.otp-input');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const countdownEl = document.getElementById('countdown');

    let timeLeft = 15;
    let timer;

    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    function startCountdown() {
        timeLeft = 15;
        countdownEl.textContent = timeLeft + 's';
        resendBtn.disabled = true;

        clearInterval(timer);
        timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft + 's';
            if (timeLeft <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
            }
        }, 1000);
    }

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');

            if (e.target.value !== '' && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            checkOTPComplete();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    function checkOTPComplete() {
        let isComplete = true;
        otpInputs.forEach((input) => {
            if (input.value === '') {
                isComplete = false;
            }
        });
        verifyBtn.disabled = !isComplete;
    }

    resendBtn.addEventListener('click', () => {
        otpInputs.forEach((input) => input.value = '');
        verifyBtn.disabled = true;
        otpInputs[0].focus();
        startCountdown();
    });

    verifyBtn.addEventListener('click', (e) => {
        let otpValue = '';
        $('.loading').addClass('loader');
        otpInputs.forEach((input) => otpValue += input.value);
        window.confirmationResult.confirm(otpValue)
            .then(function (result) {
                var user = result.user;
                var userData = {
                    uid: user.uid,
                    phoneNumber: user.phoneNumber
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ url('ajaxFE/verifyOtp') }}',
                    method: "POST",
                    data: {
                        'otp': otpValue,
                        'userData': userData
                    },
                    success: function (response) {
                        const data = response;
                        if (data.status) {
                            toastr.success(data.message);
                            $('#verifyOtp')[0].reset();
                            localStorage.removeItem('phone');
                            window.location.href = '';
                        } else {
                            let err = data.errors;
                            let mess = err.join("<br/>");
                            toastr.error(mess);
                        }
                    },error: function (xhr, status, error) {
                        toastr.error("Something went wrong! Please try again.");
                    }
                });
                closeModal();
                $('.loading').removeClass('loader');

            })
            .catch(function (error) {
                toastr.error(error.message);
            });

    });

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>