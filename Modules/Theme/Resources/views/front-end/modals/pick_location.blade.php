<style>
    /* Custom marker style */
    .custom-marker {
        width: 24px;
        height: 24px;
    }

    /* Autocomplete dropdown style */
    .autocomplete-results {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        width: 100%;
    }

    .autocomplete-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    .autocomplete-item:hover {
        background-color: #f3f4f6;
    }

    @keyframes ping-custom {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
</style>

<!-- Location Modal -->
<div id="locationModal"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayLocation z-99">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <button id="backBtn" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h3 class="text-lg font-medium">Where to ship?</h3>
            <div class="w-6"></div> <!-- Empty div for spacing -->
        </div>

        <!-- Map Container -->
        <div class="relative">
            <div id="mapContainer" class="h-64 w-full"></div>

            <!-- Search Box -->
            <div class="absolute top-4 left-0 right-0 mx-4">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="flex items-center px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="searchInput" type="text" placeholder="Search location"
                               class="ml-2 w-full outline-none text-sm" autocomplete="off"/>
                    </div>
                </div>
                <!-- Autocomplete Results -->
                <div id="autocompleteResults" class="autocomplete-results hidden mt-1"></div>
            </div>
        </div>

        <!-- Selected Location -->
        <div class="p-4">
            <div id="selectedLocationDisplay" class="mb-4">
                <div class="flex items-start mb-2">
                    <div class="bg-secondary text-white rounded-full p-1 mr-3 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p id="addressLine1" class="font-medium">179 Sampson Street, Georgetown, CO 80444</p>
                        <p id="addressLine2" class="text-sm text-gray-500">179 Sampson Street, Georgetown, CO 80444</p>
                    </div>
                </div>
            </div>

            <!-- Recent Locations -->
        {{--            <div class="mb-4">--}}
        {{--                <div class="flex items-start mb-2">--}}
        {{--                    <div class="bg-secondary text-white rounded-full p-1 mr-3 mt-1">--}}
        {{--                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
        {{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />--}}
        {{--                        </svg>--}}
        {{--                    </div>--}}
        {{--                    <div>--}}
        {{--                        <p class="font-medium">179 Sampson Street, Georgetown, CO 80444</p>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        <!-- Recent Locations (dynamic) -->
            <div id="recentLocationsContainer" class="mb-4"></div>

            <!-- Action Button -->
            <button id="pickLocationBtn"
                    class="w-full bg-secondary hover:bg-secondary-700 text-white py-3 rounded-md font-medium">
                Pick location
            </button>
        </div>
    </div>
</div>

<!-- Custom Orange Marker SVG -->
<div id="customMarker" class="relative w-6 h-6" style="display: none;">
    <!-- Pulse effect -->
    <span class="absolute inline-flex h-full w-full rounded-full bg-secondary-700 opacity-75 animate-[ping-custom_1.5s_linear_infinite]"></span>
    <!-- Actual marker image -->
    <img src="{{ url('assets/icons/cart/addr.svg') }}" class="relative w-6 h-6 z-10"/>
</div>

<script type="text/javascript" src="{{ url('plugins/js.cookie.min.js') }}"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
<link rel="stylesheet" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css"/>
<script type="text/javascript">
    const openModalLocationBtn = document.getElementById('openModalLocationBtn');
    const locationModal = document.getElementById('locationModal');
    const backBtn = document.getElementById('backBtn');
    const pickLocationBtn = document.getElementById('pickLocationBtn');
    const autocompleteResultsDiv = document.getElementById('autocompleteResults');

    openModalLocationBtn.addEventListener('click', () => {
        locationModal.classList.remove('hidden');
        initializeMap();
    });

    backBtn.addEventListener('click', () => {
        locationModal.classList.add('hidden');
    });

    pickLocationBtn.addEventListener('click', () => {
        locationModal.classList.add('hidden');
        console.log('Picked location:', selectedLatLng.lat, selectedLatLng.lng);
        console.log('Picked address:', selectedAddress);
        document.getElementById('inputLat').value = selectedLatLng.lat;
        document.getElementById('inputLng').value = selectedLatLng.lng;
        document.getElementById('inputAddress').value = selectedAddress;
        document.getElementById('textLocation').textContent = selectedAddress;

        let storeId = '{{ $storeId ?? 0 }}';
        let value = $('#inputTip').val();
        let lat = $('#inputLat').val();
        let lng = $('#inputLng').val();
        let type = $('#inputPaymentType').val();
        previewCalculator(storeId, value, lat, lng, type);

    });

    let map;
    let marker;
    let platform;
    let behavior;
    let defaultLat = Cookies.get('lat') ?? 47.50119;
    let defaultLng = Cookies.get('lng') ?? 19.05297;

    let selectedAddress = '';
    let selectedLatLng = {lat: defaultLat, lng: defaultLng};

    function initializeMap() {
        if (map) return;

        platform = new H.service.Platform({
            apikey: 'HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0'
        });

        const defaultLayers = platform.createDefaultLayers();

        map = new H.Map(
            document.getElementById('mapContainer'),
            defaultLayers.vector.normal.map,
            {
                center: {lat: defaultLat, lng: defaultLng},
                zoom: 15,
                pixelRatio: window.devicePixelRatio || 1
            }
        );

        behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

        const ui = H.ui.UI.createDefault(map, defaultLayers);

        addMarkerToMap(defaultLat, defaultLng);

        reverseGeocode(defaultLat, defaultLng);

        enableMarkerDrag();

        setupSearch();

        window.addEventListener('resize', () => {
            map.getViewPort().resize();
        });

        map.addEventListener('tap', function (evt) {
            const position = map.screenToGeo(
                evt.currentPointer.viewportX,
                evt.currentPointer.viewportY
            );

            addMarkerToMap(position.lat, position.lng);
            reverseGeocode(position.lat, position.lng);
        });
    }

    function createCustomMarkerIcon() {
        const imageUrl = '{{ url('assets/icons/cart/addr.svg') }}';

        const icon = new H.map.Icon(imageUrl, {
            size: {w: 24, h: 24},
            anchor: {x: 12, y: 34}
        });

        return icon;
    }


    function addMarkerToMap(lat, lng) {
        if (marker && map) map.removeObject(marker);

        const icon = createCustomMarkerIcon();

        marker = new H.map.Marker({lat, lng}, {
            icon: icon,
            volatility: true
        });

        if (map) map.addObject(marker);

        selectedLatLng = {lat, lng};
    }

    function enableMarkerDrag() {
        if (!map || !marker) return;

        marker.draggable = true;

        map.addEventListener('dragstart', function (ev) {
            const target = ev.target;
            if (target instanceof H.map.Marker) {
                behavior.disable();
            }
        }, false);

        map.addEventListener('dragend', function (ev) {
            const target = ev.target;
            if (target instanceof H.map.Marker) {
                behavior.enable();
                const position = marker.getGeometry();
                reverseGeocode(position.lat, position.lng);
            }
        }, false);

        map.addEventListener('drag', function (ev) {
            const target = ev.target;
            if (target instanceof H.map.Marker) {
            }
        }, false);
    }

    function reverseGeocode(lat, lng) {
        const geocoder = platform.getSearchService();

        geocoder.reverseGeocode({
            at: `${lat},${lng}`,
            limit: 1
        }, (result) => {
            if (result.items && result.items.length > 0) {
                const address = result.items[0].address;
                const formattedAddress = `${address.street || ''} ${address.houseNumber || ''}, ${address.city || ''}, ${address.stateCode || ''} ${address.postalCode || ''}`;

                document.getElementById('addressLine1').textContent = formattedAddress;
                document.getElementById('addressLine2').textContent = formattedAddress;
                selectedAddress = formattedAddress;
            }
        }, (error) => {
            console.error('Reverse geocoding error:', error);
        });
    }

    function setupSearch() {
        const searchInput = document.getElementById('searchInput');
        let debounceTimeout;

        searchInput.addEventListener('input', function () {
            if (debounceTimeout) clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(() => {
                const query = searchInput.value;

                if (query.trim().length > 2) {
                    const center = map.getCenter();

                    const service = platform.getSearchService();

                    service.autosuggest({
                        q: query,
                        at: `${center.lat},${center.lng}`,
                        limit: 5
                    }, (result) => {
                        displayAutocompleteResults(result.items);
                    }, (error) => {
                        console.error('Autocomplete error:', error);
                    });
                } else {
                    autocompleteResultsDiv.classList.add('hidden');
                }
            }, 300);
        });

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const query = searchInput.value;

                if (query.trim() !== '') {
                    searchLocation(query);
                }
            }
        });

        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !autocompleteResultsDiv.contains(e.target)) {
                autocompleteResultsDiv.classList.add('hidden');
            }
        });
    }

    function displayAutocompleteResults(items) {
        autocompleteResultsDiv.innerHTML = '';

        if (items && items.length > 0) {
            autocompleteResultsDiv.classList.remove('hidden');

            items.forEach(item => {
                const resultItem = document.createElement('div');
                resultItem.className = 'autocomplete-item';
                resultItem.textContent = item.title || item.address.label || 'Unknown location';

                resultItem.addEventListener('click', () => {
                    document.getElementById('searchInput').value = item.title || item.address.label || 'Unknown location';
                    autocompleteResultsDiv.classList.add('hidden');

                    if (item.position) {
                        addMarkerToMap(item.position.lat, item.position.lng);
                        map.setCenter({lat: item.position.lat, lng: item.position.lng});
                        map.setZoom(15);
                        reverseGeocode(item.position.lat, item.position.lng);
                    } else if (item.access && item.access.length > 0) {
                        const pos = item.access[0].lat ?
                            {lat: item.access[0].lat, lng: item.access[0].lng} :
                            {lat: item.access[0].latitude, lng: item.access[0].longitude};

                        addMarkerToMap(pos.lat, pos.lng);
                        map.setCenter(pos);
                        map.setZoom(15);
                        reverseGeocode(pos.lat, pos.lng);
                    } else {
                        searchLocation(item.title || item.address.label || 'Unknown location');
                    }
                });

                autocompleteResultsDiv.appendChild(resultItem);
            });
        } else {
            autocompleteResultsDiv.classList.add('hidden');
        }
    }

    function searchLocation(query) {
        const center = map.getCenter();

        const geocoder = platform.getSearchService();

        geocoder.geocode({
            q: query,
            at: `${center.lat},${center.lng}`,
            limit: 1
        }, (result) => {
            if (result.items && result.items.length > 0) {
                const position = result.items[0].position;

                addMarkerToMap(position.lat, position.lng);

                map.setCenter({lat: position.lat, lng: position.lng});
                map.setZoom(15);

                reverseGeocode(position.lat, position.lng);

                autocompleteResultsDiv.classList.add('hidden');
            }
        }, (error) => {
            console.error('Geocoding error:', error);
        });
    }
</script>

<script type="text/javascript">
    function saveToLocalStorage(address, lat, lng) {
        const existing = JSON.parse(
            localStorage.getItem("recentLocations") || "[]"
        );
        const updated = existing.filter((item) => item.address !== address);
        updated.unshift({address, lat, lng});
        if (updated.length > 5) updated.length = 5;
        localStorage.setItem("recentLocations", JSON.stringify(updated));
        renderRecentLocations();
    }

    function renderRecentLocations() {
        const container = document.getElementById("recentLocationsContainer");
        const recent = JSON.parse(
            localStorage.getItem("recentLocations") || "[]"
        );
        container.innerHTML = "";

        recent.forEach((item, index) => {
            const wrapper = document.createElement("div");
            wrapper.className = "flex items-center justify-between mb-2 group";

            const locationDiv = document.createElement("div");
            locationDiv.className =
                "flex items-start cursor-pointer location-item";

            locationDiv.innerHTML = `
        <div class="bg-secondary text-white rounded-full p-1 mr-3 mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="font-medium">${item.address}</p>
        </div>
    `;

            const deleteBtn = document.createElement("button");
            deleteBtn.className =
                "text-secondary hover:text-secondary-700 transition text-sm ml-4";
            deleteBtn.setAttribute("data-index", index);
            deleteBtn.setAttribute("title", "Delete");
            deleteBtn.textContent = "✕";

            locationDiv.addEventListener("click", () => {
                selectedLatLng = {lat: item.lat, lng: item.lng};
                selectedAddress = item.address;
                document.getElementById("addressLine1").textContent = item.address;
                document.getElementById("addressLine2").textContent = item.address;
                addMarkerToMap(item.lat, item.lng);
                map.setCenter({lat: item.lat, lng: item.lng});
                map.setZoom(15);
            });

            deleteBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                const idx = parseInt(e.currentTarget.getAttribute("data-index"));
                const updated = recent.filter((_, i) => i !== idx);
                localStorage.setItem("recentLocations", JSON.stringify(updated));
                renderRecentLocations();
            });

            wrapper.appendChild(locationDiv);
            wrapper.appendChild(deleteBtn);
            container.appendChild(wrapper);
        });
    }

    function reverseGeocode(lat, lng) {
        const geocoder = platform.getSearchService();
        geocoder.reverseGeocode({
            at: `${lat},${lng}`,
            limit: 1
        }, (result) => {
            if (result.items && result.items.length > 0) {
                const address = result.items[0].address;
                const formattedAddress = `${address.street || ''} ${address.houseNumber || ''}, ${address.city || ''}, ${address.stateCode || ''} ${address.postalCode || ''}`.trim();
                document.getElementById('addressLine1').textContent = formattedAddress;
                document.getElementById('addressLine2').textContent = formattedAddress;
                selectedAddress = formattedAddress;
                selectedLatLng = {lat, lng};
                saveToLocalStorage(formattedAddress, lat, lng);
            }
        }, (error) => {
            console.error('Reverse geocoding error:', error);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderRecentLocations();
    });

</script>

