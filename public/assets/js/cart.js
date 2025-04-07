const locations = {
  recent: ["123 Main St, City A", "456 Oak St, City B", "789 Pine St, City C"],
  propose: ["101 Maple St, City D", "202 Birch St, City E"],
  saved: [
    "303 Cedar St, City F",
    "404 Spruce St, City G",
    "505 Willow St, City H",
  ],
};

const modalOverlay = document.querySelector("#overlay");
const modalCloseBtn = document.querySelector(".close-modal-location");
const modalContainer = document.querySelector("#modal-container");
const openModalBtn = document.getElementById("open-modal-location");

const closeVoucher = document.querySelector(".close-voucher");
const modalVoucher = document.querySelector("#modal-voucher");
const modalOverlayVoucher = document.querySelector("#overlay-voucher");
const openModalVoucherBtn = document.querySelector("#open-modal-voucher");

function openModal() {
  modalContainer.classList.remove("hidden");
  modalContainer.classList.add("flex");
  setActiveTab("recent"); // Khi mở modal, tab mặc định là "recent"
}

function closeModal() {
  modalContainer.classList.add("hidden");
}

function openModalVoucher() {
  modalVoucher.classList.remove("hidden");
  modalVoucher.classList.add("flex");
}

function closeModalVoucher() {
  modalVoucher.classList.add("hidden");
}

openModalBtn.addEventListener("click", openModal);
modalOverlay.addEventListener("click", closeModal);
modalCloseBtn.addEventListener("click", closeModal);

openModalVoucherBtn.addEventListener("click", openModalVoucher);

modalOverlayVoucher.addEventListener("click", closeModalVoucher);
closeVoucher.addEventListener("click", closeModalVoucher);

document.querySelectorAll(".payment-option").forEach((option) => {
  option.addEventListener("click", () => {
    document.querySelectorAll(".payment-option").forEach((opt) => {
      opt.classList.remove("border-[#74CA45]", "bg-[#E6F7D4]");
      opt.classList.add("bg-[#F9F8F6]");
    });
    option.classList.add("border-[#74CA45]", "bg-[#E6F7D4]");
    option.classList.remove("bg-[#F9F8F6]");
    option.querySelector("input").checked = true;
  });
});

function renderLocations(tab) {
  const listContainer = document.getElementById(`${tab}-list`);
  listContainer.innerHTML = "";

  if (locations[tab].length === 0) {
    listContainer.innerHTML = `<p class='text-gray-500'>No ${tab} locations.</p>`;
    return;
  }

  locations[tab].forEach((loc) => {
    const div = document.createElement("div");
    div.classList.add(
      "flex",
      "items-center",
      "justify-between",
      "border-b",
      "rounded-lg",
      "p-2",
      "cursor-pointer"
    );

    div.innerHTML = `
      <div class='flex flex-col items-start lg:flex-row lg:items-center gap-3 location-item'>
        <div class='flex items-center gap-2'>
          <img src="./assets/icons/cart/addr.svg" alt="addr" />
          <div class='flex flex-col'>
            <span class='text-base lg:text-xl text-[#120F0F] location-name'>${loc}</span>
          </div>
        </div>
      </div>
      <img class='status-icon' src="./assets/icons/cart/circle.svg" alt="status" />
    `;

    div.addEventListener("click", function () {
      document.getElementById("location-input").value = loc;

      // Update status icon on selection
      const allDivs = document.querySelectorAll(`#${tab}-list .status-icon`);
      allDivs.forEach((icon) => (icon.src = "./assets/icons/cart/circle.svg")); // Reset all icons
      div.querySelector(".status-icon").src =
        "./assets/icons/cart/More Circle.svg"; // Mark selected one
    });

    listContainer.appendChild(div);
  });
}

function setActiveTab(tabId) {
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    const isActive = btn.getAttribute("data-tab") === tabId;

    btn.classList.toggle("text-[#120F0F]", isActive);
    btn.classList.toggle("border-[#120F0F]", isActive);
    btn.classList.toggle("font-medium", isActive);

    btn.classList.toggle("text-gray-500", !isActive);
    btn.classList.toggle("border-transparent", !isActive);
  });

  document.querySelectorAll(".tab-content").forEach((content) => {
    content.classList.add("hidden");
  });

  document.getElementById(tabId).classList.remove("hidden");
  renderLocations(tabId);
}

document.querySelectorAll(".tab-btn").forEach((button) => {
  button.addEventListener("click", () => {
    const tabId = button.getAttribute("data-tab");
    setActiveTab(tabId);
  });
});

setActiveTab("recent");
document.querySelectorAll(".tab-btn").forEach((button) => {
  button.addEventListener("click", () => {
    const tabId = button.getAttribute("data-tab");
    setActiveTab(tabId);
  });
});

function closeModal() {
  modalContainer.classList.add("hidden");
  setActiveTab("recent");
}

modalOverlay.addEventListener("click", closeModal);
modalCloseBtn.addEventListener("click", closeModal);

setActiveTab("recent");

function selectOption(selected) {
  document.querySelectorAll(".option").forEach((option) => {
    option.classList.remove("border-[#74CA45]", "bg-green-100");
  });
  selected.classList.add("border-[#74CA45]", "bg-green-100");
  selected.classList.remove("border-gray-400");
}

// Mặc định chọn "Super fast"
document.addEventListener("DOMContentLoaded", function () {
  const defaultOption = document.querySelector(".option");
  if (defaultOption) {
    selectOption(defaultOption);
  }
});
