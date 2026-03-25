<?php

namespace App\Http\Controllers;

use App\Services\RyftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $ryft;
    private string $webhookSecret;


    public function __construct(RyftService $ryft)
    {
        $this->ryft = $ryft;
        $this->webhookSecret = "wh_dbd51067-155c-4bf8-8947-19bb7766fa15";

    }

    public function createPayment(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'sk_sandbox_oQiFa4zlwTQlVp1en7P+MoO4Ng2f21Pxvp0Pi1I598UOq7kcAX9SMPhY0Nc8LRd2',
            'Content-Type'  => 'application/json',
        ])->post('https://sandbox-api.ryftpay.com/v1/payment-sessions', [ // ✅ sandbox URL
            'amount'        => $request->amount * 100,
            'currency'      => $request->currency,
            'customerEmail' => $request->email,
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Failed to create payment session',
                'details' => $response->json() // helpful for debugging
            ], 500);
        }
//        dd($response->json());
        return response()->json([
            'clientSecret' => $response->json('clientSecret'),
            'accountId' => 'ac_ba1145db-1ec9-4ebf-b468-e5ac6834d414',
        ]);
    }

    public function handle(Request $request)
    {

        if ($request->header('ryft-signature')) {
            if (!$this->verifySignature($request)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        } else {
            Log::warning('Ryft webhook without signature (sandbox)');
        }

        $payload = $request->json()->all();

        Log::info('Ryft webhook received', $payload);

        $eventType      = $payload['type']           ?? null;
        $paymentSession = $payload['paymentSession'] ?? null;

        if (!$eventType || !$paymentSession) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $sessionId     = $paymentSession['id']            ?? null;
        $status        = $paymentSession['status']        ?? null;
        $amount        = $paymentSession['amount']        ?? null;   // in pence
        $currency      = $paymentSession['currency']      ?? null;
        $customerEmail = $paymentSession['customerEmail'] ?? null;
        $lastError     = $paymentSession['lastError']     ?? null;

        match ($eventType) {
            'payment_session.approved',
            'payment_session.captured'  => $this->handleSuccess($sessionId, $status, $amount, $currency, $customerEmail),

            'payment_session.failed'    => $this->handleFailure($sessionId, $lastError, $customerEmail),

            'payment_session.pending'   => $this->handlePending($sessionId, $customerEmail),

            default => Log::info("Ryft webhook: unhandled event [{$eventType}]"),
        };

        return response()->json(['received' => true], 200);
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function handleSuccess(
        ?string $sessionId,
        ?string $status,
        ?int    $amount,
        ?string $currency,
        ?string $customerEmail
    ): void {
        Log::info("Ryft: Payment SUCCESS", [
            'sessionId' => $sessionId,
            'status'    => $status,
            'amount'    => $amount,
            'currency'  => $currency,
            'email'     => $customerEmail,
        ]);

        // TODO: Update your order/payment record in the database
        // Payment::where('session_id', $sessionId)->update(['status' => 'paid']);

        // TODO: Send a confirmation email to the customer
        // Mail::to($customerEmail)->send(new PaymentConfirmedMail(...));
    }

    private function handleFailure(?string $sessionId, ?string $lastError, ?string $customerEmail): void
    {
        Log::error("Ryft: Payment FAILED", [
            'sessionId' => $sessionId,
            'error'     => $lastError,
            'email'     => $customerEmail,
        ]);

        // TODO: Mark order as failed in your database
        // Payment::where('session_id', $sessionId)->update(['status' => 'failed']);

        // TODO: Notify the customer or your team
    }

    private function handlePending(?string $sessionId, ?string $customerEmail): void
    {
        Log::info("Ryft: Payment PENDING", [
            'sessionId' => $sessionId,
            'email'     => $customerEmail,
        ]);

        // TODO: Mark order as pending — wait for a follow-up webhook
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Verify Ryft's HMAC-SHA256 webhook signature.
     * Ryft sends the signature in the "Ryft-Signature" header.
     */
    private function verifySignature(Request $request): bool
    {
        $signature = $request->header('ryft-signature');

        if (!$signature) {
            Log::error('Missing Ryft signature');
            return false;
        }

        $rawBody = $request->getContent();

        $computed = hash_hmac('sha256', $rawBody, $this->webhookSecret);

        // remove prefix if exists
        $signature = str_replace('sha256=', '', $signature);

        if (!hash_equals($computed, $signature)) {
            Log::error('Signature mismatch', [
                'expected' => $computed,
                'received' => $signature,
            ]);
            return false;
        }

        return true;
    }

}
