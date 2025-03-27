// Customer logos data array
const customerLogos = [
  { src: "./assets/images/customer_logo_1.webp" },
  { src: "./assets/images/customer_logo_2.webp" },
  { src: "./assets/images/customer_logo_3.webp" },
  { src: "./assets/images/customer_logo_4.webp" },
  { src: "./assets/images/customer_logo_1.webp" },
  { src: "./assets/images/customer_logo_1.webp" },
  { src: "./assets/images/customer_logo_2.webp" },
  { src: "./assets/images/customer_logo_3.webp" },
  { src: "./assets/images/customer_logo_4.webp" },
  { src: "./assets/images/customer_logo_1.webp" },
];

document.addEventListener("DOMContentLoaded", function () {
  // Populate customer logo slides
  const customerSliderWrapper = document.querySelector(
    ".great-customer-slider .swiper-wrapper"
  );

  if (customerSliderWrapper) {
    let slidesHTML = "";

    customerLogos.forEach((logo) => {
      slidesHTML += `
        <div class="swiper-slide">
          <div>
            <img src="${logo.src}" class="aspect-square w-full object-cover rounded-xl" />
          </div>
        </div>
      `;
    });

    customerSliderWrapper.innerHTML = slidesHTML;
  }

  // Initialize customer logo slider
  var logoSwiper = new Swiper(".great-customer-slider", {
    slidesPerView: 1,
    spaceBetween: 16,
    breakpoints: {
      400: {
        slidesPerView: 2.5,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 4,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 7,
        spaceBetween: 20,
      },
      1280: {
        slidesPerView: 8,
        spaceBetween: 20,
      },
      1536: {
        slidesPerView: 10,
        spaceBetween: 20,
      },
    },
  });
});
