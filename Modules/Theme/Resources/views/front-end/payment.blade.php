<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment Modal</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
    <div class="bg-yellow-50 rounded-lg p-3 mb-4 text-center text-yellow-800 text-sm">
        TEST MODE - Use card number 4242 4242 4242 4242 for successful payment
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-4">Pay Kate</h2>

    <div class="space-y-2 mb-6">
        <div class="flex justify-between">
            <span class="text-gray-600">Silver</span>
            <span class="font-medium">$10.00</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium">$10.00</span>
        </div>
        <div class="flex justify-between border-t border-gray-200 pt-3 mt-3">
            <span class="font-bold">Total due</span>
            <span class="font-bold">$10.00</span>
        </div>
    </div>

    <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
        Pay $10.00
    </button>

    <div class="mt-6 text-center text-xs text-gray-500">
        <div>Powered by <strong>Stripe</strong> | <a href="#" class="text-blue-600 hover:underline">Terms</a> <a href="#" class="text-blue-600 hover:underline">Privacy</a></div>
    </div>
</div>

<!-- Payment Modal -->
<div id="payment-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Enter Payment Details</h3>
            <form id="payment-form">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" value="demo@gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Card information</label>
                    <div id="card-element" class="p-3 border border-gray-300 rounded-md"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Name on card</label>
                    <input type="text" id="name" value="Hartini" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Country or region</label>
                    <input type="text" id="country" value="India" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6 flex items-start">
                    <input type="checkbox" id="save-info" checked class="mt-1 mr-2">
                    <label for="save-info" class="text-sm text-gray-700">
                        Securely save my information for 1-click checkout
                        <p class="text-xs text-gray-500 mt-1">Pay faster on Kate and everywhere Link is accepted.</p>
                    </label>
                </div>

                <div class="flex space-x-3">
                    <button id="submit-button" type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-medium transition-colors">
                        Pay Now
                    </button>
                    <button type="button" id="cancel-button" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md font-medium transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const stripe = Stripe('pk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd');
    let elements;
    let cardElement;

    const modal = document.getElementById("payment-modal");
    const payButton = document.getElementById("pay-button");
    const cancelButton = document.getElementById("cancel-button");

    payButton.addEventListener('click', () => {
        modal.classList.remove('hidden');

        if (!elements) {
            elements = stripe.elements();
            cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a'
                    }
                }
            });
            cardElement.mount('#card-element');

            setTimeout(() => {
                const iframe = document.querySelector('iframe[title="Secure card number input frame"]');
                if (iframe) {
                    iframe.contentWindow.postMessage({
                        type: 'autofill',
                        payload: {
                            cardNumber: '4242424242424242',
                            cardExpiry: '12/25',
                            cardCvc: '123'
                        }
                    }, '*');
                }
            }, 500);
        }
    });

    cancelButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    document.getElementById('payment-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';

        try {
            const response = await fetch('/api/v1/create-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: 1000,
                    currency: 'usd',
                    email: document.getElementById('email').value
                })
            });

            const data = await response.json();
            if (data.error) throw new Error(data.error);

            const result = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        address: {
                            country: document.getElementById('country').value
                        }
                    }
                }
            });

            if (result.error) {
                throw result.error;
            }

            if (result.paymentIntent.status === 'succeeded') {
                modal.classList.add('hidden');
                alert('Payment succeeded!');
            }
        } catch (error) {
            alert(error.message);
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Pay Now';
        }
    });
</script>
</body>
</html>