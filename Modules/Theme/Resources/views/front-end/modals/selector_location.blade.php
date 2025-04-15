<style>
    body {
        overflow-x: hidden;
    }

    .modalOverlay {
        width: 100vw;
        max-width: 100%;
    }

    .pulse {
        height: 20px;
        width: 20px;
        background-color: #74ca45;
        border-radius: 50%;
        position: absolute;
        transform: translate(-50%, -50%);
        z-index: 100;
        cursor: move;
    }

    .pulse::before {
        content: "";
        position: absolute;
        border: 2px solid #74ca45;
        border-radius: 50%;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(3);
            opacity: 0;
        }
    }

    #mapHome {
        height: 300px;
        width: 100%;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .circle-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(76, 175, 80, 0.2);
        border: 2px solid rgba(76, 175, 80, 0.6);
        pointer-events: none;
        display: none;
    }

    .modal-arrow {
        width: 0;
        height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-bottom: 20px solid white;
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
    }

    .map-container-home {
        position: relative;
        margin-top: 1rem;
        margin-bottom: 1rem;
        height: 300px;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    /* Prevent overflow on small screens */
    body.modal-open {
        overflow: hidden;
    }
</style>

<!-- Location Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayLocationHome"
     id="locationModalHome">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative overflow-hidden">
        <div class="modal-arrow"></div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-medium">Use current location</h2>
            <button id="closeModalHome" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Description -->
        <p class="text-gray-600 text-sm">
            Confirming your location helps us determine availability and delivery fees.
        </p>

        <!-- Search box with autocomplete -->
        <div class="mt-4 mb-2 relative">
            <input
                    autocomplete="off"
                    type="text"
                    id="searchLocation"
                    placeholder="Search for location"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
            />
            <div
                    id="autocompleteResultsHome"
                    class="bg-white shadow-md rounded-md mt-1 hidden absolute z-50 w-full max-h-60 overflow-auto"
            ></div>
        </div>

        <!-- Map container -->
        <div class="map-container-home">
            <div id="mapHome"></div>
            <div class="circle-overlay"></div>
            <div class="pulse" id="locationMarker"></div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center space-x-3">
            <button id="cancelBtn"
                    class="w-full border border-gray-300 hover:bg-primary-700 hover:text-white text-black font-medium py-2 px-4 rounded-lg transition mb-4 closeModalHomeBtnForgot">
                Cancel
            </button>
            <button id="submitBtn"
                    class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition mb-4">
                Submit
            </button>
        </div>
    </div>
</div>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
<link rel="stylesheet" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css"/>
<script type="text/javascript">
    const API_KEY = "HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0";
    let map;
    let currentCoordinates = {lat: '{{ $_COOKIE['lat'] ?? 47.50119 }}', lng: '{{ $_COOKIE['lng'] ?? 19.05297 }}'};
    let isDraggingMarker = false;
    let markerOffsetX = 0;
    let markerOffsetY = 0;

    function initMapHome() {
        const platform = new H.service.Platform({apikey: API_KEY});
        const defaultLayers = platform.createDefaultLayers();
        map = new H.Map(document.getElementById("mapHome"), defaultLayers.vector.normal.map, {
            zoom: 15,
            center: currentCoordinates,
        });
        window.addEventListener("resize", () => map.getViewPort().resize());
        new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
        H.ui.UI.createDefault(map, defaultLayers);
        updateMarkerPosition(currentCoordinates);
        map.addEventListener("tap", function (evt) {
            const coord = map.screenToGeo(evt.currentPointer.viewportX, evt.currentPointer.viewportY);
            currentCoordinates = {lat: coord.lat, lng: coord.lng};
            updateMarkerPosition(currentCoordinates);
        });
        setupDraggableMarker();
        document.body.style.overflowX = 'hidden';
    }

    function updateMarkerPosition(coords) {
        const marker = document.getElementById("locationMarker");
        const point = map.geoToScreen(coords);
        marker.style.left = point.x + "px";
        marker.style.top = point.y + "px";
    }

    function setupDraggableMarker() {
        const marker = document.getElementById("locationMarker");
        marker.addEventListener("mousedown", startDraggingMarker);
        marker.addEventListener("touchstart", startDraggingMarker, {passive: false});
        document.addEventListener("mousemove", dragMarker);
        document.addEventListener("touchmove", dragMarker, {passive: false});
        document.addEventListener("mouseup", stopDraggingMarker);
        document.addEventListener("touchend", stopDraggingMarker);

        function startDraggingMarker(e) {
            e.preventDefault();
            isDraggingMarker = true;
            const rect = marker.getBoundingClientRect();
            if (e.type === "mousedown") {
                markerOffsetX = e.clientX - rect.left - rect.width / 2;
                markerOffsetY = e.clientY - rect.top - rect.height / 2;
            } else {
                const touch = e.touches[0];
                markerOffsetX = touch.clientX - rect.left - rect.width / 2;
                markerOffsetY = touch.clientY - rect.top - rect.height / 2;
            }
        }

        function dragMarker(e) {
            if (!isDraggingMarker) return;
            e.preventDefault();
            const mapContainerHome = document.getElementById("mapHome");
            const rect = mapContainerHome.getBoundingClientRect();
            let clientX, clientY;
            if (e.type === "mousemove") {
                clientX = e.clientX;
                clientY = e.clientY;
            } else {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            }
            if (clientX >= rect.left && clientX <= rect.right && clientY >= rect.top && clientY <= rect.bottom) {
                const x = clientX - rect.left - markerOffsetX;
                const y = clientY - rect.top - markerOffsetY;
                const geoCoord = map.screenToGeo(x, y);
                currentCoordinates = {lat: geoCoord.lat, lng: geoCoord.lng};
                marker.style.left = x + "px";
                marker.style.top = y + "px";
            }
        }

        function stopDraggingMarker() {
            if (isDraggingMarker) {
                isDraggingMarker = false;
                map.setCenter(currentCoordinates);
                updateMarkerPosition(currentCoordinates);
            }
        }
    }

    function setupAutocomplete() {
        const searchInput = document.getElementById("searchLocation");
        const resultsContainer = document.getElementById("autocompleteResultsHome");

        searchInput.addEventListener("input", function () {
            const query = this.value;
            if (query.length < 3) {
                resultsContainer.innerHTML = "";
                resultsContainer.classList.add("hidden");
                return;
            }
            fetch(`https://geocode.search.hereapi.com/v1/geocode?q=${encodeURIComponent(query)}&apiKey=${API_KEY}`)
                .then((response) => response.json())
                .then((data) => {
                    resultsContainer.innerHTML = "";
                    if (data.items && data.items.length > 0) {
                        resultsContainer.classList.remove("hidden");
                        data.items.slice(0, 5).forEach((item) => {
                            const resultItem = document.createElement("div");
                            resultItem.className = "p-2 hover:bg-gray-100 cursor-pointer";
                            resultItem.textContent = item.title;
                            resultItem.addEventListener("click", function () {
                                const position = item.position;
                                currentCoordinates = {lat: position.lat, lng: position.lng};
                                map.setCenter(currentCoordinates);
                                updateMarkerPosition(currentCoordinates);
                                searchInput.value = item.title;
                                resultsContainer.classList.add("hidden");
                            });
                            resultsContainer.appendChild(resultItem);
                        });
                    } else {
                        resultsContainer.classList.add("hidden");
                    }
                }).catch((error) => {
                console.error("Error fetching autocomplete results:", error);
            });
        });

        document.addEventListener("click", function (e) {
            if (e.target !== searchInput && e.target !== resultsContainer) {
                resultsContainer.classList.add("hidden");
            }
        });
    }

    document.querySelectorAll(".changeLocationBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            const modal = document.getElementById("locationModalHome");
            modal.style.display = "flex";
            if (!map) {
                initMapHome();
                setupAutocomplete();
            } else {
                map.setCenter(currentCoordinates);
                updateMarkerPosition(currentCoordinates);
            }
            setTimeout(() => {
                map.getViewPort().resize();
            }, 100);
        });
    });


    document.getElementById("closeModalHome").addEventListener("click", function () {
        document.getElementById("locationModalHome").style.display = "none";
    });

    document.getElementById("cancelBtn").addEventListener("click", function () {
        document.getElementById("locationModalHome").style.display = "none";
    });

    document.getElementById("submitBtn").addEventListener("click", function () {
        getAddressByLatLng(currentCoordinates.lat ?? 47.50119, currentCoordinates.lng ?? 19.05297);
        document.getElementById("locationModalHome").style.display = "none";
    });
</script>
