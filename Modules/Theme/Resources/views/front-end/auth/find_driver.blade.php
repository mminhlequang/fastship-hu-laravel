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
                <img
                        src="{{ url('assets/icons/icon_map.svg') }}"
                        alt="Driver Avatar"
                        class="w-12 h-12 rounded-full mb-2"
                />
                <span class="text-lg font-medium text-finding"
                >Finding you a nearby driver...</span
                >
                <p class="text-sm text-gray-200">This may take a few seconds...</p>
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

        @include('theme::front-end.ajax.order_driver')
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
            let data = {token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3plbm5haWwyMy5jb20vYXBpL3YxL2xvZ2luIiwiaWF0IjoxNzQ3MTQ2OTQzLCJleHAiOjE3NDc3NTE3NDMsIm5iZiI6MTc0NzE0Njk0MywianRpIjoiMVRnc0dLZThET1d5Z2xWTiIsInN1YiI6IjUiLCJwcnYiOiIxZDBhMDIwYWNmNWM0YjZjNDk3OTg5ZGYxYWJmMGZiZDRlOGM4ZDYzIiwiaWQiOjUsInVpZCI6IndqakpOTkx3ZFdOeHZ4YXVZbW1hOWtEMExnaDIiLCJuYW1lIjoiRGluaCBEdW9uZyIsInBob25lIjoiKzg0OTY0NTQxMzQwIiwidHlwZSI6MX0.TayVFdGGY4LPjrRKRHnX-yhH61HIBv20CJBn_oJhfFU'};
            console.log("Emitting authenticate_customer with data:", data);
            socket.on('authentication_success', (data) => {
                console.log("authentication_success", data);
            });

            socket.on('disconnect', () => {
                console.log("disconnect");
            });

            socket.on('error', (data) => {
                console.log("error");
            });

            socket.on('order_status_updated', (data) => {
                console.log("order_status_updated", data);
                if (data?.isSuccess && data.data) {
                    showDriverAndUserWithRoute({lat: 46.50119, lng: 15.05297});
                    const {processStatus, storeStatus} = data.data;
                    let orderId = '{{ $order->id }}';
                    fetch('/api/v1/order/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: orderId,
                            process_status: processStatus,
                            store_status: storeStatus
                        })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log("API update response:", result);
                        })
                        .catch(error => {
                            console.error("API update error:", error);
                        });
                } else {
                    console.warn("order_status_updated: Invalid data", data);
                }
            });

            socket.on('order_cancelled', (data) => {
                console.log("order_cancelled", data);
            });
            socket.on('order_cancelled_confirmation', (data) => {
                console.log("order_cancelled_confirmation", data);
            });

            socket.on('order_completed', (data) => {
                console.log("order_completed", data);
                if (data?.isSuccess && data.data) {
                    let orderId = '{{ $order->id }}';
                    fetch('/api/v1/order/complete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: orderId
                        })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log("API update response:", result);
                        })
                        .catch(error => {
                            console.error("API update error:", error);
                        });
                } else {
                    console.warn("order_status_updated: Invalid data", data);
                }
            });

            socket.on('order_completed_confirmation', (data) => {
                console.log("order_completed_confirmation", data);
            });

            socket.on('create_order_result', (data) => {
                console.log("create_order_result", data);
                if (data.isSuccess) {
                    toastr.success(data.data.process_status ?? 'Store Accepted');
                }
            });

            socket.emit('authenticate_customer', data);

            let orderData = @json($order);
            socket.emit('create_order', orderData);

        });


        let map;
        let driverMarker, routeLine;
        let driverPulseContainer;
        const userLatLng = {lat: {{ $order->lat ?? 47.50300 }}, lng: {{ $order->lng ?? 17.05000 }}};

        function showDriverAndUserWithRoute(driverLatLng) {
            if (driverMarker) map.removeObject(driverMarker);
            if (routeLine) map.removeObject(routeLine);

            driverMarker = new H.map.Marker(driverLatLng, {visibility: false});
            map.addObject(driverMarker);

            if (!driverPulseContainer) {
                driverPulseContainer = createDriverPulseContainer(driverLatLng);
            }

            updatePulsePosition(driverPulseContainer, driverLatLng);
            positionUserAvatar();

            const lineString = new H.geo.LineString();
            lineString.pushPoint(userLatLng);
            lineString.pushPoint(driverLatLng);

            routeLine = new H.map.Polyline(lineString, {
                style: {
                    lineWidth: 4,
                    strokeColor: 'rgb(116,202,69)'
                }
            });

            map.addObject(routeLine);

            map.getViewModel().setLookAtData({
                bounds: routeLine.getBoundingBox()
            });
        }

        function createDriverPulseContainer(position) {
            const container = document.createElement('div');
            container.className = 'pulse-container absolute';
            container.innerHTML = `
        <div class="pulse-animation-avatar"></div>
        <div class="pulse-animation-avatar"></div>
        <div class="pulse-animation-avatar"></div>
        <div class="user-avatar rounded-full flex items-center justify-center flex-shrink-0">
            <img src="https://upload.wikimedia.org/wikipedia/sco/thumb/b/bf/KFC_logo.svg/1024px-KFC_logo.svg.png"
                 alt="Driver Avatar"
                 class="w-10 h-10 rounded-full" />
        </div>
    `;
            document.getElementById("map-container").parentElement.appendChild(container);
            return container;
        }

        function updatePulsePosition(container, latLng) {
            const pixelCoords = map.geoToScreen(latLng);
            container.style.left = `${pixelCoords.x}px`;
            container.style.top = `${pixelCoords.y}px`;
        }

        function positionUserAvatar() {
            const pixelCoords = map.geoToScreen(userLatLng);
            const userPulseContainer = document.getElementById("pulse-container");
            if (userPulseContainer) {
                userPulseContainer.style.left = `${pixelCoords.x}px`;
                userPulseContainer.style.top = `${pixelCoords.y}px`;
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

            positionUserAvatar();

            map.addEventListener("mapviewchange", function () {
                positionUserAvatar();
                if (driverMarker) {
                    updatePulsePosition(driverPulseContainer, driverMarker.getGeometry());
                }
            });

            window.addEventListener("resize", () => map.getViewPort().resize());
            const behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
            const ui = H.ui.UI.createDefault(map, defaultLayers);
        }

        window.onload = initMapFindDriver;

    </script>

@endsection