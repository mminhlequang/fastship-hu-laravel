document.addEventListener("DOMContentLoaded", function () {
  var topRatedSlider = new Swiper(".top-rated-slider", {
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
        slidesPerView: 2.7,
        spaceBetween: 20,
      },
      1280: {
        slidesPerView: 3.07,
        spaceBetween: 24,
      },
      1536: {
        slidesPerView: 4,
        spaceBetween: 24,
      },
      1920: {
        slidesPerView: 4,
        spaceBetween: 24,
      },
      2560: {
        slidesPerView: 6,
        spaceBetween: 30,
      },
    },
  });
});
