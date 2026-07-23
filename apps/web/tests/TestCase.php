<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $database = (string) config('database.connections.mongodb.database');
        if ($database !== 'massage_automated_test') {
            throw new RuntimeException("Refusing to run automated MongoDB tests against [{$database}]. Expected [massage_automated_test].");
        }
    }
}
