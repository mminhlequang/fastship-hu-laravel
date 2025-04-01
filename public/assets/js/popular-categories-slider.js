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
