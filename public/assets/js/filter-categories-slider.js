var categoriesSwiper = new Swiper('.filter-categories-slider', {
  slidesPerView: 'auto',
  spaceBetween: 8,
  navigation: {
    nextEl: '.custom-btn-next-slide',
    prevEl: '.custom-btn-prev-slide',
  },
  loop: true,
  pagination: {
    el: '.swiper-pagination',
    clickable: true,
    dynamicBullets: true,
  },
})
