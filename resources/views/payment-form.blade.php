<!DOCTYPE html>
<html>
<head>
    <title>Square Payment</title>
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
</head>
<body>
    @if(session('success'))
        <h3 style="color:green">{{ session('success') }}</h3>
    @endif
    @if(session('error'))
        <h3 style="color:red">{{ session('error') }}</h3>
    @endif

    <form id="payment-form" method="POST" action="{{ route('payment.process') }}">
        @csrf
        <div id="card-container"></div>
        <button id="card-button" type="submit">Pay $10</button>
        <input type="hidden" name="nonce" id="card-nonce">
    </form>

    <script>
        const payments = Square.payments('{{ env('SQUARE_APPLICATION_ID') }}', '{{ env('SQUARE_ENVIRONMENT') }}');
        let card;
        async function initializeCard() {
            card = await payments.card();
            await card.attach('#card-container');
        }
        initializeCard();

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const result = await card.tokenize();
            if (result.status === 'OK') {
                document.getElementById('card-nonce').value = result.token;
                form.submit();
            } else {
                alert('Payment failed: ' + result.errors[0].detail);
            }
        });
    </script>
</body>
</html>
