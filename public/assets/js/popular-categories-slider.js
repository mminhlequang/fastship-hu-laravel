// Popular categories data array
const popularCategories = [
  {
    image: "./assets/images/food_category_1.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_2.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_3.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_4.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_5.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_6.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_1.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_2.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_3.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_4.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_5.svg",
    title: "Fast food",
    places: "21 place",
  },
  {
    image: "./assets/images/food_category_6.svg",
    title: "Fast food",
    places: "21 place",
  },
];

document.addEventListener("DOMContentLoaded", function () {
  // Populate popular categories slides
  const categoriesSliderWrapper = document.querySelector(
    ".popular-categories-slider .swiper-wrapper"
  );

  if (categoriesSliderWrapper) {
    let categoriesSlidesHTML = "";

    popularCategories.forEach((category) => {
      categoriesSlidesHTML += `
        <div class="swiper-slide rounded-2xl">
          <div class="rounded-2xl bg-white p-4 flex flex-col gap-8 hover:shadow-xl transition-all w-full cursor-pointer">
            <img src="${category.image}" loading="lazy" class="w-full" alt="Food Category" />
            <div class="flex flex-col gap-1 items-center justify-center">
              <h3 class="font-medium text-lg">${category.title}</h3>
              <p class="text-secondary capitalize">${category.places}</p>
            </div>
          </div>
        </div>
      `;
    });

    categoriesSliderWrapper.innerHTML = categoriesSlidesHTML;
  }

  // Initialize popular categories slider
  var categoriesSwiper = new Swiper(".popular-categories-slider", {
    slidesPerView: 1,
    spaceBetween: 16,
    navigation: {
      nextEl: ".custom-btn-next-slide",
      prevEl: ".custom-btn-prev-slide",
    },
    breakpoints: {
      400: {
        slidesPerView: 2.5,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 3.1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 3.5,
        spaceBetween: 20,
      },
      1020: {
        slidesPerView: 4.5,
        spaceBetween: 20,
      },
      1280: {
        slidesPerView: 6.5,
        spaceBetween: 20,
      },
      1536: {
        slidesPerView: 6,
        spaceBetween: 20,
      },
    },
  });
});
