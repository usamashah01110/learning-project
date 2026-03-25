<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ryft SDK -->
    <script src="https://web-sdk.ryftpay.com/embedded/latest/ryft.min.js"></script>

    <style>
        body {
            background: #f5f7fb;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .btn-primary {
            background-color: #0070f3;
            border: none;
        }

        .btn-primary:hover {
            background-color: #005ad1;
        }

        .spinner {
            display: none;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- 🔹 Customer Form -->
            <div class="card shadow p-4" id="customer-card">
                <h4 class="mb-3 text-center">💳 Enter Payment Details</h4>

                <form id="customer-form">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" id="name" class="form-control" placeholder="John Doe" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" id="email" class="form-control" placeholder="john@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" id="amount" class="form-control" placeholder="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Currency</label>
                        <select name="currency" id="currency" class="form-control">
                            <option value="USD"> USD </option>
                            <option value="EUR"> EUR </option>
                            <option value="GBP"> GBP </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Continue to Payment →
                    </button>
                </form>
            </div>

            <!-- 🔹 Payment Section -->
            <div class="card shadow p-4 mt-4" id="payment-card" style="display:none;">
                <h4 class="mb-3 text-center">🔒 Secure Card Payment</h4>

                <form id="ryft-pay-form">
                    <div id="card-form-container" class="mb-3"></div>

                    <button id="pay-btn" class="btn btn-success w-100" disabled>
                        Pay Now
                    </button>

                    <div id="spinner" class="text-center mt-3 spinner">
                        <div class="spinner-border text-primary"></div>
                    </div>

                    <div id="error-message" class="mt-3 text-center"></div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    const { createController, createCardForm } = window.Ryft;

    const publicKey = "pk_sandbox_p3i/voBCeoSpfbzeP2cNfhnURgM6DYiZGjXB3hn8d3xBKr/hrezFJw+lmvFqjeb2"; // 👈 replace

    const customerForm = document.getElementById("customer-form");
    const paymentCard  = document.getElementById("payment-card");
    const customerCard = document.getElementById("customer-card");

    const payBtn   = document.getElementById("pay-btn");
    const spinner  = document.getElementById("spinner");
    const errorDiv = document.getElementById("error-message");

    let cardFormInstance = null;

    customerForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const currency   = document.getElementById("currency").value;
        const email  = document.getElementById("email").value;
        const amount = document.getElementById("amount").value;

        try {
            const res = await fetch("/create-payment", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    currency: currency,
                    email: email,
                    amount: amount
                })
            });

            const data = await res.json();

            if (!res.ok || !data.clientSecret) {
                showError("Failed to initialize payment");
                return;
            }

            // UI Switch
            customerCard.style.display = "none";
            paymentCard.style.display  = "block";

            initCardForm(data.clientSecret);

        } catch (err) {
            console.error(err);
            showError("Something went wrong");
        }
    });

    function initCardForm(clientSecret) {

        const controller = createController({
            publicKey,
            clientSecret,
        });

        cardFormInstance = createCardForm(controller);

        cardFormInstance.on("validationChange", (event) => {
            payBtn.disabled = !event.isValid;
        });

        cardFormInstance.mount("#card-form-container");

        payBtn.addEventListener("click", async (e) => {
            e.preventDefault();

            spinner.style.display = "block";
            payBtn.disabled = true;
            errorDiv.innerHTML = "";

            try {
                const result = await cardFormInstance.attemptPayment();
                handlePaymentResult(result);
            } catch (error) {
                spinner.style.display = "none";
                payBtn.disabled = false;
                showError("Payment failed");
            }
        });
    }

    function handlePaymentResult(result) {
        spinner.style.display = "none";

        const session = result.paymentSession;

        if (session.status === "Approved" || session.status === "Captured") {
            errorDiv.innerHTML = "<p class='text-success fw-bold'>✅ Payment Successful</p>";
            return;
        }

        if (session.lastError) {
            payBtn.disabled = false;
            showError(result.userFacingErrorMessage);
        }
    }

    function showError(message) {
        errorDiv.innerHTML = `<p class="text-danger">${message}</p>`;
    }
</script>

</body>
</html>
