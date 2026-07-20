<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Uuid;

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
        // Laravel's notification sender requests a UUID for every outgoing
        // notification. Use Laravel's Symfony UID dependency so Apache never
        // loads Ramsey's UUID implementation from a stale OPcache entry.
        Str::createUuidsUsing(static fn () => Uuid::v4());
    }
}
