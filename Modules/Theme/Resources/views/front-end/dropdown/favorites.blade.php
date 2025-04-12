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
        <button id="close-favorite" class="text-gray-500" >
            <img src="{{ url('assets/icons/cart/close.svg') }}">
        </button>
    </div>

    <div class="p-4 max-h-[500px] overflow-y-auto">
        <div class="bg-gray-50 p-4 rounded-lg">
            @php
                $ids = \DB::table('products_favorite')->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->pluck('product_id')->toArray();
                $products = \App\Models\Product::whereIn('id', $ids)->whereNull('deleted_at')->select(['id', 'name', 'image', 'price'])->get();
            @endphp

            <!-- Product item -->
            @include('theme::front-end.dropdown.favorites_inner')

        </div>
        <div class="text-center text-secondary">
            <a href="{{ url('my-wishlist-product') }}" class="text-sencondary underline">All view</a>
        </div>
    </div>
</div>

<script type="text/javascript">
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