const locationPopover = document.getElementById("location-popover");

const createBackdrop = () => {
  const backdrop = document.createElement("div");
  backdrop.id = "tippy-backdrop";

  backdrop.className =
    "fixed inset-0 bg-black/50 z-40 transition-opacity duration-200 opacity-0";


  setTimeout(() => {
    backdrop.classList.remove("opacity-0");
    backdrop.classList.add("opacity-100");
  }, 10);

  return backdrop;
};

const removeBackdrop = () => {
  const backdrop = document.getElementById("tippy-backdrop");
  if (backdrop) {

    backdrop.classList.remove("opacity-100");
    backdrop.classList.add("opacity-0");

    setTimeout(() => {
      if (backdrop.parentNode) {
        backdrop.remove();
      }
    }, 200);
  }
};


const closeButtonIds = [
  "submit-location",
  "close-location-select",
  "cancel-location-select",
];

