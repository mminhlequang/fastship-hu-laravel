var categoriesSwiper = new Swiper('.popular-categories-slider', {
  slidesPerView: 'auto',
  spaceBetween: 24,
  navigation: {
    nextEl: '.custom-btn-next-slide',
    prevEl: '.custom-btn-prev-slide',
  },
  breakpoints: {
    400: {
      slidesPerView: 2.5,
    },
    640: {
      slidesPerView: 3.1,
    },
    768: {
      slidesPerView: 3.5,
    },
    1020: {
      slidesPerView: 4.5,
    },
    1280: {
      slidesPerView: 6.5,
    },
    1536: {
      slidesPerView: 6.5,
    },
  },
})
