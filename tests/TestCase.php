<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure testing database exists
        $databasePath = database_path('testing.sqlite');
        if (!file_exists($databasePath)) {
            @touch($databasePath);
        }

        // Force tests to use SQLite file regardless of .env
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $databasePath,
        ]);
    }
}
