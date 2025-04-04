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
</style>
<form action="{{ url('search') }}" method="GET">
    <div
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modalOverlay modalOverlayFilter"
    >
        <div
                class="bg-white rounded-xl  w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto shadow-xl"
        >
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-medium">Filter</h2>
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
            <div class="p-4 border-b flex space-x-2 items-center">
                <div class="flex items-center gap-3 w-full">
                    <div
                            class="flex items-center gap-1.5 pl-4 pr-2 rounded-full bg-white shadow md:w-auto md:flex-1"
                    >
              <span>
                <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M3.75 10C3.75 6.54822 6.54822 3.75 10 3.75C13.4518 3.75 16.25 6.54822 16.25 10C16.25 13.4518 13.4518 16.25 10 16.25C6.54822 16.25 3.75 13.4518 3.75 10ZM10 2.25C5.71979 2.25 2.25 5.71979 2.25 10C2.25 14.2802 5.71979 17.75 10 17.75C14.2802 17.75 17.75 14.2802 17.75 10C17.75 5.71979 14.2802 2.25 10 2.25ZM17.5303 16.4697C17.2374 16.1768 16.7626 16.1768 16.4697 16.4697C16.1768 16.7626 16.1768 17.2374 16.4697 17.5303L19.4697 20.5303C19.7626 20.8232 20.2374 20.8232 20.5303 20.5303C20.8232 20.2374 20.8232 19.7626 20.5303 19.4697L17.5303 16.4697Z"
                          fill="#636F7E"
                  ></path>
                </svg>
              </span>
                        <input
                                id="searchInput"
                                type="text"
                                class="w-full md:flex-1 focus:outline-none"
                                placeholder="Search"
                        />
                        <button
                                id="searchButton"
                                class="rounded-full inline-flex items-center py-2.5 px-4 md:px-8 bg-primary text-white hover:bg-primary-700 capitalize text-xs md:text-base"
                        >
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4 border-b">
                <h3 class="font-medium mb-3">Type Food</h3>
                <div class="grid grid-cols-3 gap-2 mb-4" id="typeFood">
                    @foreach(array_slice($categoriesFilter, 0, 20) as $keyC => $itemC)
                        <div title="{{ $itemC }}" data-id="{{ $keyC }}"
                             class="chip px-2 py-1.5 text-center text-sm bg-gray-100 rounded-full cursor-pointer border border-gray-200"
                             data-category="cuisine"
                        >
                            {{ str_limit($itemC, 10, '...') }}
                        </div>
                    @endforeach

                </div>

                <div
                        class="hidden-chips hidden grid grid-cols-3 gap-2 mb-4"
                        id="hiddenMeal"
                >
                    @foreach(array_slice($categoriesFilter, 20) as $keyC => $itemC)
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
                        class="text-secondary text-sm font-medium px-2"
                >
                    + See all filter
                </button>
            </div>

            <!-- Price Range -->
            <div class="p-4 border-b">
                <h3 class="font-medium mb-3">Price</h3>
                <div class="mt-6 mb-1 flex justify-between text-sm">
                    <span id="minPriceDisplay">$ 10.00</span>
                    <span id="maxPriceDisplay">$ 35.00</span>
                </div>
                <div class="relative h-8 mb-4">
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
            </div>

            <!-- Sort By -->
            <div class="p-4 border-b">
                <h3 class="font-medium mb-3">Sort By</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                        <input
                                type="radio"
                                id="recommended"
                                name="sort"
                                class="w-6 h-6 text-primary bg-primary"
                                checked
                        />
                        <label for="recommended" class="ml-2 text-sm">Recommended</label>
                    </div>
                    <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                        <input
                                type="radio"
                                id="deliveryPrice"
                                name="sort"
                                class="w-6 h-6 text-primary bg-primary"
                        />
                        <label for="deliveryPrice" class="ml-2 text-sm">Delivery price</label>
                    </div>
                    <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                        <input
                                type="radio"
                                id="rating"
                                name="sort"
                                class="w-6 h-6 text-primary bg-primary"
                        />
                        <label for="rating" class="ml-2 text-sm">Rating</label>
                    </div>
                    <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                        <input
                                type="radio"
                                id="distance"
                                name="sort"
                                class="w-6 h-6 text-primary bg-primary"
                        />
                        <label for="distance" class="ml-2 text-sm">Distance</label>
                    </div>
                    <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                        <input
                                type="radio"
                                id="deliveryTime"
                                name="sort"
                                class="w-6 h-6 text-primary bg-primary"
                        />
                        <label for="deliveryTime" class="ml-2 text-sm">Delivery time</label>
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
                this.classList.toggle("bg-green-50");
                this.classList.toggle("bg-gray-100");
                this.classList.toggle("border-primary");
                this.classList.toggle("border-gray-200");
                this.classList.toggle("text-primary");
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

