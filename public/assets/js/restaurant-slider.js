// Initialize local favorites slider
var restaurantSlider = new Swiper(".restaurant-slider", {
  pagination: {
    el: ".swiper-pagination",
  },
  navigation: {
    nextEl: ".btn-next-blur",
    prevEl: ".btn-prev-blur",
  },
  slidesPerView: 1,
  spaceBetween: 16,
});
