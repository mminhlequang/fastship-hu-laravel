@extends('theme::front-end.master2')
@section('style')
    <style>

        .text-lg.font-medium.text-finding {
            position: relative;
            animation: subtle-glow 2s infinite alternate;
        }

        .text-lg.font-medium.text-finding::after {
            content: "...";
            position: absolute;
            animation: dot-typing 1.5s infinite step-end;
        }

        @keyframes subtle-glow {
            to {
                text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
            }
        }

        @keyframes dot-typing {
            0% {
                content: "";
            }
            25% {
                content: ".";
            }
            50% {
                content: "..";
            }
            75% {
                content: "...";
            }
        }

        .pulse-animation-avatar {
            position: absolute;
            width: 120px; /* Tăng kích thước */
            height: 120px;
            border-radius: 50%;
            background-color: #74ca45;
            z-index: 1;
            animation: pulse2 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite; /* Thêm easing để mượt hơn */
            transform: translate(-50%, -50%);
            box-shadow: 0 0 20px rgba(116, 202, 69, 0.5); /* Thêm đổ bóng để đẹp hơn */
        }

        .pulse-animation-avatar:nth-child(2) {
            animation-delay: 0.7s; /* Điều chỉnh delay */
        }

        .pulse-animation-avatar:nth-child(3) {
            animation-delay: 1.4s;
        }

        @keyframes pulse2 {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 0.8; /* Điều chỉnh opacity ban đầu */
            }
            70% {
                opacity: 0.3; /* Giữ opacity cao hơn ở giữa animation */
            }
            100% {
                transform: translate(-50%, -50%) scale(1.5); /* Scale lớn hơn */
                opacity: 0;
            }
        }

        .user-avatar {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e53935;
            border: 3px solid white;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            transform: translate(-50%, -50%);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .map-container {
            position: relative;
            width: 100%;
            height: 80vh;
            overflow: hidden;
        }

        .tracking-info {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pulse-container, .driver-avatar-container {
            position: absolute;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
@endsection

@section('content')
    <main>
        <section class="pb-4 w-full">
            <div id="status"
                 class="py-2 responsive-px shadow-[0px_4px_20px_0px_rgba(0,0,0,0.1)]">
                @include('theme::front-end.ajax.order_status')
            </div>
        </section>

        <div class="map-container relative">
            <div id="map-container" class="w-full h-full"></div>

            <div class="tracking-info">
                <img src="{{ url('assets/icons/icon_map.svg') }}" alt="Driver Avatar"
                     class="w-12 h-12 rounded-full mb-2"/>
                <span class="text-lg font-medium text-finding" id="textStore">Finding you a nearby driver...</span>
                <p class="text-sm text-dark" id="textStoreSM">This may take a few seconds...</p>
            </div>

            <!-- These elements will be positioned properly with JavaScript -->
            <div id="pulse-container" class="absolute left-1/2 top-1/2">
                <div class="pulse-animation-avatar"></div>
                <div class="pulse-animation-avatar"></div>
                <div class="pulse-animation-avatar"></div>
                <div class="user-avatar rounded-full flex items-center justify-center flex-shrink-0">
                    <img
                            src="{{ url(\Auth::guard('loyal_customer')->user()->getAvatarDefault()) }}"
                            alt="KFC Logo"
                            class="w-10 h-10" style="border-radius: 100%;"
                    />
                </div>
            </div>
        </div>
        <form id="submitRatingDriver">
            @csrf
            <div id="sectionOrderStatus">
            </div>
        </form>
        <div id="sectionOrderDriver">
        </div>
    </main>
@endsection
@section('script')
    <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
    <script type="text/javascript">
        const socket = io("https://socket.mminhdev.io.vn", {
            transports: ["websocket"]
        });
        socket.on("connect", () => {
            console.log("Connected:", socket.id);
            let userToken = @json($token);
            let data = {token: userToken};
            console.log("Emitting authenticate_customer with data:", data);
            socket.on('authentication_success', (data) => {
                console.log("authentication_success", data);
            });

            socket.on('disconnect', () => {
                console.log("disconnect");
            });

            socket.on('error', (data) => {
                console.log("error", data);
            });

            socket.on('order_status_updated', (data) => {
                console.log("order_status_updated", data);
                if (data?.isSuccess && data.data) {
                    let resData = data.data;
                    let {process_status, store_status} = data.data;
                    let orderId = '{{ $order->id }}';
                    document.getElementById('textStore').textContent = resData?.processStatus;
                    getOrderStatus(orderId, process_status, store_status);

                } else {
                    console.warn("order_status_updated: Invalid data", data);
                }
            });

            socket.on('order_cancelled', (data) => {
                console.log("order_cancelled", data);
                let orderId = '{{ $order->id }}';
                if (data?.isSuccess && data.data) {
                    getOrderStatus(orderId, 'cancelled', null);
                }
            });
            socket.on('order_cancelled_confirmation', (data) => {
                console.log("order_cancelled_confirmation", data);
                let orderId = '{{ $order->id }}';
                if (data?.isSuccess && data.data) {
                    getOrderStatus(orderId, 'cancelled', null);
                }
            });

            socket.on('order_completed', (data) => {
                console.log("order_completed", data);
                if (data?.isSuccess && data.data) {
                    let orderId = '{{ $order->id }}';
                    if (data?.isSuccess && data.data) {
                        getOrderStatus(orderId, 'completed', null, null, 1);
                    }
                } else {
                    console.warn("order_status_updated: Invalid data", data);
                }
            });

            socket.on('order_completed_confirmation', (data) => {
                console.log("order_completed_confirmation", data);
                if (data.isSuccess && data.data) {

                }
            });

            let currentDriverId = null;

            socket.on('driver_location_update' , (data) => {
                console.log("socket on  driver_location_update", data);
                if (data.isSuccess && data.data) {
                    let resData = data.data;
                    let lat = resData.location?.lat ?? 46.50119;
                    let lng = resData.location?.lng ?? 15.05297;
                    showDriverAndUserWithRoute({lat, lng});
                }
            });

            socket.on('create_order_result', (data) => {
                console.log("create_order_result", data);
                if (data.isSuccess && data.data) {
                    let resData = data.data;
                    toastr.success(resData.process_status ?? 'Store Accepted');
                    if (resData.process_status === "driverAccepted" && resData?.find_driver_status === 'found') {
                        let lat = resData.driverInfo?.location?.lat ?? 46.50119;
                        let lng = resData.driverInfo?.location?.lng ?? 15.05297;
                        let orderId = resData.orderId;
                        currentDriverId = resData.driverInfo?.profile?.id;
                        console.log('currentDriverId', currentDriverId);
                        document.getElementById('textStore').textContent = resData?.processStatus;
                        getOrderStatus(orderId, null, null, 1);
                        showDriverAndUserWithRoute({lat, lng});
                    }
                }
            });

            socket.emit('authenticate_customer', data);

            let id = "{{ \Auth::guard('loyal_customer')->id() }}";
            socket.emit("joinRoom", "customer_" + id);

            let orderData = @json($order);
            socket.emit('create_order', orderData);

            document.addEventListener("click", function (event) {
                const target = event.target;
                if (target && (target.id === "doneBtn")) {
                    event.preventDefault();
                    const panel = document.querySelector(".driver-panel");
                    if (panel) {
                        panel.style.display = "none";
                    }
                }
            });

            document.addEventListener("click", function (event) {
                const target = event.target;
                const doneBtn = target.closest(".doneBtn");
                if (doneBtn) {
                    event.preventDefault();
                    const panel = document.querySelector(".driver-panel");
                    if (panel) {
                        panel.style.display = "none";
                    }
                }
            });

            function getOrderStatus(orderId, processStatus = null, storeStatus = null, isDriver = null, isRating = null) {
                const params = new URLSearchParams({
                    id: orderId,
                });

                if (processStatus) params.append('process_status', processStatus);
                if (storeStatus) params.append('store_status', storeStatus);
                if (isDriver) params.append('is_driver', isDriver);
                if (isRating) params.append('is_rating', isRating);

                fetch(`{{ url('ajaxFE/getOrderStatus') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        console.log("API update response:", result);
                        if (result.status) {
                            document.getElementById('status').innerHTML = result.view1;
                            document.getElementById('sectionOrderStatus').innerHTML = result.view2;
                            document.getElementById('sectionOrderDriver').innerHTML = result.view3;
                            setTimeout(() => {
                                initSubmitRatingDriver();
                                initStarRating();
                            }, 1000);
                        } else {
                            console.error("Update failed:", result.message);
                            alert("Failed to update order status: " + (result.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error("API update error:", error);
                        toastr.error("An error occurred while updating the order.");
                    });
            }
        });


        let map;
        let driverMarker, driverUserRouteLine, storeUserRouteLine;
        let driverPulseContainer, driverAvatarContainer;
        const userLatLng = {lat: {{ $order->lat ?? 47.50300 }}, lng: {{ $order->lng ?? 17.05000 }}};
        const storeLatLng = {
            lat: {{ optional($order->store)->lat ?? 47.50300 }},
            lng: {{ optional($order->store)->lng ?? 17.05000 }}
        };
        const storeAvatarUrl = "{{ optional($order->store)->avatar_image ? url(optional($order->store)->avatar_image) : url('images/partner.png') }}";


        function showDriverAndUserWithRoute(driverLatLng) {
            if (driverMarker) map.removeObject(driverMarker);
            if (driverUserRouteLine) map.removeObject(driverUserRouteLine);
            if (driverPulseContainer) driverPulseContainer.remove();
            if (driverAvatarContainer) driverAvatarContainer.remove();

            driverMarker = new H.map.Marker(driverLatLng);
            map.addObject(driverMarker);

            driverPulseContainer = createDriverPulseContainer(driverLatLng);
            driverAvatarContainer = createDriverAvatarContainer(driverLatLng);

            drawStoreRoute();
            updateDriverAvatarPosition(driverAvatarContainer, driverLatLng);
            positionUserAvatar();
            positionStoreAvatar();

            map.setCenter(driverLatLng);
            map.setZoom(15);

        }

        function drawStoreRoute() {
            if (storeUserRouteLine) {
                map.removeObject(storeUserRouteLine);
            }

            const existingMarkers = map.getObjects().filter(obj => obj instanceof H.map.Marker);
            existingMarkers.forEach(marker => {
                if (marker.getGeometry().lat === storeLatLng.lat &&
                    marker.getGeometry().lng === storeLatLng.lng) {
                    map.removeObject(marker);
                }
            });

            @if(!empty($order->ship_polyline))
            const polyline = H.geo.LineString.fromFlexiblePolyline("{{ $order->ship_polyline }}");
            storeUserRouteLine = new H.map.Polyline(polyline, {
                style: {
                    lineWidth: 4,
                    strokeColor: 'rgb(116,202,69)'
                }
            });
            @else
            const lineString = new H.geo.LineString();
            lineString.pushPoint(userLatLng);
            lineString.pushPoint(storeLatLng);
            storeUserRouteLine = new H.map.Polyline(lineString, {
                style: {
                    lineWidth: 4,
                    strokeColor: 'rgb(116,202,69)'
                }
            });
            @endif

            map.addObject(storeUserRouteLine);

            const storeMarker = new H.map.Marker(storeLatLng, {
                icon: new H.map.Icon("{{ url('images/store-marker.png') }}", {size: {w: 40, h: 40}})
            });
            map.addObject(storeMarker);
        }

        function createDriverPulseContainer(position) {
            const avatarUrl = "{{ url('images/driver.png') }}";
            const container = document.createElement('div');
            container.className = 'pulse-container absolute';
            container.innerHTML = `
        <div class="pulse-animation-avatar"></div>
        <div class="pulse-animation-avatar"></div>
        <div class="pulse-animation-avatar"></div>
        <div class="user-avatar rounded-full flex items-center justify-center flex-shrink-0">
            <img src="${avatarUrl}"
                 alt="Driver Avatar"
                 class="w-10 h-10 rounded-full" />
        </div>
    `;
            document.getElementById("map-container").parentElement.appendChild(container);
            updatePulsePosition(container, position);
            return container;
        }

        function updatePulsePosition(container, latLng) {
            const pixelCoords = map.geoToScreen(latLng);
            if (pixelCoords && container) {
                container.style.left = `${pixelCoords.x}px`;
                container.style.top = `${pixelCoords.y}px`;
            }
        }

        function positionStoreAvatar() {
            const pixelCoords = map.geoToScreen(storeLatLng);
            const storePulseContainer = document.getElementById("store-pulse-container");

            if (!storePulseContainer) {
                const container = document.createElement('div');
                container.id = 'store-pulse-container';
                container.className = 'absolute';
                container.innerHTML = `
            <div class="pulse-animation-avatar" style="background-color: #4285F4;"></div>
            <div class="pulse-animation-avatar" style="background-color: #4285F4;"></div>
            <div class="pulse-animation-avatar" style="background-color: #4285F4;"></div>
            <div class="user-avatar rounded-full flex items-center justify-center flex-shrink-0">
                <img src="${storeAvatarUrl}"
                     alt="Store Avatar"
                     class="w-10 h-10 rounded-full border-2 border-white" />
            </div>
        `;
                document.getElementById("map-container").parentElement.appendChild(container);
            }

            if (pixelCoords) {
                document.getElementById("store-pulse-container").style.left = `${pixelCoords.x}px`;
                document.getElementById("store-pulse-container").style.top = `${pixelCoords.y}px`;
            }
        }

        function positionUserAvatar() {
            const pixelCoords = map.geoToScreen(userLatLng);
            const userPulseContainer = document.getElementById("pulse-container");
            if (userPulseContainer && pixelCoords) {
                userPulseContainer.style.left = `${pixelCoords.x}px`;
                userPulseContainer.style.top = `${pixelCoords.y}px`;
            }
        }


        function createDriverAvatarContainer(position) {
            const avatarUrl = "{{ url('images/driver.png') }}";
            const container = document.createElement('div');
            container.className = 'driver-avatar-container absolute';
            container.innerHTML = `
        <div class="user-avatar rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
            <img src="${avatarUrl}"
                 alt="Driver Avatar"
                 class="w-12 h-12 rounded-full border-2 border-white" />
        </div>
    `;
            document.getElementById("map-container").parentElement.appendChild(container);
            updateDriverAvatarPosition(container, position);
            return container;
        }

        function updateDriverAvatarPosition(container, latLng) {
            const pixelCoords = map.geoToScreen(latLng);
            if (pixelCoords && container) {
                container.style.left = `${pixelCoords.x - 24}px`;
                container.style.top = `${pixelCoords.y - 24}px`;
            }
        }

        function initMapFindDriver() {
            const platform = new H.service.Platform({
                apikey: "HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0"
            });

            const defaultLayers = platform.createDefaultLayers();
            map = new H.Map(
                document.getElementById("map-container"),
                defaultLayers.vector.normal.map,
                {
                    zoom: 15,
                    center: userLatLng
                }
            );

            drawStoreRoute();
            positionUserAvatar();
            positionStoreAvatar();

            map.addEventListener("mapviewchange", function () {
                positionUserAvatar();
                positionStoreAvatar();
                if (driverMarker && driverPulseContainer) {
                    updatePulsePosition(driverPulseContainer, driverMarker.getGeometry());
                }
            });

            window.addEventListener("resize", () => map.getViewPort().resize());
            const behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
            const ui = H.ui.UI.createDefault(map, defaultLayers);
        }

        window.onload = initMapFindDriver;

        function initSubmitRatingDriver() {
            const form = document.querySelector('#submitRatingDriver');
            if (!form) return;
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                document.querySelector('.loading')?.classList.add('loader');

                const formData = new FormData(form);

                fetch("{{ url('ajaxFE/submitRatingDriver') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            const panel = document.querySelector(".driver-panel");
                            if (panel) {
                                panel.style.display = "none";
                            }
                            document.getElementById('sectionOrderStatus').innerHTML = data.view;
                            document.getElementById('textStore').textContent = 'Thank you for your order!';
                            document.getElementById('textStoreSM').textContent = 'The store has prepared your food. You can come pick it up anytime.';
                        } else {
                            toastr.error(data.message || "Có lỗi xảy ra!");
                        }

                        document.querySelector('.loading')?.classList.remove('loader');
                    })
                    .catch(() => {
                        document.querySelector('.loading')?.classList.remove('loader');
                        toastr.error("Lỗi hệ thống, vui lòng thử lại.");
                    });
            });

        }


        function initStarRating() {
            const starContainer = document.querySelector('.driver-panel .flex.justify-between.items-center.py-8.border-b');

            if (!starContainer) {
                console.warn('Không tìm thấy container của star rating');
                return;
            }

            const stars = starContainer.querySelectorAll('span');
            const inputRating = document.getElementById('inputRating');
            let currentRating = parseInt(inputRating.value || 0);

            stars.forEach((star, index) => {
                star.style.cursor = 'pointer';

                star.addEventListener('click', function () {
                    currentRating = index + 1;
                    inputRating.value = currentRating;
                    updateStars();
                });

                star.addEventListener('mouseenter', function () {
                    highlightStars(index + 1);
                });

                star.addEventListener('mouseleave', function () {
                    updateStars();
                });
            });

            function updateStars() {
                stars.forEach((star, index) => {
                    const path = star.querySelector('path');
                    if (path) {
                        path.setAttribute('fill', index < currentRating ? '#F17228' : '#E5E5E5');
                    }
                });
            }

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    const path = star.querySelector('path');
                    if (path) {
                        path.setAttribute('fill', index < rating ? '#F17228' : '#E5E5E5');
                    }
                });
            }

            updateStars();
        }
    </script>

@endsection