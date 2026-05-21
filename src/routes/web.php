<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/console', function () {
    return view('console');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'sk'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
