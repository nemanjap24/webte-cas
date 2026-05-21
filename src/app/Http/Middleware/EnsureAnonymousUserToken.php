<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureAnonymousUserToken
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->cookie('webte-cas-session')) {
            $token = (string) Str::uuid();

            $response = $next($request);

            return $response->cookie(
                'webte-cas-session',
                $token,
                60 * 24 * 365
            );
        }

        return $next($request);
    }
}