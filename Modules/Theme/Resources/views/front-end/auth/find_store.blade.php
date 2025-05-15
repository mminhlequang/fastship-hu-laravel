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

        .driver-avatar {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
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
                <img src="{{ url('assets/icons/icon_map.svg') }}" alt="Driver Avatar"
                     class="w-12 h-12 rounded-full mb-2"/>
                <span class="text-lg font-medium text-finding" id="textStore">The store is preparing the food....</span>
                <p class="text-sm text-gray-200" id="textStoreSM">This may take a few minutes...</p>
            </div>

            <!-- These elements will be positioned properly with JavaScript -->
            <div id="pulse-container" class="absolute left-1/2 top-1/2">
                <div class="pulse-animation-avatar"></div>
                <div class="pulse-animation-avatar"></div>
                <div class="pulse-animation-avatar"></div>
                <div class="driver-avatar rounded-full flex items-center justify-center flex-shrink-0">
                    <img data-src="{{ optional($order->store)->avatar_image }}" alt="KFC Logo"
                         class="w-10 h-10 rounded-full lazyload"/>
                </div>
            </div>
        </div>
        <div id="sectionOrderStatus">
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
                    let orderId = '{{ $order->id }}';
                    let {process_status, store_status} = data.data;
                    getOrderStatus(orderId, process_status, store_status);

                } else {
                    console.warn("order_status_updated: Invalid data", data);
                }
            });

            socket.on('order_completed', (data) => {
                console.log("order_completed", data);
                if (data?.isSuccess && data.data) {
                    let orderId = '{{ $order->id }}';
                    getOrderStatus(orderId, null, null);
                    document.getElementById('textStore').textContent = 'Thank you for your order!';
                    document.getElementById('textStoreSM').textContent = 'The store has prepared your food. You can come pick it up anytime.';
                } else {
                    console.warn("order_status_updated: Invalid data", data);
                }
            });

            socket.on('order_cancelled', (data) => {
                console.log("order_cancelled");
                if (data?.isSuccess && data.data) {
                    let orderId = '{{ $order->id }}';
                    getOrderStatus(orderId, null, null);
                } else {
                    console.warn("order_cancelled: Invalid data", data);
                }
            });

            socket.on('order_cancelled_confirmation', (data) => {
                console.log("order_cancelled_confirmation");
            });

            socket.on('order_status_updated', (data) => {
                console.log("order_status_updated", data);
                if (data?.isSuccess && data.data) {
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

            socket.on('create_order_result', (data) => {
                console.log("create_order_result", data);
                if (data.isSuccess && data.data) {
                    let orderId = '{{ $order->id }}';
                    let {process_status, store_status} = data.data;
                    let processStatusT = data.data.process_status ?? 'Store Accepted';
                    let processText = '';
                    if (processStatusT == 'storeAccepted') processText = 'Store is accept order for you';
                    else processText = 'The store is preparing the food.';
                    toastr.success(processText);
                    document.getElementById('textStore').textContent = processText;
                    getOrderStatus(orderId, process_status, store_status);
                }
            });

            socket.emit('authenticate_customer', data);

            let id = "{{ \Auth::guard('loyal_customer')->id() }}";
            socket.emit("joinRoom", "customer_" + id);

            let orderData = @json($order);
            socket.emit('create_order', orderData);

        });

        document.addEventListener("click", function (event) {
            if (event.target && event.target.id === "doneBtn") {
                event.preventDefault();
                const panel = document.querySelector(".driver-panel");
                if (panel) {
                    panel.style.display = "none";
                    const dataId = event.target.getAttribute('data-id');
                    if (dataId) {
                        socket.emit('complete_order', { orderId: dataId });
                    } else {
                        console.warn("Button doneBtn không có data-id, không emit socket.");
                    }
                }
            }
        });


        function getOrderStatus(orderId, processStatus = null, storeStatus = null) {
            const params = new URLSearchParams({
                id: orderId,
            });

            if (processStatus) params.append('process_status', processStatus);
            if (storeStatus) params.append('store_status', storeStatus);

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


    </script>
    <script type="text/javascript">
        function initMapFindStore() {
            const platform = new H.service.Platform({
                apikey: "HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0",
            });

            const defaultLayers = platform.createDefaultLayers();

            const map = new H.Map(
                document.getElementById("map-container"),
                defaultLayers.vector.normal.map,
                {
                    zoom: 15,
                    center: {
                        lat: {{ optional($order->store)->lat ?? 47.50119 }},
                        lng: {{ optional($order->store)->lng ?? 19.05297 }} },
                }
            );

            window.addEventListener("resize", () => map.getViewPort().resize());

            const behavior = new H.mapevents.Behavior(
                new H.mapevents.MapEvents(map)
            );

            const ui = H.ui.UI.createDefault(map, defaultLayers);

            function positionDriverAvatar() {
                const pixelCoords = map.geoToScreen({
                    lat: {{ optional($order->store)->lat ?? 47.50119 }},
                    lng: {{ optional($order->store)->lng ?? 19.05297 }}});

                const pulseContainer = document.getElementById("pulse-container");
                pulseContainer.style.left = `${pixelCoords.x}px`;
                pulseContainer.style.top = `${pixelCoords.y}px`;
            }

            positionDriverAvatar();

            map.addEventListener("mapviewchange", positionDriverAvatar);
        }

        window.onload = initMapFindStore;
    </script>
@endsection