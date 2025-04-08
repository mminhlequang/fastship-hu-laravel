<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* Basic styling for the modal */
        #paymentModal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black with opacity */
        }

        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Thanh toán với Stripe</h1>

<button id="payButton">Thanh toán</button>

<!-- Modal -->
<div id="paymentModal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Thanh toán với Stripe</h2>
        <form id="payment-form">
            <div id="card-element"></div>
            <button id="submit" type="submit">Thanh toán</button>
            <div id="error-message" style="color: red;"></div>
        </form>
    </div>
</div>

<script>
    var stripe = Stripe('pk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd');
    const cardElement = stripe.elements().create('card');
    cardElement.mount('#card-element');

    const payButton = document.getElementById('payButton');
    const paymentModal = document.getElementById('paymentModal');
    const closeModalButton = document.getElementById('closeModal');
    const form = document.getElementById('payment-form');

    // Show modal when Pay button is clicked
    payButton.addEventListener('click', function() {
        paymentModal.style.display = 'block';
    });

    // Close modal when close button is clicked
    closeModalButton.addEventListener('click', function() {
        paymentModal.style.display = 'none';
    });

    // Close modal if clicked outside of modal content
    window.addEventListener('click', function(event) {
        if (event.target === paymentModal) {
            paymentModal.style.display = 'none';
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const {token, error} = await stripe.createToken(cardElement);

        if (error) {
            document.getElementById('error-message').textContent = error.message;
        } else {
            const orderData = {
                name: 'John Doe',
                amount: 5000,
                currency: 'usd',
                token: token.id
            };

            fetch('/api/v1/create-payment', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(orderData)
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var clientSecret = data.clientSecret;
                    var orderId = data.orderId;

                    stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {name: 'John Doe'}
                        }
                    }).then(function (result) {
                        if (result.error) {
                            document.getElementById('error-message').textContent = result.error.message;
                        } else {
                            if (result.paymentIntent.status === 'succeeded') {
                                fetch('/api/v1/confirm-payment', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json'},
                                    body: JSON.stringify({
                                        paymentIntentId: result.paymentIntent.id,
                                        orderId: orderId
                                    })
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Thanh toán thành công!');
                                            paymentModal.style.display = 'none'; // Close modal on success
                                        } else {
                                            alert('Thanh toán thất bại!');
                                        }
                                    });
                            }
                        }
                    });
                });
        }
    });
</script>

</body>
</html>
