// Top-rated items data array
const topRatedItems = [
  {
    image: "./assets/images/food_top_rated_img_2.webp",
    title: "Cheese Burger",
    restaurant: "Foodworld",
    restaurantLogo: "./assets/images/food_logo_1.webp",
    discount: "20% off",
    deliveryTime: "15-20 min",
    rating: 5,
    originalPrice: "$3.30",
    discountedPrice: "$2.20",
    distance: "0.44 km",
  },
  {
    image: "./assets/images/food_top_rated_img_2.webp",
    title: "Cheese Burger",
    restaurant: "Foodworld",
    restaurantLogo: "./assets/images/food_logo_1.webp",
    discount: "20% off",
    deliveryTime: "15-20 min",
    rating: 5,
    originalPrice: "$3.30",
    discountedPrice: "$2.20",
    distance: "0.44 km",
  },
  {
    image: "./assets/images/food_top_rated_img_2.webp",
    title: "Cheese Burger",
    restaurant: "Foodworld",
    restaurantLogo: "./assets/images/food_logo_1.webp",
    discount: "20% off",
    deliveryTime: "15-20 min",
    rating: 5,
    originalPrice: "$3.30",
    discountedPrice: "$2.20",
    distance: "0.44 km",
  },
  {
    image: "./assets/images/food_top_rated_img_2.webp",
    title: "Cheese Burger",
    restaurant: "Foodworld",
    restaurantLogo: "./assets/images/food_logo_1.webp",
    discount: "20% off",
    deliveryTime: "15-20 min",
    rating: 5,
    originalPrice: "$3.30",
    discountedPrice: "$2.20",
    distance: "0.44 km",
  },
  {
    image: "./assets/images/food_top_rated_img_2.webp",
    title: "Cheese Burger",
    restaurant: "Foodworld",
    restaurantLogo: "./assets/images/food_logo_1.webp",
    discount: "20% off",
    deliveryTime: "15-20 min",
    rating: 5,
    originalPrice: "$3.30",
    discountedPrice: "$2.20",
    distance: "0.44 km",
  },
  {
    image: "./assets/images/food_top_rated_img_2.webp",
    title: "Cheese Burger",
    restaurant: "Foodworld",
    restaurantLogo: "./assets/images/food_logo_1.webp",
    discount: "20% off",
    deliveryTime: "15-20 min",
    rating: 5,
    originalPrice: "$3.30",
    discountedPrice: "$2.20",
    distance: "0.44 km",
  },
];

document.addEventListener("DOMContentLoaded", function () {
  // Populate top-rated items slides
  const topRatedSliderWrapper = document.querySelector(
    ".top-rated-slider .swiper-wrapper"
  );

  if (topRatedSliderWrapper) {
    let topRatedSlidesHTML = "";

    topRatedItems.forEach((item) => {
      // Generate stars based on rating
      let starsHTML = "";
      for (let i = 0; i < item.rating; i++) {
        starsHTML += `<img src="./assets/icons/star_rating.svg" class="w-3 h-3" />`;
      }

      topRatedSlidesHTML += `
        <div class="swiper-slide">
          <a href="#" class="fd-item relative block w-full transition-all duration-500 hover:-translate-y-2 transform-gpu">
            <img
              src="${item.image}"
              class="aspect-[16/10] rounded-2xl object-cover w-full"
            />
            <div
              class="p-2 absolute top-0 left-0 right-0 flex items-center justify-between z-10"
            >
              <span class="w-9 h-9 flex rounded-full bg-black/30">
                <img
                  src="./assets/icons/heart_line_icon.svg"
                  class="m-auto"
                />
              </span>
              <div class="flex items-center gap-1">
                <span
                  class="bg-secondary text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1"
                >
                  <img
                    src="./assets/icons/ticket_star_icon.svg"
                    class="w-6 h-6"
                  />
                  ${item.discount}
                </span>
                <span
                  class="bg-warning text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1"
                >
                  <img
                    src="./assets/icons/clock_icon.svg"
                    class="w-6 h-6"
                  />
                  ${item.deliveryTime}
                </span>
              </div>
            </div>
            <div class="flex items-center justify-between gap-1.5 mt-3 mb-1">
              <span class="flex items-center capitalize gap-1.5 text-muted">
                <img
                  class="w-7 h-7"
                  src="${item.restaurantLogo}"
                />
                ${item.restaurant}
              </span>
              <span
                class="flex items-center capitalize gap-1.5 text-secondary"
              >
                <span class="flex items-center">
                  ${starsHTML}
                </span>
                ${item.rating}
              </span>
            </div>
            <div class="flex flex-col">
              <h3 class="font-medium text-lg leading-[1.5] md:text-[22px] md:leading-snug capitalize text-start">
                ${item.title}
              </h3>
              <div class="flex items-center justify-between font-medium">
                <div class="flex items-center gap-1 text-lg">
                  <span class="text-muted line-through">${item.originalPrice}</span>
                  <span class="text-secondary">${item.discountedPrice}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                  <img
                    src="./assets/icons/map_banner_input_icon.svg"
                    class="w-6 h-6"
                  />
                  <span>${item.distance}</span>
                </div>
              </div>
            </div>
          </a>
        </div>
      `;
    });

    topRatedSliderWrapper.innerHTML = topRatedSlidesHTML;
  }

  // Initialize top-rated items slider
  var topRatedSwiper = new Swiper(".top-rated-slider", {
    slidesPerView: 1,
    spaceBetween: 16,
    breakpoints: {
      350: {
        slidesPerView: 1.2,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 1.5,
        spaceBetween: 16,
      },
      768: {
        slidesPerView: 2.2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 2.5,
        spaceBetween: 20,
      },
      1280: {
        slidesPerView: 3.1,
        spaceBetween: 20,
      },
      1536: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1920: {
        slidesPerView: 3.5,
        spaceBetween: 20,
      },
      2560: {
        slidesPerView: 6,
        spaceBetween: 30,
      },
    },
  });
});
