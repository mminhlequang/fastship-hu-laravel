const locationPopover = document.getElementById("location-popover");

// Create a backdrop element with Tailwind classes and transitions
const createBackdrop = () => {
  const backdrop = document.createElement("div");
  backdrop.id = "tippy-backdrop";

  // Add Tailwind classes including transitions
  backdrop.className =
    "fixed inset-0 bg-black/50 z-40 transition-opacity duration-200 opacity-0";

  // Force a reflow before adding the opacity
  setTimeout(() => {
    backdrop.classList.remove("opacity-0");
    backdrop.classList.add("opacity-100");
  }, 10);

  return backdrop;
};

// Function to remove backdrop with transition
const removeBackdrop = () => {
  const backdrop = document.getElementById("tippy-backdrop");
  if (backdrop) {
    // Add fade-out transition
    backdrop.classList.remove("opacity-100");
    backdrop.classList.add("opacity-0");

    // Remove after transition completes
    setTimeout(() => {
      if (backdrop.parentNode) {
        backdrop.remove();
      }
    }, 200); // Match this with your transition duration
  }
};

// List of IDs that should close the popover when clicked
const closeButtonIds = [
  "submit-location",
  "close-location-select",
  "cancel-location-select",
];

tippy("#toggle-change-location", {
  content: locationPopover.innerHTML,
  interactive: true,
  allowHTML: true,
  theme: "location-popover",
  zIndex: 50,
  trigger: "click", // Change trigger from hover to click
  hideOnClick: false, // Don't hide when clicking inside the tippy
  appendTo: document.body, // Ensure it's appended to the body
  onShow(instance) {
    // Add backdrop to the body
    document.body.appendChild(createBackdrop());

    // Add click event to close when clicking outside
    const backdrop = document.getElementById("tippy-backdrop");
    if (backdrop) {
      backdrop.addEventListener("click", () => {
        instance.hide();
      });
    }
  },
  onHide() {
    // Remove backdrop with transition
    removeBackdrop();
  },
  onMount(instance) {
    // Use event delegation to handle clicks on close buttons
    const tippyContent = instance.popper.querySelector(".tippy-content");

    if (tippyContent) {
      tippyContent.addEventListener("click", (e) => {
        // Check if the clicked element is one of the close buttons or a child of one
        const isCloseButton = closeButtonIds.some((id) => {
          return e.target.id === id || e.target.closest(`#${id}`);
        });

        if (isCloseButton) {
          instance.hide();
        }
      });
    }
  },
});
