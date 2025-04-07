<style>
    .notification-dot {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background-color: #ff6b35;
        border-radius: 50%;
    }

    .notification-panel {
        position: absolute;
        top: calc(100%);
        right: -100px;
        width: 380px;
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        z-index: 100;
        overflow: hidden;
    }

    @media (max-width: 800px) {
        .notification-panel {
            position: fixed; /* Fix the panel to the screen */
            top: 0; /* Adjust to appear just below the notification icon */
            left: 50%; /* Center horizontally */
            transform: translateX(-50%); /* Perfectly center it */
            width: 95%; /* You can adjust this to a percentage or any fixed value */
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 100;
            overflow: hidden;
        }

    }

    .notification-panel::before {
        content: "";
        position: absolute;
        top: -8px;
        right: 162px;
        width: 16px;
        height: 16px;
        background-color: white;
        transform: rotate(45deg);
        border-radius: 2px;
    }
</style>
<!-- Notification dropdown -->
<div id="notification-dropdown" class="notification-panel hidden">
    <div class="flex items-center justify-between p-4 border-b">
        <div class="flex items-center">
            <img
                    src="{{ url('assets/icons/bell.svg') }}"
                    class="m-auto"
            />&nbsp;
            <h3 class="font-medium">Notification</h3>
        </div>
        <button id="close-notification" class="text-gray-500">
            <img src="{{ url('assets/icons/cart/close.svg') }}">
        </button>
    </div>

    <div class="p-4 max-h-[500px] overflow-y-auto">
        <h4 class="text-gray-500 mb-4">Today</h4>

        <div class="bg-gray-50 p-4 rounded-lg">
            <!-- Notification item -->
            <div class="mb-4 relative">
                <div class="flex items-start pb-4 border-b">
                    <div
                            class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3"
                    >
                        <img
                                src="{{ url('assets/icons/icon_notify1.svg') }}"
                                class="m-auto"
                        />
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium">30% Special Discount!</h5>
                        <p class="text-gray-500 text-sm">
                            Special promotion only valid today
                        </p>
                    </div>
                </div>
                <span
                        class="absolute top-0 right-0 w-2 h-2 bg-sencondary rounded-full"
                ></span>
            </div>

            <!-- Notification item -->
            <div class="mb-4 relative">
                <div class="flex items-start pb-4 border-b">
                    <div
                            class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3"
                    >
                        <img
                                src="{{ url('assets/icons/icon_notify2.svg') }}"
                                class="m-auto"
                        />
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium">
                            Your Order Has Been Taken by the Driver
                        </h5>
                        <p class="text-gray-500 text-sm">Recently</p>
                    </div>
                </div>
                <span
                        class="absolute top-0 right-0 w-2 h-2 bg-sencondary rounded-full"
                ></span>
            </div>

            <!-- Notification item -->
            <div class="mb-4">
                <div class="flex items-start pb-4 border-b">
                    <div
                            class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3"
                    >
                        <img
                                src="{{ url('assets/icons/icon_notify3.svg') }}"
                                class="m-auto"
                        />
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium">
                            Your Order Has Been Canceled
                        </h5>
                        <p class="text-gray-500 text-sm">19 Jun 2023</p>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="text-gray-500 mb-4 mt-6">Yesterday</h4>

        <div class="bg-gray-50 p-4 rounded-lg">
            <!-- Notification item -->
            <div class="mb-4">
                <div class="flex items-start pb-4 border-b">
                    <div
                            class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3"
                    >
                        <img
                                src="{{ url('assets/icons/icon_notify4.svg') }}"
                                class="m-auto"
                        />
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium">35% Special Discount!</h5>
                        <p class="text-gray-500 text-sm">
                            Special promotion only valid today
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notification item -->
            <div class="mb-4">
                <div class="flex items-start pb-4 border-b">
                    <div
                            class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3"
                    >
                        <img
                                src="{{ url('assets/icons/icon_notify5.svg') }}"
                                class="m-auto"
                        />
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium">Account Setup Successful!!</h5>
                        <p class="text-gray-500 text-sm">
                            Special promotion only valid today
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notification item -->
            <div class="mb-2">
                <div class="flex items-start pb-4 border-b">
                    <div
                            class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3"
                    >
                        <img
                                src="{{ url('assets/icons/icon_notify3.svg') }}"
                                class="m-auto"
                        />
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium">Special Offer! 60% Off</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-secondary">
            <a href="#" class="text-sencondary underline">All view</a>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notificationIcon = document.getElementById("notification-icon");
        const notificationDropdown = document.getElementById(
            "notification-dropdown"
        );
        const closeNotification = document.getElementById("close-notification");

        notificationIcon.addEventListener("click", function (e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle("hidden");
        });

        closeNotification.addEventListener("click", function () {
            notificationDropdown.classList.add("hidden");
        });

        document.addEventListener("click", function (e) {
            if (
                !notificationDropdown.contains(e.target) &&
                e.target !== notificationIcon
            ) {
                notificationDropdown.classList.add("hidden");
            }
        });

        notificationDropdown.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    });
</script>