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
                 class="py-2 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 shadow-[0px_4px_20px_0px_rgba(0,0,0,0.1)]">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-2 lg:gap-y-0 items-center">
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-primary-700 items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/Paper.svg') }}" class="lazyload"/>
                            <span class="text-sm lg:text-base text-primary-700">Confirming</span>
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/Bag.svg') }}" class="lazyload"/>

                            <span class="text-sm lg:text-base text-[#847D79]">preparing food</span>
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400 hidden lg:block"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/deliver.svg') }}" class="lazyload"/>
                            <span class="text-sm lg:text-base text-[#847D79]">In progress</span>
                        </div>
                        <div class="w-11 border-t-2 border-dashed border-gray-400"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex w-full flex-col border border-[#F1EFE9] items-center gap-2 px-1 py-2 rounded-xl">
                            <img data-src="{{ url('assets/icons/cart/box.svg') }}" class="lazyload"/>
                            <span class="text-sm lg:text-base text-[#847D79]">Delivered</span>
                        </div>
                    </div>
                </div>
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
                <div class="driver-avatar rounded-full flex items-center justify-center flex-shrink-0">
                    <img
                            src="https://upload.wikimedia.org/wikipedia/sco/thumb/b/bf/KFC_logo.svg/1024px-KFC_logo.svg.png"
                            alt="KFC Logo"
                            class="w-10 h-10"
                    />
                </div>
            </div>
        </div>

    </main>
@endsection
@section('script')
    <script type="text/javascript">
        function initMapFindDriver() {
            const platform = new H.service.Platform({
                apikey: "HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0",
            });

            const defaultLayers = platform.createDefaultLayers();

            const map = new H.Map(
                document.getElementById("map-container"),
                defaultLayers.vector.normal.map,
                {
                    zoom: 15,
                    center: { lat: 47.50119, lng: 19.05297 },
                }
            );

            window.addEventListener("resize", () => map.getViewPort().resize());

            const behavior = new H.mapevents.Behavior(
                new H.mapevents.MapEvents(map)
            );

            const ui = H.ui.UI.createDefault(map, defaultLayers);

            function positionDriverAvatar() {
                const pixelCoords = map.geoToScreen({ lat: 47.50119, lng: 19.05297 });

                const pulseContainer = document.getElementById("pulse-container");
                pulseContainer.style.left = `${pixelCoords.x}px`;
                pulseContainer.style.top = `${pixelCoords.y}px`;
            }

            positionDriverAvatar();

            map.addEventListener("mapviewchange", positionDriverAvatar);
        }

        window.onload = initMapFindDriver;
    </script>
@endsection