<style>
    input[type="radio"] {
        appearance: none;
        background-color: #fff;
        border: 1px solid #d1d5db;
        border-radius: 50%;
        position: relative;
    }

    input[type="radio"]:checked {
        background-color: #74CA45;
        border-color: #74CA45;
    }

    input[type="radio"]:checked::after {
        content: "✓";
        font-size: 8px;
        color: white;
        font-weight: bold;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }


    #modal-filter .swiper-slide {
        padding-top: 12px;
        padding-bottom: 16px;
        width: 84px !important;
    }

    #modal-filter .chip.active {
        border: 1px solid #E6FBDA !important;
        background-color: #E6FBDA !important;
        color: #538D33 !important;
    }

    #modal-filter .chip.chip-card.active {
        border: 0.5px solid #74CA45 !important;
        background-color: white !important;
        color: #03081F !important;
        box-shadow: 0px 2px 0px 0px #74CA45 !important;
    }


    #modal-filter .swiper-pagination-bullet-active {
        background-color: #74CA45;
    }
</style>
<form id="modal-filter" action="{{ url('search') }}" method="GET">
    <div
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayFilter z-99"
    >
        <div class="bg-white rounded-xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="p-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-medium">Filter</h2>
                    <button onclick="toggleModal('modalOverlayFilter')" id="closeModal"
                            class="text-gray-500 hover:text-gray-700">
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Search -->
                <div  style="border-color: #F8F1F0; height: 52px; border-radius: 56px" class="flex items-center gap-2 p-2 pl-4 border mb-3">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.4697 16.9697C16.7443 16.6951 17.1791 16.6777 17.4736 16.918L17.5303 16.9697L20.5303 19.9697L20.582 20.0264C20.8223 20.3209 20.8049 20.7557 20.5303 21.0303C20.2557 21.3049 19.8209 21.3223 19.5264 21.082L19.4697 21.0303L16.4697 18.0303L16.418 17.9736C16.1777 17.6791 16.1951 17.2443 16.4697 16.9697ZM10 2.75C14.2802 2.75 17.75 6.21979 17.75 10.5C17.75 14.7802 14.2802 18.25 10 18.25C5.71979 18.25 2.25 14.7802 2.25 10.5C2.25 6.21979 5.71979 2.75 10 2.75ZM10 4.25C6.54822 4.25 3.75 7.04822 3.75 10.5C3.75 13.9518 6.54822 16.75 10 16.75C13.4518 16.75 16.25 13.9518 16.25 10.5C16.25 7.04822 13.4518 4.25 10 4.25Z" fill="#636F7E"/>
                    </svg>
                    <input  id="searchInput" type="text" class="flex-1 focus:outline-none" placeholder="Search" >
                    <button
                        id="searchButton"
                        class="rounded-full h-full inline-flex items-center py-2.5 px-4 bg-primary text-white hover:bg-primary-700 capitalize text-sm"
                    >
                        Search
                    </button>
                </div>

                <!-- Sort By -->
                <div class="mb-3">
                    <h3 class="font-medium text-xl mb-3">Sort By</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center bg-gray-50 rounded-lg">
                            <input
                                    type="radio"
                                    id="recommended"
                                    name="sort"
                                    class="w-6 h-6 text-primary bg-primary"
                                    {{ (\Request::get('sort') == 'recommended' || \Request::get('sort') == null) ? 'checked' : '' }}
                            />
                            <label for="recommended" class="ml-2 text-sm">Recommended</label>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-lg">
                            <input
                                    type="radio"
                                    id="deliveryPrice"
                                    name="sort"
                                    class="w-6 h-6 text-primary bg-primary"
                                    {{ \Request::get('sort') == 'deliveryPrice' ? 'checked' : '' }}
                            />
                            <label for="deliveryPrice" class="ml-2 text-sm">Delivery price</label>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-lg">
                            <input
                                    type="radio"
                                    id="rating"
                                    name="sort"
                                    class="w-6 h-6 text-primary bg-primary"
                                    {{ \Request::get('sort') == 'rating' ? 'checked' : '' }}
                            />
                            <label for="rating" class="ml-2 text-sm">Rating</label>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-lg">
                            <input
                                    type="radio"
                                    id="distance"
                                    name="sort"
                                    class="w-6 h-6 text-primary bg-primary"
                                    {{ \Request::get('sort') == 'distance' ? 'checked' : '' }}
                            />
                            <label for="distance" class="ml-2 text-sm">Distance</label>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-lg">
                            <input
                                    type="radio"
                                    id="deliveryTime"
                                    name="sort"
                                    class="w-6 h-6 text-primary bg-primary"
                                    {{ \Request::get('sort') == 'deliveryTime' ? 'checked' : '' }}
                            />
                            <label for="deliveryTime" class="ml-2 text-sm">Delivery time</label>
                        </div>
                    </div>
                </div>


                <div class="border-b">
                    <h3 class="font-medium text-xl">Type Food</h3>


                    <!-- fake data -->
                    <div class="swiper filter-categories-slider">
                        <div class="swiper-wrapper">
                            @foreach(array_slice($categoriesFilter, 5) as $keyC => $itemC)
                                <div class="swiper-slide">
                                    <div style="padding: 6px;" data-id="{{ $keyC }}" class="card-base relative rounded-2xl bg-white flex flex-col gap-1 chip chip-card">
                                        <img data-src="https://res.cloudinary.com/vuongute/image/upload/v1748157761/Frame_1618872579_4_cycmic.png" style="aspect-ratio: 72 / 50;" class="lazyload object-cover rounded-md"  onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                                        />

                                        <h3 class="font-medium text-xs line-clamp-1 capitalize">{{ $itemC }}</h3>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="swiper-pagination relative"></div>
                    </div>

                    <div class="flex flex-wrap gap-2.5 py-6" id="typeFood">
                        @foreach(array_slice($categoriesFilter, 0, 5) as $keyC => $itemC)
                            <div title="{{ $itemC }}" data-id="{{ $keyC }}"
                                class="chip px-2 py-1.5 text-center text-sm bg-gray-100 rounded-full cursor-pointer border border-gray-200"
                                data-category="cuisine"
                            >
                                {{ str_limit($itemC, 10, '...') }}
                            </div>
                        @endforeach

                    </div>

                    <div
                            class="hidden-chips hidden grid grid-cols-3 gap-2"
                            id="hiddenMeal"
                    >
                        @foreach(array_slice($categoriesFilter, 5) as $keyC => $itemC)
                            <div title="{{ $itemC }}" data-id="{{ $keyC }}"
                                class="chip px-2 py-1.5 text-center text-sm bg-gray-100 rounded-full cursor-pointer border border-gray-200"
                                data-category="meal"
                            >
                                {{ str_limit($itemC, 10, '...') }}
                            </div>
                        @endforeach
                    </div>

                    <button
                            id="seeAllButton"
                            class="text-secondary text-sm font-medium px-2 hidden"
                    >
                        + See all filter
                    </button>
                </div>

                <!-- Price Range -->
                <div class="p-4 border-b">
                    <div class="flex items-center gap-4 mb-4"> 
                        <h3 class="font-medium text-xl flex-1">Price</h3>
                        <div class="flex items-center gap-1">
                            <span id="minPriceDisplay">$ 10.00</span>
                            <span>-</span>
                            <span id="maxPriceDisplay">$ 35.00</span>
                        </div>
                    </div>
                    <div class="flex gap-6 text-sm items-center">
                        <span>$ 0</span>
                        <div class="flex-1 relative h-8">
                            <div
                                class="absolute top-1/2 w-full h-1 bg-gray-200 rounded-full"
                            ></div>
                            <div
                                    id="priceRange"
                                    class="absolute top-1/2 h-1 bg-secondary rounded-full"
                                    style="left: 20%; width: 50%"
                            ></div>
                            <div
                                    id="minThumb"
                                    class="absolute top-1/2 w-5 h-5 bg-white border-2 border-secondary rounded-full cursor-pointer transform -translate-y-1/2 -translate-x-1/2"
                                    style="left: 20%"
                            ></div>
                            <div
                                    id="maxThumb"
                                    class="absolute top-1/2 w-5 h-5 bg-white border-2 border-secondary rounded-full cursor-pointer transform -translate-y-1/2 -translate-x-1/2"
                                    style="left: 70%"
                            ></div>
                        </div>
                        <span>$ 100.00</span>
                    </div>
     
                </div>
            </div>

            <!-- Footer -->
            <div
                    class="p-4 flex justify-between items-center border-t sticky bottom-0 bg-white"
            >
                <div class="flex flex-wrap justify-center items-center space-x-2 w-full">
                    <button
                            onclick="toggleModal('modalOverlayFilter')"
                            class="px-8 py-3 text-sm border border-gray-300 rounded-full "
                    >
                        Cancel
                    </button>
                    <button
                            id="applyButton"
                            class="transition-all bg-primary hover:bg-primary-700 text-white text-sm px-8 py-3 rounded-full"
                    >
                        Apply (1 filter)
                    </button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" >
</form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const seeAllButton = document.getElementById("seeAllButton");
        const hiddenMeal = document.getElementById("hiddenMeal");
        const applyButton = document.getElementById("applyButton");
        const minThumb = document.getElementById("minThumb");
        const maxThumb = document.getElementById("maxThumb");
        const priceRange = document.getElementById("priceRange");
        const minPriceDisplay = document.getElementById("minPriceDisplay");
        const maxPriceDisplay = document.getElementById("maxPriceDisplay");
        const allChips = document.querySelectorAll(".chip");
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");

        let activeFilters = 1;
        let isDraggingMin = false;
        let isDraggingMax = false;
        let minPrice = 1;
        let maxPrice = 100;
        const MAX_PRICE = 100;

        seeAllButton.addEventListener("click", function (e) {
            e.preventDefault();
            hiddenMeal.classList.toggle("hidden");
            seeAllButton.textContent = hiddenMeal.classList.contains("hidden")
                ? "+ See all filter"
                : "- Hide filters";
        });

        allChips.forEach((chip) => {
            chip.addEventListener("click", function () {
                this.classList.toggle("active");
                updateFilterCount();
            });
        });

        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            if (!searchTerm) {
                allChips.forEach((chip) => {
                    chip.style.display = "flex";
                });
            } else {
                allChips.forEach((chip) => {
                    const chipText = chip.textContent.toLowerCase();
                    if (chipText.includes(searchTerm)) {
                        chip.style.display = "flex";
                    } else {
                        chip.style.display = "none";
                    }
                });
            }

            const selectedCategories = [];
            document.querySelectorAll('.chip.active').forEach(chip => {
                selectedCategories.push(chip.getAttribute('data-id'));
            });

            const selectedMinPrice = ((minPrice / 100) * MAX_PRICE).toFixed(2);
            const selectedMaxPrice = ((maxPrice / 100) * MAX_PRICE).toFixed(2);

            const selectedSort = document.querySelector('input[name="sort"]:checked')?.id;

            let url = new URL(window.location.origin + '/search');

            if (selectedCategories.length > 0) {
                url.searchParams.set('categories', selectedCategories.join(','));
            }
            url.searchParams.set('keywords', searchInput.value);
            url.searchParams.set('min_price', selectedMinPrice);
            url.searchParams.set('max_price', selectedMaxPrice);

            if (selectedSort) {
                url.searchParams.set('sort', selectedSort);
            }

            window.location.href = url.toString();
        }

        searchButton.addEventListener("click", performSearch);

        function initPriceSlider() {
            const sliderTrack = document.querySelector(".relative.h-8");

            minThumb.addEventListener("mousedown", function (e) {
                isDraggingMin = true;
                e.preventDefault();
            });

            maxThumb.addEventListener("mousedown", function (e) {
                isDraggingMax = true;
                e.preventDefault();
            });

            document.addEventListener("mousemove", function (e) {
                if (!isDraggingMin && !isDraggingMax) return;

                const sliderRect = sliderTrack.getBoundingClientRect();
                let newPosition =
                    ((e.clientX - sliderRect.left) / sliderRect.width) * 100;

                newPosition = Math.max(0, Math.min(100, newPosition));

                if (isDraggingMin) {
                    if (newPosition < maxPrice) {
                        minPrice = newPosition;
                    } else {
                        minPrice = maxPrice;
                    }
                } else if (isDraggingMax) {
                    if (newPosition > minPrice) {
                        maxPrice = newPosition;
                    } else {
                        maxPrice = minPrice;
                    }
                }

                updatePriceRangeUI();
            });

            document.addEventListener("mouseup", function () {
                isDraggingMin = false;
                isDraggingMax = false;
            });

            minThumb.addEventListener("touchstart", function (e) {
                isDraggingMin = true;
            });

            maxThumb.addEventListener("touchstart", function (e) {
                isDraggingMax = true;
            });

            document.addEventListener("touchmove", function (e) {
                if (!isDraggingMin && !isDraggingMax) return;

                const touch = e.touches[0];
                const sliderRect = sliderTrack.getBoundingClientRect();
                let newPosition =
                    ((touch.clientX - sliderRect.left) / sliderRect.width) * 100;

                newPosition = Math.max(0, Math.min(100, newPosition));

                if (isDraggingMin) {
                    if (newPosition < maxPrice) {
                        minPrice = newPosition;
                    } else {
                        minPrice = maxPrice;
                    }
                } else if (isDraggingMax) {
                    if (newPosition > minPrice) {
                        maxPrice = newPosition;
                    } else {
                        maxPrice = minPrice;
                    }
                }

                updatePriceRangeUI();
                e.preventDefault();
            });

            document.addEventListener("touchend", function () {
                isDraggingMin = false;
                isDraggingMax = false;
            });
        }

        function updatePriceRangeUI() {
            minThumb.style.left = `${minPrice}%`;
            maxThumb.style.left = `${maxPrice}%`;
            priceRange.style.left = `${minPrice}%`;
            priceRange.style.width = `${maxPrice - minPrice}%`;

            const minPriceValue = ((minPrice / 100) * MAX_PRICE).toFixed(2);
            const maxPriceValue = ((maxPrice / 100) * MAX_PRICE).toFixed(2);

            minPriceDisplay.textContent = `$ ${minPriceValue}`;
            maxPriceDisplay.textContent = `$ ${maxPriceValue}`;
        }

        function updateFilterCount() {
            const activeChips = document.querySelectorAll(".chip.active");
            activeFilters = activeChips.length;

            if (activeFilters === 0) {
                applyButton.textContent = "Apply";
            } else {
                applyButton.textContent = `Apply (${activeFilters} ${
                    activeFilters === 1 ? "filter" : "filters"
                })`;
            }
        }
        searchButton.addEventListener('click', function(event) {
            event.preventDefault();
            submitForm();
        });
        applyButton.addEventListener('click', function(event) {
            event.preventDefault();
            submitForm();
        });

        function submitForm(){
            const selectedCategories = [];
            document.querySelectorAll('.chip.active').forEach(chip => {
                selectedCategories.push(chip.getAttribute('data-id'));
            });

            const selectedMinPrice = ((minPrice / 100) * MAX_PRICE).toFixed(2);
            const selectedMaxPrice = ((maxPrice / 100) * MAX_PRICE).toFixed(2);

            const selectedSort = document.querySelector('input[name="sort"]:checked')?.id;

            let url = new URL(window.location.origin + '/search');

            if (selectedCategories.length > 0) {
                url.searchParams.set('categories', selectedCategories.join(','));
            }
            url.searchParams.set('keywords', searchInput.value);
            url.searchParams.set('min_price', selectedMinPrice);
            url.searchParams.set('max_price', selectedMaxPrice);

            if (selectedSort) {
                url.searchParams.set('sort', selectedSort);
            }

            window.location.href = url.toString();
        }

        initPriceSlider();
        updatePriceRangeUI();
        updateFilterCount();
    });
</script>

