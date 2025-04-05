// Sample data for vouchers
const vouchers = [
  {
    id: 1,
    title: "Free shipping $1 off",
    description: "$1 off, when you order $3 more to enjoy this offer",
    image: "./assets/icons/cart/pr2.png",
    icon: "./assets/icons/cart/1.svg",
    code: "VOUCHER1",
  },
];

// Store selected voucher
let selectedVoucher = null;

// Render voucher list dynamically
function renderVouchers() {
  const voucherListContainer = document.getElementById("voucher-list");
  voucherListContainer.innerHTML = ""; // Clear existing list

  vouchers.forEach((voucher) => {
    const voucherItem = document.createElement("div");
    voucherItem.classList.add(
      "voucher-item",
      "flex",
      "items-center",
      "justify-between",
      "border-b",
      "rounded-lg",
      "p-2"
    );
    voucherItem.onclick = () => selectVoucher(voucher);

    voucherItem.innerHTML = `
            <div class="flex flex-col items-start lg:flex-row lg:items-center gap-3">
              <div class="flex items-center gap-2">
                <img src="${voucher.image}" alt="Voucher Image" id="voucher-image-${voucher.id}" />
                <div class="flex flex-col">
                  <span class="text-base lg:text-xl text-[#120F0F]">
                    ${voucher.title} <strong class="text-[#F17228]">$1 off</strong>
                  </span>
                  <span class="text-sm text-[#7D7575]">${voucher.description}</span>
                </div>
              </div>
            </div>
            <img id="voucher-icon-${voucher.id}" src="${voucher.icon}" alt="Voucher Icon" class="w-9 h-9" />
          `;

    voucherListContainer.appendChild(voucherItem);
  });
}

// Handle voucher selection/deselection
function selectVoucher(voucher) {
  const voucherIcon = document.getElementById(`voucher-icon-${voucher.id}`);

  // If the voucher is already selected, deselect it
  if (selectedVoucher && selectedVoucher.id === voucher.id) {
    selectedVoucher = null;
    voucherIcon.src = voucher.icon; // Reset to the default icon
    document.getElementById("voucher-input").classList.add("hidden"); // Hide the input
  } else {
    selectedVoucher = voucher;
    voucherIcon.src = "./assets/icons/cart/More Circle.svg"; // Change to selected icon
    document.getElementById("voucher-input").classList.remove("hidden"); // Show the input
    document.getElementById("voucher-input").value = voucher.title; // Set voucher title in input
  }

  console.log(
    "Voucher selected:",
    selectedVoucher ? selectedVoucher.title : "None"
  );
}

// Apply voucher
function applyVoucher() {
  if (selectedVoucher) {
    alert("Voucher " + selectedVoucher.title + " applied!");
  } else {
    alert("No voucher selected!");
  }
}

// Toggle modal visibility
function toggleModal() {
  const modal = document.getElementById("modal-voucher");
  modal.classList.toggle("hidden"); // Toggle visibility of the modal
}

// Close the modal
function closeModal() {
  const modal = document.getElementById("modal-voucher");
  modal.classList.add("hidden");
}

renderVouchers();

function selectOption(selected) {
  document.querySelectorAll(".option").forEach((option) => {
    option.classList.remove("border-[#74CA45]", "bg-green-100");
    option.classList.add("border-gray-400");
  });
  selected.classList.add("border-[#74CA45]", "bg-green-100");
  selected.classList.remove("border-gray-400");
}

document.addEventListener("DOMContentLoaded", function () {
  const defaultOption = document.querySelector(".option");
  if (defaultOption) {
    selectOption(defaultOption);
  }
});
