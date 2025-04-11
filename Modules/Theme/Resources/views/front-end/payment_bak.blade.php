<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Checkout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Pay for Silver</h2>

    <div class="space-y-2 mb-6">
        <div class="flex justify-between">
            <span class="text-gray-600">Silver</span>
            <span class="font-medium">$10.00</span>
        </div>
        <div class="flex justify-between border-t border-gray-200 pt-3 mt-3">
            <span class="font-bold">Total due</span>
            <span class="font-bold">$10.00</span>
        </div>
    </div>

    <button id="checkout-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
        Pay $10.00
    </button>
</div>

<script>
    var stripe = Stripe('pk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd');

    document.getElementById('checkout-button').addEventListener('click', function () {
        fetch('{{ url('createCheckoutSession') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (sessionId) {
                return stripe.redirectToCheckout({ sessionId: sessionId.id });
            })
            .then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {
                console.error("Error:", error);
            });
    });
</script>
</body>
</html>
