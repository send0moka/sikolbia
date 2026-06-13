<?php

namespace App\Providers;

use App\Http\Middleware\EnsureChatbotAnonId;
use App\Listeners\AssignDefaultRole;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default pagination view to Tailwind
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Set timezone for database connections only in non-testing environment
        if (!app()->environment('testing')) {
            if (config('database.default') === 'sqlite') {
                // For SQLite, we need to handle timezone conversion in the application layer
                // SQLite doesn't have built-in timezone support
            } elseif (config('database.default') === 'mysql') {
                // For MySQL, set the timezone
                try {
                    DB::statement("SET time_zone = '+07:00'");
                } catch (\Exception $e) {
                    // Silently ignore if MySQL is not available
                }
            }
        }

        // Public chatbot rate limiting (Option B): per-anon-cookie, fallback to IP if cookies are blocked.
        RateLimiter::for('chatbot-user', function (Request $request) {
            $fromCookie = (bool) $request->attributes->get(EnsureChatbotAnonId::REQUEST_ATTR_FROM_COOKIE, false);
            $anonId = (string) $request->attributes->get(EnsureChatbotAnonId::REQUEST_ATTR, '');

            $key = ($fromCookie && $anonId !== '')
                ? ('anon:' . $anonId)
                : ('ip:' . (string) $request->ip());

            return Limit::perMinute(20)
                ->by('chatbot:' . $key)
                ->response(function (Request $request, array $headers) {
                    $retryAfter = isset($headers['Retry-After']) ? (int) $headers['Retry-After'] : null;
                    $suffix = ($retryAfter !== null && $retryAfter > 0) ? " dalam {$retryAfter} detik" : '';

                    return response()->json([
                        'reply' => "Terlalu banyak permintaan. Silakan coba lagi{$suffix}.",
                        'mode' => (string) $request->input('mode', 'natural'),
                        'rate_limited' => true,
                        'retry_after_seconds' => $retryAfter,
                    ], 200)->withHeaders($headers);
                });
        });

        // Register event listener for assigning default role
        Event::listen(
            Registered::class,
            AssignDefaultRole::class,
        );
    }
}
