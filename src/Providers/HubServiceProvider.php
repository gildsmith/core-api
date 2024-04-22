<?php

namespace Gildsmith\HubApi\Providers;

use Gildsmith\HubApi\Gildsmith;
use Illuminate\Support\ServiceProvider;

class HubServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('gildsmith', function () {
            return new Gildsmith();
        });
    }
}