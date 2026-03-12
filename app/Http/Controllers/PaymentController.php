<?php

namespace App\Http\Controllers;

use App\Services\RyftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    protected $ryft;

    public function __construct(RyftService $ryft)
    {
        $this->ryft = $ryft;
    }

    public function createPayment(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'sk_sandbox_oQiFa4zlwTQlVp1en7P+MoO4Ng2f21Pxvp0Pi1I598UOq7kcAX9SMPhY0Nc8LRd2',
            'Content-Type'  => 'application/json',
        ])->post('https://sandbox-api.ryftpay.com/v1/payment-sessions', [ // ✅ sandbox URL
            'amount'        => 1000,
            'currency'      => 'GBP',
            'customerEmail' => 'test@ryftpay.com', // ✅ customerEmail not email
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

    public function attemptPayment(Request $request)
    {
        $validated = $request->validate([
            'clientSecret'  => 'required|string',
            'number'        => 'required|string',
            'expiryMonth'   => 'required|string',
            'expiryYear'    => 'required|string',
            'cvc'           => 'required|string',
        ]);


        $response = Http::withHeaders([
            'Authorization' =>'pk_sandbox_XG8WXCWRNeK0MBvSvlgSHbB9KhUU5l+snhjsvb49Ek3a3FgjIAPpaQJ3rl8CLJOw',
            'Content-Type'  => 'application/json',
            'Account'       => 'ac_ba1145db-1ec9-4ebf-b468-e5ac6834d414',
        ])->post('https://sandbox-api.ryftpay.com/v1/payment-sessions/attempt-payment', [
            'clientSecret' => $validated['clientSecret'],
            'cardDetails'  => [
                'number'      => $validated['number'],
                'expiryMonth' => $validated['expiryMonth'],
                'expiryYear'  => $validated['expiryYear'],
                'cvc'         => $validated['cvc'],
            ],
        ]);


        dd([
            'vallidation' => $validated,
            'status'   => $response->status(),
            'response' => $response->json(),
            'sent'     => [
                'clientSecret' => $validated['clientSecret'],
                'number'       => $validated['number'],
            ]
        ]);
    }
}
