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

}
