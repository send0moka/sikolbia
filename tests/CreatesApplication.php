<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Force testing DB config here too (paranoid)
        $databasePath = database_path('testing.sqlite');
        if (!file_exists($databasePath)) {
            @touch($databasePath);
        }
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $databasePath,
        ]);

        return $app;
    }
}
