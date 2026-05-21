<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/console', function () {
    // Implement anonymous token in cookies as per requirement (11)
    $sessionToken = request()->cookie('cas_session_token');
    
    if (!$sessionToken) {
        $sessionToken = Str::uuid()->toString();
        cookie()->queue('cas_session_token', $sessionToken, 60 * 24 * 30); // 30 days
    }

    return view('console', [
        'sessionToken' => $sessionToken,
        'apiKey' => config('cas.api_key')
    ]);
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'sk'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
