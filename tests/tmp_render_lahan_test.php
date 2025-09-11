<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// instantiate component
$c = new App\Livewire\Admin\LahanManagement();
try {
    if (method_exists($c, 'mount')) {
        $c->mount();
    }
} catch (Throwable $e) {
    echo "Mount error: " . $e->getMessage() . PHP_EOL;
}
try {
    $view = $c->render();
    echo "Render OK" . PHP_EOL;
} catch (Throwable $e) {
    echo "Render error: " . $e->getMessage() . PHP_EOL;
}
