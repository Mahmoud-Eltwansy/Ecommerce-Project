<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    private $apiKey;
    private $base = 'https://api.currencyfreaks.com/v2.0';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function convert($to, $amount = 1)
    {
        $response = Http::baseUrl($this->base)
            ->get('rates/latest', [
                'apikey' => $this->apiKey,
            ]);
        $result = $response->json();
        $rate = $result['rates'][$to] * $amount;
        return $rate;
    }
}
