<style>
    .register-success-container {
        background: white; 
        max-width: 1232px;
        width: 100%;   
        height: 100%;
        overflow-y: auto;              
        padding: 32px 24px 24px;             
        gap: 24px;              
    }

    .register-success-container .text-welcome {
        color: #74CA45;
    }

    .register-success-container .text-description {
        font-size: 24px; 
        color: #0E0D0A; 
        line-height: 140%; 
        margin-bottom: 16px;
    }

    .register-success-container .login-header-mobile {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .register-success-container .form-container {
        padding: 0;
    }

    @media (min-width: 768px) {
        .register-success-container {
            background: #F9F8F6; 
            padding: 46px 60px;   
            gap: 92px;
            height: auto; 
        }

        .register-success-container .text-description {
            font-size: 40px; 
            margin-bottom: 44px;
        }

        .register-success-container .login-header-mobile {
            display: none;
        }

        .register-success-container .form-container {
            padding: 40px;
        }
    }
</style>


<!-- Modal Background Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayRegisterSuccess z-10 overflow-auto">
    <!-- Modal Container -->
    <div class="register-success-container w-full md:rounded-2xl grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="login-header-mobile">
                <a href="{{ url('/') }}" class="logo" style="background:white !important">
                    <img class="dashboard-image logo-lg" src="{{ url('images/logo.svg') }}" style="width:170px;">
                </a>
                <button onclick="toggleModal('modalOverlayRegisterSuccess');" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
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
                <button onclick="toggleModal('modalOverlayRegisterSuccess');" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98495 8.63224L16.015 16.6623M16.015 8.63224L7.98495 16.6623" stroke="#847D79" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Close</span>
                </button>
            </div>

            <!-- success fully form -->
            <div class="form-container not-show relative bg-white rounded-xl">
                 <img style="width: 90px;" class="mx-auto" src="{{ url('assets/images/register_success.svg') }}" >

                <div style="color: #222430;" class="text-center text-2xl font-medium mt-4 mb-6">
                    Congratulations, you have successfully registered
                </div>


                <!-- Login Now Button -->
                <button class="w-full h-12 bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-full transition" >
                    Login now
                </button>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>