<div class="w-full sm:w-1/4">
    <div class="bg-white rounded-lg shadow sm:mr-4 pt-6">
        <div class="avatar-container">
            <div class="avatar-inner">
                <div
                        class="w-32 h-32 bg-[#F1EFE9] rounded-full flex items-center justify-center"
                >
                    <img class="avatarUser" src="{{ url(\Auth::guard('loyal_customer')->user()->getAvatarDefault() ) }}">
                </div>
                <button
                        class="absolute bottom-0 right-0 bg-white rounded-full p-1 border border-gray-300"
                >
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
                <img data-src="{{ url('assets/icons/icon_menu1.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>My account</span>
            </a>

            <a href="{{ url('my-order') }}"class="menu-item {{ \Request::is('my-order') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu2.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>Order information</span>
            </a>

            <a href="{{ url('my-wishlist') }}" class="menu-item {{ \Request::is('my-wishlist') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu3.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>My Wishlist</span>
            </a>

            <a href="{{ url('my-voucher') }}" class="menu-item {{ \Request::is('my-voucher') ? 'active' : '' }}">
                <img data-src="{{ url('assets/icons/icon_menu4.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>Vouchers</span>
            </a>

            <a href="{{ url('logout/customer') }}" class="menu-item">
                <img data-src="{{ url('assets/icons/icon_menu5.svg') }}" alt="Point" class="mr-2 lazyload"/>
                <span>Log out of account</span>
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
