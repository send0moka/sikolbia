<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ForceAppUrl
{
    public function handle($request, Closure $next)
    {
        $appUrl = config('app.url');

        if ($appUrl) {
            URL::forceRootUrl($appUrl);

            // Automatically force HTTPS if your APP_URL starts with https://
            if (Str::startsWith($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        return $next($request);
    }
}
