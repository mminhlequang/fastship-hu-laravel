<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán QR Code</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="responsive-px p-4">
    <h1 class="text-2xl font-bold mb-6">Thanh toán đơn hàng</h1>

    <!-- Nút mở modal -->
    <button
            id="openQrModal"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    >
        Thanh toán bằng QR Code
    </button>

    <!-- Modal QR Code -->
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Quét mã QR để thanh toán
                            </h3>
                            <div class="mt-2 flex justify-center">
                                <img id="qrCodeImage" src="" alt="QR Code" class="w-64 h-64 border border-gray-200">
                            </div>
                            <p class="mt-4 text-sm text-gray-500">
                                Sử dụng ứng dụng ngân hàng hoặc ví điện tử để quét mã
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                            id="closeQrModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Đóng
                    </button>
                    <a
                            id="paymentLink"
                            href="#"
                            target="_blank"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Mở trình thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('qrModal');
        const openButton = document.getElementById('openQrModal');
        const closeButton = document.getElementById('closeQrModal');

        openButton.addEventListener('click', async function() {
            try {
                const response = await fetch('/create-payment-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                document.getElementById('qrCodeImage').src = data.qr_code_url;
                document.getElementById('paymentLink').href = data.payment_link;

                modal.classList.remove('hidden');
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tạo QR code');
            }
        });

        closeButton.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>