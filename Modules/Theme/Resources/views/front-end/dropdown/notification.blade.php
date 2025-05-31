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
            <h3 class="font-medium">{{ __('theme::web.header_notification') }}</h3>
        </div>
        <button id="close-notification" class="text-gray-500">
            <img src="{{ url('assets/icons/cart/close.svg') }}">
        </button>
    </div>
    @php
        // Lấy tất cả notification trước đã
        $notifications = \App\Models\Notification::where('user_id', \Auth::guard('loyal_customer')->id())->get();

        // Sắp xếp và group theo ngày
        $grouped = $notifications
            ->sortByDesc('created_at')
            ->groupBy(function ($notification) {
                $created = \Carbon\Carbon::parse($notification->created_at);

                if ($created->isToday()) {
                    return 'Today';
                } elseif ($created->isYesterday()) {
                    return 'Yesterday';
                } else {
                    return 'Earlier';
                }
            });
        // Chuyển về mảng có định dạng 'text' và 'data'
        $groupedFormatted = $grouped->map(function ($items, $key) {
            return [
                'text' => $key,
                'data' => $items->values(), // reset key về dạng chỉ số
            ];
        })->values(); // reset key ngoài cùng
    @endphp
    <div class="p-4 max-h-[500px] overflow-y-auto">
        @forelse($groupedFormatted as $itemN)
            <h4 class="text-gray-500 mb-4">{{ $itemN['text'] ?? '' }}</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
            @foreach($itemN['data'] as $itemI)
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
                                <h5 class="font-medium">{{ $itemI->title }}</h5>
                                <p class="text-gray-500 text-sm">
                                    {{ $itemI->description }}
                                </p>
                            </div>
                        </div>
                        <span
                                class="absolute top-0 right-0 w-2 h-2 bg-sencondary rounded-full"
                        ></span>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="flex flex-col gap-6 justify-items-center justify-center text-center">
                <img src="{{ url('images/no-data.webp') }}" width="190" height="160">
                <h6 class="text-dark font-medium">Nothing to Show</h6>
            </div>
        @endforelse

    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notificationIcon = document.getElementById("notification-icon");
        const cartDropdown = document.getElementById("cart-dropdown");
        const notificationDropdown = document.getElementById(
            "notification-dropdown"
        );
        const favoriteDropdown = document.getElementById(
            "favorite-dropdown"
        );
        const closeNotification = document.getElementById("close-notification");

        notificationIcon.addEventListener("click", function (e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle("hidden");
            favoriteDropdown.classList.add("hidden");
            cartDropdown.classList.add("hidden");
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