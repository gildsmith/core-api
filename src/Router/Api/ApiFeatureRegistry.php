<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Router\Api;

use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

class ApiFeatureRegistry
{
    protected static array $registry = [];

    /** todo adds new feature under given name to the list */
    public static function add(string $feature, ApiFeatureRoutes $apiFeatureRoutes): void
    {
        if (! in_array($feature, self::$registry)) {
            self::$registry[$feature] = [];
        }

        self::$registry[$feature][] = $apiFeatureRoutes;
    }

    /**
     * todo old
     * Registers all collected feature routes under their respective prefixes,
     * protected by the 'feature' middleware. This middleware checks if a
     * requested feature is enabled before allowing access to its routes.
     */
    public static function trigger(): void
    {
        foreach (self::$registry as $feature => $callables) {
            Feature::define($feature, fn () => true);
            Route::middleware(EnsureFeaturesAreActive::using($feature))->group(function () use ($feature, $callables) {
                /** @var ApiFeatureRoutes $callable */
                foreach ($callables as $callable) {
                    $callable->triggerAll($feature);
                }
            });
        }
    }
}
