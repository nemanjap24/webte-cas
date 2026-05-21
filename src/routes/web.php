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

Route::get('/animations', function () {
    return view('animations', [
        'apiKey' => config('cas.api_key')
    ]);
})->name('animations');

Route::get('/logs', function () {
    $logs = \App\Models\CasLog::orderByDesc('executed_at')->paginate(15);
    return view('logs', ['logs' => $logs]);
})->name('logs');

Route::get('/logs/export', [\App\Http\Controllers\Api\LogController::class, 'export'])->name('logs.export');

Route::get('/stats', function () {
    $stats = [
        'pendulum_count' => \App\Models\AnimationStatistic::where('animation_name', 'pendulum')->count(),
        'ball_count' => \App\Models\AnimationStatistic::where('animation_name', 'ball')->count(),
        'recent' => \App\Models\AnimationStatistic::orderByDesc('created_at')->limit(50)->get(),
    ];
    return view('stats', $stats);
})->name('stats');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'sk'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
