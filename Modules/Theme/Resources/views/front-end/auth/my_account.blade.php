@extends('theme::front-end.master')
@section('style')
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .input-field:focus {
            border-color: #74ca45;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .password-field {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .avatar-container {
            padding-bottom: 20px;
        }

        .avatar-inner {
            width: 120px;
            height: 120px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin: 0 auto;
        }

        .initials {
            font-size: 48px;
            color: #7ac142;
            font-weight: bold;
        }

        .camera-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #f5f5f5;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #666;
        }

        .menu-item.active {
            border-radius: 12px;
            background-color: #74ca45;
            color: white;
        }

        .menu-item i {
            width: 24px;
            margin-right: 10px;
        }

        .pagination-item {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .logo {
            color: #7ac142;
            font-weight: bold;
            font-size: 24px;
        }
    </style>
    <style>
        .payment-option {
            transition: all 0.2s ease;
        }

        .payment-option:focus-within {
            border-color: #74ca45;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
        }

        .payment-option.selected {
            border-color: #74ca45;
        }

        /* Custom radio button styling */
        .custom-radio {
            position: relative;
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            background-color: white;
        }

        .custom-radio::after {
            content: "";
            position: absolute;
            display: none;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
        }

        input[type="radio"]:checked + .custom-radio {
            border-color: #22c55e;
        }

        input[type="radio"]:checked + .custom-radio::after {
            display: block;
        }

        input[type="radio"]:focus + .custom-radio {
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }
    </style>
    <style>
        .copy-animation {
            animation: copyFlash 0.5s ease;
        }

        @keyframes copyFlash {
            0%, 100% {
                background-color: #f9fafb;
            }
            50% {
                background-color: #e5e7eb;
            }
        }
    </style>
@endsection
@section('content')
    <section
            class="bg-gray-100 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 py-3"
    >
        <div class="flex flex-wrap">
            <!-- Left Sidebar -->
        @include('theme::front-end.layouts.sidebar')
        <!-- Right Content -->
            <div class="w-full sm:w-3/4 bg-white rounded-lg shadow-sm border">
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Tab Navigation -->
                    <div class="flex border-b border-blue-100">
                        <button
                                class="tab-btn px-5 py-3 text-sm font-medium text-primary active"
                                data-tab="account"
                        >
                            My account
                        </button>
                        <button
                                class="tab-btn px-5 py-3 text-sm font-medium text-gray-500"
                                data-tab="payment"
                        >
                            Payment methods
                        </button>
                        <button
                                class="tab-btn px-5 py-3 text-sm font-medium text-gray-500"
                                data-tab="rewards"
                        >
                            Add rewards
                        </button>
                        <div class="ml-auto">
                            <button class="px-5 py-3 text-sm font-medium text-pink-500">
                                Delete account
                            </button>
                        </div>
                    </div>
                    <!-- My Account Tab -->
                    <div id="account" class="tab-content active p-6 bg-gray-50">
                        @if ($errors->any())
                            <div class="bg-gray-50 border-l-4 border-secondary mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-500">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(Session::has('success'))
                            <div class="bg-gray-50 border-l-4 border-secondary mb-4 text-primary">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ url('customer/update_profile') }}">
                            @csrf
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-lg font-medium text-gray-800">Personal info</h2>
{{--                                <button class="text-secondary text-sm font-medium">Edit</button>--}}
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1"
                                    >Name</label
                                    >
                                    <input name="name" value="{{ \Auth::guard('loyal_customer')->user()->name }}"
                                           type="text"
                                           class="input-field w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none transition duration-200"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                                    <input name="email" value="{{ \Auth::guard('loyal_customer')->user()->email }}"
                                           type="email"
                                           class="input-field w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none transition duration-200"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1"
                                    >Number phone</label
                                    >
                                    <input value="{{ \Auth::guard('loyal_customer')->user()->phone }}"
                                           type="tel"
                                           class="input-field w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none transition duration-200"
                                           readonly
                                    />
                                </div>
                            </div>

                            <div class="mb-8">
                                <label class="block text-sm text-gray-600 mb-1">Password</label>
                                <div class="password-field">
                                    <input name="password" autocomplete="off"
                                           type="password"
                                           value=""
                                           class="input-field w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none transition duration-200"
                                    />
                                    <span class="eye-icon text-gray-400">
                                      <svg
                                              xmlns="http://www.w3.org/2000/svg"
                                              width="18"
                                              height="18"
                                              viewBox="0 0 24 24"
                                              fill="none"
                                              stroke="currentColor"
                                              stroke-width="2"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                      >
                                        <path
                                                d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                        ></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                      </svg>
                                    </span>
                                </div>
                                <div class="flex justify-end mt-2">
                                    <button class="text-primary text-sm font-medium">
                                        Change password
                                    </button>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-sm text-gray-600 mb-1">Addresses</label>
                                    <button class="text-primary text-sm font-medium">
                                        + Add address
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1"
                                        >Select your country</label
                                        >
                                        <div class="relative">
                                            <select
                                                    class="input-field w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none appearance-none transition duration-200"
                                            >
                                                <option>Select your country</option>
                                                <option>United States</option>
                                                <option>Canada</option>
                                                <option>United Kingdom</option>
                                            </select>
                                            <div
                                                    class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
                                            >
                                                <svg
                                                        class="w-4 h-4 text-gray-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                >
                                                    <path d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1"
                                        >Street name number</label
                                        >
                                        <div class="relative">
                                            <input name="street"
                                                   value="{{ \Auth::guard('loyal_customer')->user()->street }}"
                                                   type="text"
                                                   class="input-field w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none transition duration-200"
                                            />
                                            <div
                                                    class="absolute inset-y-0 right-0 flex items-center px-2"
                                            >
                                                <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="text-primary"
                                                >
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-8">
                                    <input name="address"
                                           type="text"
                                           value="{{ \Auth::guard('loyal_customer')->user()->address }}"
                                           class="input-field w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none transition duration-200"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <a href="{{ url('') }}"
                                   class="px-6 py-2 rounded-full text-gray-600 font-medium border border-gray-300"
                                >
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-8 py-2 rounded-full text-white font-medium bg-primary hover:bg-primary-700 transition duration-200">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Methods Tab -->
                    <div id="payment" class="tab-content p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">
                            Payment Methods
                        </h2>
                        <!-- Payment methods content would go here -->
                        <div id="payment">
                            <!-- Credit and debit cards section -->
                            <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm">
                                <h2 class="text-base font-medium text-gray-800 mb-2">
                                    Credit and debit cards
                                </h2>
                                <div class="border-t border-gray-100 pt-2">
                                    <button
                                            class="flex items-center text-green-500 text-sm font-medium py-2"
                                    >
                                        <span class="mr-1">+</span> Add new card
                                    </button>
                                </div>
                            </div>

                            <!-- Other methods section -->
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <h2 class="text-base font-medium text-gray-800 mb-4">
                                    Other methods
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Cash option - Selected -->
                                    <div
                                            class="payment-option selected relative flex items-center p-4 border rounded-lg cursor-pointer"
                                    >
                                        <input
                                                type="radio"
                                                id="cash1"
                                                name="payment"
                                                class="absolute opacity-0"
                                                checked
                                        />
                                        <label for="cash1" class="custom-radio"></label>
                                        <label for="cash1" class="ml-2 flex-grow cursor-pointer">
                      <span class="text-sm font-medium text-gray-700"
                      >Cash</span
                      >
                                        </label>
                                        <div class="flex">
                                            <img
                                                    src="https://zennail23.com/storage/images/news/wise.png"
                                                    alt="Cash"
                                                    class="h-5"
                                            />
                                        </div>
                                    </div>

                                    <!-- Cash option - Not selected -->
                                    <div
                                            class="payment-option relative flex items-center p-4 border rounded-lg cursor-pointer"
                                    >
                                        <input
                                                type="radio"
                                                id="cash2"
                                                name="payment"
                                                class="absolute opacity-0"
                                        />
                                        <label for="cash2" class="custom-radio"></label>
                                        <label for="cash2" class="ml-2 flex-grow cursor-pointer">
                      <span class="text-sm font-medium text-gray-700"
                      >Cash</span
                      >
                                        </label>
                                        <div class="flex">
                                            <img
                                                    src="https://zennail23.com/storage/images/news/wise.png"
                                                    alt="Payment icons"
                                                    class="h-5"
                                            />
                                        </div>
                                    </div>

                                    <!-- Bank Transfer option -->
                                    <div
                                            class="payment-option relative flex items-center p-4 border rounded-lg cursor-pointer"
                                    >
                                        <input
                                                type="radio"
                                                id="bank"
                                                name="payment"
                                                class="absolute opacity-0"
                                        />
                                        <label for="bank" class="custom-radio"></label>
                                        <label for="bank" class="ml-2 flex-grow cursor-pointer">
                      <span class="text-sm font-medium text-gray-700"
                      >Bank Transfer</span
                      >
                                        </label>
                                        <div class="flex">
                                            <img
                                                    src="https://zennail23.com/storage/images/news/wise.png"
                                                    alt="Bank icons"
                                                    class="h-5"
                                            />
                                        </div>
                                    </div>

                                    <!-- Sepa option -->
                                    <div
                                            class="payment-option relative flex items-center p-4 border rounded-lg cursor-pointer"
                                    >
                                        <input
                                                type="radio"
                                                id="sepa"
                                                name="payment"
                                                class="absolute opacity-0"
                                        />
                                        <label for="sepa" class="custom-radio"></label>
                                        <label for="sepa" class="ml-2 flex-grow cursor-pointer">
                      <span class="text-sm font-medium text-gray-700"
                      >Sepa</span
                      >
                                        </label>
                                        <div class="flex">
                                            <img
                                                    src="https://zennail23.com/storage/images/news/wise.png"
                                                    alt="Sepa"
                                                    class="h-5"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Rewards Tab -->
                    <div id="rewards" class="tab-content p-6 bg-gray-50 text-center">
                        <!-- Rewards content would go here -->
                        <div class="w-full sm:w-1/2 mx-auto text-left p-6">
                            <img
                                    src="assets/icons/icon_rewards.svg"
                                    alt="Rewards"
                                    class="mx-auto mb-4"
                            />
                            <h2 class="text-lg font-medium text-black mb-4 text-center">
                                Invite friends and get discounts
                            </h2>
                            <p class="text-gray-600 mb-2">
                                1. Your friends will get €4.00 in Wolt credits when they use
                                your code for each of their first 3 delivery orders.
                            </p>
                            <p class="text-gray-600 mb-2">
                                2. You’ll get €2.00 in Wolt credits for each of your friend’s
                                first 3 delivery orders. You can earn a maximum of €18.00 in
                                credits by inviting your friends to join Wolt.
                            </p>
                            <p class="text-secondary underline mb-2 text-center">How do referral codes work?</p>
                            <div class="w-full max-w-md">
                                <!-- Referral Code Component -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500 mb-2">Your referral code</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xl font-bold tracking-wide" id="referralCode">DFGHJKL23</p>
                                        <button id="copyButton"
                                                class="p-1 rounded hover:bg-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButton = document.getElementById('copyButton');
            const referralCode = document.getElementById('referralCode');

            copyButton.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(referralCode.textContent);

                    const originalSVG = copyButton.innerHTML;
                    copyButton.innerHTML = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                      <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
              `;

                    const parentDiv = referralCode.closest('.bg-gray-50');
                    parentDiv.classList.add('copy-animation');

                    setTimeout(() => {
                        copyButton.innerHTML = originalSVG;
                        parentDiv.classList.remove('copy-animation');
                    }, 1500);

                } catch (err) {
                    console.error('Failed to copy text: ', err);

                    const originalSVG = copyButton.innerHTML;
                    copyButton.innerHTML = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                      <line x1="18" y1="6" x2="6" y2="18"></line>
                      <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
              `;

                    setTimeout(() => {
                        copyButton.innerHTML = originalSVG;
                    }, 1500);
                }
            });

            copyButton.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    copyButton.click();
                }
            });

            copyButton.setAttribute('tabindex', '0');
            copyButton.setAttribute('aria-label', 'Copy referral code');
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
                        avatarContainer.appendChild(img);
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
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[name="payment"]');

            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const paymentOptions = document.querySelectorAll('.payment-option');

                    paymentOptions.forEach(function (option) {
                        option.classList.remove('selected');
                    });

                    if (radio.checked) {
                        const parentDiv = radio.closest('.payment-option');
                        parentDiv.classList.add('selected');
                    }
                });
            });
        });

    </script>

@endsection