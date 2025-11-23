<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CurrencyConverterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => ['required', 'string', 'size:3']
        ]);

        $currencyCode = strtoupper($request->input('currency_code'));
        $cacheCurrencyKey = 'currency_rate_' . $currencyCode;

        $converter = app()->make('currency.converter');

        // Cache the rate on the server, or get if already cached
        Cache::remember($cacheCurrencyKey, now()->addMinutes(60), function () use ($converter, $currencyCode) {
            return $converter->convert($currencyCode);
        });

        // Store user's selected currency in their session.
        Session::put('currency_code', $currencyCode);

        return redirect()->back();
    }
}
