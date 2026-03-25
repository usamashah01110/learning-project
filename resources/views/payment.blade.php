<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryft Card Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://web-sdk.ryftpay.com/embedded/latest/ryft.min.js"></script>

    <style>
        #pay-btn {
            background-color: #0070f3;
            color: white;
            border: none;
            padding: 14px 22px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 12px;
        }

        #pay-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #09f;
            animation: spin 1s ease infinite;
            display: none;
            margin: 10px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>

<div class="Ryft--paysection">
    <form id="ryft-pay-form" class="Ryft--payform">
        <div id="card-form-container"></div>
        <button id="pay-btn" disabled>PAY NOW</button>
        <div id="ryft-pay-error"></div>
        <div id="spinner-container" class="spinner"></div>
    </form>
</div>

<script>
    const { createController, createCardForm } = window.Ryft;

    const publicKey = "pk_sandbox_p3i/voBCeoSpfbzeP2cNfhnURgM6DYiZGjXB3hn8d3xBKr/hrezFJw+lmvFqjeb2"; // 👈 your sandbox public key

    const payButton = document.getElementById("pay-btn");
    const errorDiv  = document.getElementById("ryft-pay-error");
    const spinner   = document.getElementById("spinner-container");


    async function initRyft() {
        try {
            const res = await fetch("/create-payment", {   // 👈 your route
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                },
            });

            const data = await res.json();
            console.log('Client Secret:', data.clientSecret);
            if (!res.ok || !data.clientSecret) {
                errorDiv.innerHTML = "<p style='color:red;'>Could not initialise payment. Please try again.</p>";
                return;
            }

            const clientSecret = data.clientSecret;
            const accountId    = data.accountId;


            const controller = createController({
                publicKey,
                clientSecret,
            });

            // Step 3: Create & mount the card form
            const cardForm = createCardForm(controller);

            cardForm.on("validationChange", (event) => {
                payButton.disabled = !event.isValid;
            });

            cardForm.mount("#card-form-container");


            payButton.addEventListener("click", async (e) => {
                e.preventDefault();

                errorDiv.innerHTML  = "";
                payButton.disabled  = true;
                spinner.style.display = "block";

                try {
                    const result = await cardForm.attemptPayment();
                    handlePaymentResult(result);
                } catch (error) {
                    spinner.style.display = "none";
                    payButton.disabled    = false;
                    console.error("System Error:", error);
                    errorDiv.innerHTML = "<p style='color:red;'>An unexpected error occurred.</p>";
                }
            });

        } catch (err) {
            console.error("Init error:", err);
            errorDiv.innerHTML = "<p style='color:red;'>Failed to load payment form.</p>";
        }
    }

    function handlePaymentResult(result) {
        spinner.style.display = "none";
        const session = result.paymentSession;

        if (session.status === "Approved" || session.status === "Captured") {
            console.log("Payment Successful", session);
            errorDiv.innerHTML = "<p style='color:green;'>Payment Successful!</p>";
            return;
        }

        if (session.lastError) {
            payButton.disabled = false;
            console.error("Payment Error", session.lastError);
            errorDiv.innerHTML = `<p style='color:red;'>${result.userFacingErrorMessage}</p>`;
        }
    }

    initRyft();
</script>

</body>
</html>
