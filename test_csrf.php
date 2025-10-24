<?php

// Quick CSRF Token Test
// Run: docker-compose exec app php test_csrf.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "================================\n";
echo "CSRF & Session Configuration Test\n";
echo "================================\n\n";

// Check Session Config
echo "1. Session Configuration:\n";
echo "   Driver: " . config('session.driver') . "\n";
echo "   Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   Domain: " . (config('session.domain') ?: 'null') . "\n";
echo "   Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "   Same Site: " . config('session.same_site') . "\n\n";

// Check if sessions table exists
echo "2. Database Check:\n";
try {
    $sessionsExists = \Illuminate\Support\Facades\Schema::hasTable('sessions');
    echo "   Sessions table: " . ($sessionsExists ? "✅ EXISTS" : "❌ MISSING") . "\n";
    
    if ($sessionsExists) {
        $count = \Illuminate\Support\Facades\DB::table('sessions')->count();
        echo "   Active sessions: $count\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Check APP_KEY
echo "3. Encryption Key:\n";
$appKey = config('app.key');
if ($appKey && str_starts_with($appKey, 'base64:')) {
    echo "   ✅ APP_KEY is set correctly\n";
} else {
    echo "   ❌ APP_KEY is missing or invalid\n";
    echo "   Run: php artisan key:generate\n";
}
echo "\n";

// Check Route
echo "4. Registration Routes:\n";
$routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->filter(function($route) {
    return str_contains($route->uri(), 'registrasi');
})->map(function($route) {
    return $route->methods()[0] . ' ' . $route->uri();
});

foreach ($routes as $route) {
    echo "   ✅ $route\n";
}
echo "\n";

echo "================================\n";
echo "Recommendations:\n";
echo "================================\n";
echo "1. Clear browser cookies for localhost:8000\n";
echo "2. Open form in private/incognito window\n";
echo "3. Check browser console for errors\n";
echo "4. Ensure you're accessing via http://localhost:8000\n";
echo "\n";

echo "Quick Fix Commands:\n";
echo "  php artisan config:clear\n";
echo "  php artisan cache:clear\n";
echo "  php artisan view:clear\n";
echo "\n";
