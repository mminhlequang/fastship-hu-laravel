<style>
    .favorite-dot {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background-color: #ff6b35;
        border-radius: 50%;
    }

    .favorite-panel {
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
        .favorite-panel {
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

    .favorite-panel::before {
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
<div id="favorite-dropdown" class="favorite-panel hidden">
    <div class="flex items-center justify-between p-4 border-b">
        <div class="flex items-center">
            <img
                    src="{{ url('assets/icons/heart.svg') }}"
                    class="m-auto"
            />&nbsp;
            <h3 class="font-medium">Add to Wishlist</h3>
        </div>
        <button id="close-favorite" class="text-gray-500">
            <img src="{{ url('assets/icons/cart/close.svg') }}">
        </button>
    </div>

    <div class="p-4 max-h-[500px] overflow-y-auto">
        <div class="bg-gray-50 p-4 rounded-lg">
            <!-- Notification item -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-3 mb-4">
                <div class="flex flex-col md:flex-row justify-between items-start gap-3 w-full">
                    <!-- Left: Image + Info -->
                    <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                        <!-- Image -->
                        <img
                                onerror="this.onerror=null; this.src='http://lv.fastship.org/images/no-image.png'"
                                src="http://lv.fastship.org/images/no-image.png"
                                class="w-9 h-9 object-cover"
                                alt="Matcha coffee"
                        >

                        <!-- Text + Price -->
                        <div class="flex flex-col gap-1 w-full">
                            <p class="text-[#14142A] text-sm md:text-base">Matcha coffee</p>
                            <div class="flex ">
                                <p class="text-base text-black">8.00 €</p>&nbsp;
                                <p class="text-base text-secondary">64.00 €</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Delete button -->
                    <div class="cursor-pointer deleteFavorite md:ml-auto" data-id="13">
                        <img src="{{ url('assets/icons/cart/close.svg') }}" alt="Delete">
                    </div>
                </div>
            </div>



        </div>
        <div class="text-center text-secondary">
            <a href="{{ url('my-wishlist-product') }}" class="text-sencondary underline">All view</a>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const favoriteIcon = document.getElementById("favorite-icon");
        const favoriteDropdown = document.getElementById(
            "favorite-dropdown"
        );
        const notificationDropdown = document.getElementById(
            "notification-dropdown"
        );
        const closeNotification = document.getElementById("close-favorite");

        favoriteIcon.addEventListener("click", function (e) {
            e.stopPropagation();
            favoriteDropdown.classList.toggle("hidden");
            notificationDropdown.classList.add("hidden");
        });

        closeNotification.addEventListener("click", function () {
            favoriteDropdown.classList.add("hidden");
        });

        document.addEventListener("click", function (e) {
            if (
                !favoriteDropdown.contains(e.target) &&
                e.target !== favoriteIcon
            ) {
                favoriteDropdown.classList.add("hidden");
            }
        });

        favoriteDropdown.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    });
</script>