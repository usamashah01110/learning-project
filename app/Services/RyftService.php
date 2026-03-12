<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RyftService
{
    protected $secret;
    protected $baseUrl;

    public function __construct()
    {
        $this->secret = config('services.ryft.secret');
        $this->baseUrl = config('services.ryft.base_url');
    }

    public function createPaymentSession($amount, $currency, $email)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->secret,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/payment-sessions', [
            'amount' => $amount,
            'currency' => $currency,
            'customerEmail' => $email
        ]);

        return $response->json();
    }
}
