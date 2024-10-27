<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Providers;

use Gildsmith\CoreApi\Facades\Gildsmith;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * TODO
 */
final class TestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('api')->prefix('api')->group(function () {
            Gildsmith::api();
        });
    }
}
