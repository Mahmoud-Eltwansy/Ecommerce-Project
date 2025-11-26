<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LocaleController extends Controller
{
    /**
     * Switch application locale and redirect to localized URL.
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');
        $redirect = $request->input('redirect', $request->fullUrl());

        // Validate supported locale — fallback to current locale if invalid
        $supported = LaravelLocalization::getSupportedLocales();
        if (! array_key_exists($locale, $supported)) {
            $locale = app()->getLocale();
        }

        $localizedUrl = LaravelLocalization::getLocalizedURL($locale, $redirect);

        return Redirect::to($localizedUrl);
    }
}