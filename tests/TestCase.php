<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $db = database_path('database.sqlite');
        if (config('database.default') === 'sqlite' && ! file_exists($db)) {
            touch($db);
        }
    }
}
