<?php

namespace Tests\Concerns;

/**
 * Feature tests run against MONGODB_DATABASE=massage_test (see phpunit.xml),
 * a separate database from the massage_main development data. This trait
 * only clears user_main between tests; it does not touch massage_main.
 */
use App\Models\User;

trait InteractsWithMongoUsers
{
    protected function setUpInteractsWithMongoUsers(): void
    {
        User::query()->delete();
    }

    protected function tearDownInteractsWithMongoUsers(): void
    {
        User::query()->delete();
    }
}
