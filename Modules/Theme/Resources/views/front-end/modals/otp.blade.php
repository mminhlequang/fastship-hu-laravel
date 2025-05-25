<style>
    #verifyBtn:disabled {
        background-color: #D3D3D3;  /* Light gray color */
        cursor: not-allowed;        /* Change the cursor to indicate it's disabled */
        opacity: 0.6;               /* Make the button slightly transparent */
    }

    #verifyBtn:disabled:hover {
        background-color: #D3D3D3;  /* Prevent any hover effect when disabled */
    }

    .otp-container {
        background: white; 
        max-width: 1232px;
        width: 100%;   
        height: 100%;
        overflow-y: auto;              
        padding: 32px 24px 24px;             
        gap: 24px;              
    }

    .otp-container .text-welcome {
        color: #74CA45;
    }

    .otp-container .text-description {
        font-size: 24px; 
        color: #0E0D0A; 
        line-height: 140%; 
        margin-bottom: 16px;
    }

    .otp-container .login-header-mobile {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .otp-container .form-container {
        padding: 0;
    }

    @media (min-width: 768px) {
        .otp-container {
            background: white; 
            padding: 20px 30px;   
            gap: 20px;
            height: auto;
        }

        .otp-container .text-description {
            font-size: 32px; 
            margin-bottom: 44px;
        }

        .otp-container .login-header-mobile {
            display: none;
        }

        .otp-container .form-container {
            padding: 0px;
        }
    }

    @media (min-width: 1160px) {
        .otp-container {
            background: #F9F8F6; 
            padding: 46px 60px;   
            gap: 92px;
            height: auto; 
        }

        .otp-container .text-description {
            font-size: 40px; 
            margin-bottom: 44px;
        }

        .otp-container .form-container {
            padding: 40px;
        }
    }
</style>


<!-- Modal Background Overlay -->
<div class="bg-black bg-opacity-50 fixed inset-0 hidden flex justify-center items-center min-h-screen modalOverlay modalOverlayOtp z-10">
    <!-- Modal Container -->
    <div class="otp-container w-full md:rounded-2xl grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="login-header-mobile">
                <a href="{{ url('/') }}" class="logo" style="background:white !important">
                    <img class="dashboard-image logo-lg" src="{{ url('images/logo.svg') }}" style="width:170px;">
                </a>
                <button onclick="toggleModal('modalOverlayOtp');" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
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
                <button onclick="toggleModal('modalOverlayOtp');" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98495 8.63224L16.015 16.6623M16.015 8.63224L7.98495 16.6623" stroke="#847D79" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Close</span>
                </button>
            </div>
            <!-- Registration Form -->
             <div class="form-container relative bg-white grid grid-cols-1 gap-4 rounded-xl">
                <div style="color: #222430;" class="text-2xl md:text-4xl font-medium mb-2">Enter OTP code</div>
                <div style="color: #847D79;">Enter OTP received by sms</div>
                <form id="otpForm">
                    @csrf
                    <div class="flex flex-wrap justify-between items-center mb-5">
                        <!-- OTP input fields with consistent styling -->
                        <input type="text" class="otp-input w-12 h-12 sm:w-12 sm:h-12 rounded-full text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" autofocus />
                        <input type="text" class="otp-input w-12 h-12 sm:w-12 sm:h-12 rounded-full text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                        <input type="text" class="otp-input w-12 h-12 sm:w-12 sm:h-12 rounded-full text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                        <input type="text" class="otp-input w-12 h-12 sm:w-12 sm:h-12 rounded-full text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                        <input type="text" class="otp-input w-12 h-12 sm:w-12 sm:h-12 rounded-full text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                        <input type="text" class="otp-input w-12 h-12 sm:w-12 sm:h-12 rounded-full text-center text-lg mx-1.5 bg-gray-100 focus:outline-none focus:border-primary" maxlength="1" />
                    </div>
                    <div class="flex justify-end items-center mb-6 text-sm">
                        <div class="text-orange-500 mr-2 flex items-center">
                            <span class="inline-block w-3 h-3 border-2 border-gray-200 border-t-orange-500 rounded-full animate-spin mr-1.5"></span>
                            <span id="countdown">15s</span>
                        </div>
                        <button class="text-gray-500 bg-transparent border-0 p-0 text-sm cursor-pointer disabled:text-gray-300 disabled:cursor-not-allowed" id="resendBtn" disabled>
                            Code not received
                        </button>
                    </div>
                    <div class="flex justify-between">
                        <button onclick="toggleModal('modalOverlayOtp');" type="button" class="flex-1 py-3 rounded-full border border-gray-300 text-gray-700 bg-white text-base cursor-pointer text-center mr-2" >Cancel</button>
                        <button type="button" class="flex-1 py-3 rounded-full border-0 bg-primary transition-all hover:bg-primary-700 text-white text-base cursor-pointer text-center ml-2" id="verifyBtn" disabled>Verify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

  const modal = document.getElementById('otpModal');
    const otpInputs = document.querySelectorAll('.otp-input');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const countdownEl = document.getElementById('countdown');

    let timeLeft = 15;
    let timer;

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
        verifyBtn.disabled = !isComplete || otpInputs.length !== 6;
    }

    resendBtn.addEventListener('click', () => {
        otpInputs.forEach((input) => input.value = '');
        verifyBtn.disabled = true;
        otpInputs[0].focus();
        startCountdown();
        checkOTPComplete();
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
                            localStorage.removeItem('phone');
                            window.location.reload(true);
                            $('.loading').removeClass('loader');
                            // toggleModal('modalOverlayRegisterSuccess')
                        } else {
                            let err = data.errors;
                            let mess = err.join("<br/>");
                            toastr.error(mess);
                            $('.loading').removeClass('loader');
                        }
                    },error: function (xhr, status, error) {
                        toastr.error("Something went wrong! Please try again.");
                        $('.loading').removeClass('loader');
                    }
                });

            })
            .catch(function (error) {
                toastr.error(error.message);
                $('.loading').removeClass('loader');

            });
    });


</script>