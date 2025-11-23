<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use NumberFormatter;

class Currency
{

    public static function format($amount, $currency = null)
    {
        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        $baseCurrency = config('app.currency', 'EGP');

        // Get the currency code
        if ($currency === null) {
            $currency = Session::get('currency_code') ?? $baseCurrency;
        }

        if ($baseCurrency != $currency) {
            $rate = Cache::get('currency_rate_' . $currency, 1);
            $amount *= $rate;
        }
        return $formatter->formatCurrency($amount, $currency);
    }
}
