<div class="w-full sm:w-1/4">
    <div class="bg-white rounded-lg shadow sm:mr-4 pt-6">
        <div class="avatar-container">
            <div class="avatar-inner">
                <div class="w-32 h-32 bg-[#F1EFE9] rounded-full flex items-center justify-center">
                    <img class="avatarUser"
                         src="{{ url(\Auth::guard('loyal_customer')->user()->getAvatarDefault() ) }}">
                </div>
                <button class="absolute bottom-0 right-0 bg-white rounded-full p-1 border border-gray-300">
                    <img data-src="{{ url('assets/icons/icon_camera.svg') }}" alt="Camera" class="fa-camera lazyload"/>
                </button>
            </div>
        </div>

        <div class="px-4 pb-6">
            <h2 class="text-xl font-medium text-center">{{ \Auth::guard('loyal_customer')->user()->name }}</h2>
            <div class="flex items-center justify-center mt-2 text-gray-500">
                <img data-src="{{ url('assets/icons/icon_rank.svg') }}" alt="Point" class="w-4 h-4 mr-1 lazyload"/>
                <span
                >Silver Member:
                  <span class="text-black">0 Point</span></span
                >
            </div>

            <hr class="my-4"/>

            <a href="{{ url('my-account') }}" class="menu-item {{ \Request::is('my-account') ? 'active' : '' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M11.9848 14.9053C8.63011 14.9053 5.76526 15.4125 5.76526 17.4438C5.76526 19.4752 8.61193 20.0005 11.9848 20.0005C15.3395 20.0005 18.2036 19.4925 18.2036 17.462C18.2036 15.4315 15.3577 14.9053 11.9848 14.9053Z"
                          stroke="{{ \Request::is('my-account') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M11.9847 12.0077C14.1862 12.0077 15.9706 10.2225 15.9706 8.021C15.9706 5.81949 14.1862 4.03516 11.9847 4.03516C9.78323 4.03516 7.99807 5.81949 7.99807 8.021C7.99064 10.2151 9.76341 12.0002 11.9567 12.0077H11.9847Z"
                          stroke="{{ \Request::is('my-account') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.42857"
                          stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                &nbsp;
                <span>My Account</span></a>

            <a href="{{ url('my-order') }}" class="menu-item {{ \Request::is('my-order') ? 'active' : '' }}"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.5 9.39996L7.5 4.20996" stroke="{{ \Request::is('my-order') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M21 15.9999V7.9999C20.9996 7.64918 20.9071 7.30471 20.7315 7.00106C20.556 6.69742 20.3037 6.44526 20 6.2699L13 2.2699C12.696 2.09437 12.3511 2.00195 12 2.00195C11.6489 2.00195 11.304 2.09437 11 2.2699L4 6.2699C3.69626 6.44526 3.44398 6.69742 3.26846 7.00106C3.09294 7.30471 3.00036 7.64918 3 7.9999V15.9999C3.00036 16.3506 3.09294 16.6951 3.26846 16.9987C3.44398 17.3024 3.69626 17.5545 4 17.7299L11 21.7299C11.304 21.9054 11.6489 21.9979 12 21.9979C12.3511 21.9979 12.696 21.9054 13 21.7299L20 17.7299C20.3037 17.5545 20.556 17.3024 20.7315 16.9987C20.9071 16.6951 20.9996 16.3506 21 15.9999Z" stroke="{{ \Request::is('my-order') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.27002 6.95996L12 12.01L20.73 6.95996" stroke="{{ \Request::is('my-order') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 22.08V12" stroke="{{ \Request::is('my-order') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                &nbsp; <span>Order information</span></a>

            <a href="{{ url('my-wishlist') }}" class="menu-item {{ \Request::is('my-wishlist') ? 'active' : '' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.98389 11.6106L9.11798 18.5107C10.5955 20.4964 13.4045 20.4964 14.882 18.5107L20.0161 11.6106C21.328 9.84746 21.328 7.34218 20.0161 5.57906C18.0957 2.9981 13.6571 3.76465 12 6.54855C10.3429 3.76465 5.90428 2.9981 3.9839 5.57906C2.67204 7.34218 2.67203 9.84746 3.98389 11.6106Z"
                          stroke="{{ \Request::is('my-wishlist') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                &nbsp;
                <span>{{ __('theme::web.menu_wishlist') }}</span>
            </a>

            <a href="{{ url('my-voucher') }}" class="menu-item {{ \Request::is('my-voucher') ? 'active' : '' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.8497 4.25V6.67" stroke="{{ \Request::is('my-voucher') ? '#FFFFFF' : '#847D79' }}"
                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M13.8497 17.7598V19.7838" stroke="{{ \Request::is('my-voucher') ? '#FFFFFF' : '#847D79' }}"
                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M13.8497 14.3239V9.50293" stroke="{{ \Request::is('my-voucher') ? '#FFFFFF' : '#847D79' }}"
                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M18.7021 20C20.5242 20 22 18.5426 22 16.7431V14.1506C20.7943 14.1506 19.8233 13.1917 19.8233 12.001C19.8233 10.8104 20.7943 9.85039 22 9.85039L21.999 7.25686C21.999 5.45745 20.5221 4 18.7011 4H5.29892C3.47789 4 2.00104 5.45745 2.00104 7.25686L2 9.93485C3.20567 9.93485 4.17668 10.8104 4.17668 12.001C4.17668 13.1917 3.20567 14.1506 2 14.1506V16.7431C2 18.5426 3.4758 20 5.29787 20H18.7021Z"
                          stroke="{{ \Request::is('my-voucher') ? '#FFFFFF' : '#847D79' }}" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>&nbsp;
                <span>Vouchers</span></a>

            <a href="{{ url('logout/customer') }}" class="menu-item" style="padding: 15px!important;">
                <img data-src="{{ url('assets/icons/icon_menu5.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>{{ __('theme::web.menu_logout') }}</span>
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const avatarPath = document.getElementsByClassName("avatarUser");
        const avatarContainer = document.querySelector(".w-32.h-32");
        const cameraButton = document.querySelector(".fa-camera").parentNode;

        const fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.accept = "image/*";
        fileInput.style.display = "none";
        document.body.appendChild(fileInput);

        cameraButton.addEventListener("click", function () {
            fileInput.click();
        });

        fileInput.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    avatarContainer.innerHTML = "";
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.className = "w-full h-full object-cover rounded-full";

                    const formData = new FormData();
                    formData.append("avatar", file);
                    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    fetch("{{ url('ajaxFE/uploadAvatar') }}", {
                        method: "POST",
                        body: formData,
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                for (let i = 0; i < avatarPath.length; i++) {
                                    avatarPath[i].src = data.path;
                                }
                                toastr.success("Avatar uploaded successfully!");
                                avatarContainer.appendChild(img);
                            } else {
                                toastr.error("Error uploading avatar!");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            toastr.error("An error occurred while uploading.");
                        });

                };
                reader.readAsDataURL(file);
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const tabButtons = document.querySelectorAll(".tab-btn");
        const tabContents = document.querySelectorAll(".tab-content");

        tabButtons.forEach((button) => {
            button.addEventListener("click", () => {
                tabButtons.forEach((btn) => {
                    btn.classList.remove("active");
                    btn.classList.remove("text-primary");
                    btn.classList.add("text-gray-500");
                });

                button.classList.add("active");
                button.classList.remove("text-gray-500");
                button.classList.add("text-primary");

                tabContents.forEach((content) => {
                    content.classList.remove("active");
                });

                const tabId = button.getAttribute("data-tab");
                document.getElementById(tabId).classList.add("active");
            });
        });

        const eyeIcon = document.querySelector(".eye-icon");
        const passwordField = document.querySelector('input[type="password"]');

        if (eyeIcon && passwordField) {
            eyeIcon.addEventListener("click", () => {
                const type =
                    passwordField.getAttribute("type") === "password"
                        ? "text"
                        : "password";
                passwordField.setAttribute("type", type);

                if (type === "text") {
                    eyeIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    `;
                } else {
                    eyeIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    `;
                }
            });
        }
    });
</script>
