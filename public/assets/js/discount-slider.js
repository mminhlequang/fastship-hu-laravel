// Discount items data array
const discountItems = [
  {
    image: "./assets/images/food_shop_img_1.webp",
    title: "Chef Burgers London",
    type: "Restaurant",
    discount: "20% off",
    rating: 5,
  },
  {
    image: "./assets/images/food_shop_img_1.webp",
    title: "Chef Burgers London",
    type: "Restaurant",
    discount: "20% off",
    rating: 5,
  },
  {
    image: "./assets/images/food_shop_img_1.webp",
    title: "Chef Burgers London",
    type: "Restaurant",
    discount: "20% off",
    rating: 5,
  },
  {
    image: "./assets/images/food_shop_img_1.webp",
    title: "Chef Burgers London",
    type: "Restaurant",
    discount: "20% off",
    rating: 5,
  },
  {
    image: "./assets/images/food_shop_img_1.webp",
    title: "Chef Burgers London",
    type: "Restaurant",
    discount: "20% off",
    rating: 5,
  },
  {
    image: "./assets/images/food_shop_img_1.webp",
    title: "Chef Burgers London",
    type: "Restaurant",
    discount: "20% off",
    rating: 5,
  },
];

document.addEventListener("DOMContentLoaded", function () {
  // Populate discount items slides
  const discountSliderWrapper = document.querySelector(
    ".discount-slider .swiper-wrapper"
  );

  if (discountSliderWrapper) {
    let discountSlidesHTML = "";

    discountItems.forEach((item) => {
      // Generate stars based on rating
      let starsHTML = "";
      for (let i = 0; i < item.rating; i++) {
        starsHTML += `<img src="./assets/icons/star_rating.svg" class="w-3 h-3" />`;
      }

      discountSlidesHTML += `
        <div class="swiper-slide">
          <a href="#" class="dg-item relative block transition-all duration-500 hover:-translate-y-2 transform-gpu">
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
              <span
                class="bg-secondary text-white rounded-full px-3 py-1.5 flex items-center text-sm gap-1"
              >
                <img
                  src="./assets/icons/ticket_star_icon.svg"
                  class="w-6 h-6"
                />
                ${item.discount}
              </span>
            </div>
            <div class="flex flex-col mt-3">
              <h3 class="text-lg leading-[1.5] md:text-[22px] text-start md:leading-snug capitalize">
                ${item.title}
              </h3>
              <div class="flex items-center justify-between font-medium">
                <span class="text-muted">${item.type}</span>
                <span
                  class="flex items-center capitalize gap-1.5 text-secondary"
                >
                  <span class="flex items-center">
                    ${starsHTML}
                  </span>
                  ${item.rating}
                </span>
              </div>
            </div>
          </a>
        </div>
      `;
    });

    discountSliderWrapper.innerHTML = discountSlidesHTML;
  }

  // Initialize discount items slider
  var discountSwiper = new Swiper(".discount-slider", {
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
