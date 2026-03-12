<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <script src="https://embedded.ryftpay.com/v2/ryft.min.js"></script>
</head>
<body>

<div class="Ryft--paysection">
    <form id="ryft-pay-form" class="Ryft--payform">

        {{-- Card fields --}}
        <div>
            <label>Card Number</label>
            <input type="text" id="card-number" placeholder="4444333322221111" maxlength="16"/>
        </div>
        <div>
            <label>Expiry Month</label>
            <input type="text" id="expiry-month" placeholder="10" maxlength="2"/>
        </div>
        <div>
            <label>Expiry Year</label>
            <input type="text" id="expiry-year" placeholder="2024" maxlength="4"/>
        </div>
        <div>
            <label>CVC</label>
            <input type="text" id="cvc" placeholder="100" maxlength="4"/>
        </div>

        <button id="pay-btn">PAY NOW</button>
        <div id="ryft-pay-error"></div>
        <div id="ryft-pay-success"></div>
    </form>
</div>

<script>
    let storedClientSecret = null;

    // Step 1: Create payment session on page load
    async function initPayment() {
        try {
            const response = await fetch('/create-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.error) {
                showError(data.error);
                return;
            }

            console.log( data.clientSecret);

            storedClientSecret = data.clientSecret; // ✅ store for later
            console.log('Payment session ready');

        } catch (error) {
            console.error('Init error:', error);
            showError('Failed to initialise payment.');
        }
    }

    // Step 2: On Pay button click, send card details to Laravel
    document.getElementById('pay-btn').addEventListener('click', async (e) => {
        e.preventDefault();

        if (!storedClientSecret) {
            showError('Payment session not ready. Please refresh.');
            return;
        }

        const payload = {
            clientSecret: storedClientSecret,
            number:       document.getElementById('card-number').value,
            expiryMonth:  document.getElementById('expiry-month').value,
            expiryYear:   document.getElementById('expiry-year').value,
            cvc:          document.getElementById('cvc').value,
        };

        try {
            const response = await fetch('/attempt-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('ryft-pay-success').innerHTML =
                    `<p style='color:green;'>${result.message}</p>`;
            } else {
                showError(result.error ?? 'Payment failed.');
            }

        } catch (error) {
            console.error('Payment error:', error);
            showError('An unexpected error occurred.');
        }
    });

    function showError(msg) {
        document.getElementById('ryft-pay-error').innerHTML =
            `<p style='color:red;'>${msg}</p>`;
    }

    initPayment();
</script>

</body>
</html>
