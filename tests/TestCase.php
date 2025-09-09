<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure testing database exists
        $databasePath = database_path('testing.sqlite');
        if (!file_exists($databasePath)) {
            touch($databasePath);
        }
    }
}
