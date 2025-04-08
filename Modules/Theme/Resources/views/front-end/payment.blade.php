<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<h1>Thanh toán với Stripe</h1>

<div id="payment-form">
    <form id="payment-form">
        <div id="card-element"></div>
        <button id="submit" type="submit">Thanh toán</button>
        <div id="error-message"></div>
    </form>
</div>

<script>
    var stripe = Stripe('pk_test_51QwQfYGbnQCWi1BqsVDBmUNXwsA6ye6daczJ5E7j8zgGTjuVAWjLluexegaACZTaHP14XUtrGxDLHwxWzMksUVod00p0ZXsyPd');
    const cardElement = stripe.elements().create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');

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
