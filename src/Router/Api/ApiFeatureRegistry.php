<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Router\Api;

use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

/**
 * Manages feature routes and registers them with feature-control middleware.
 * This class enables organizing API routes based on features and applying
 * selective access control using the 'feature' middleware.
 */
class ApiFeatureRegistry
{
    protected static array $registry = [];

    /**
     * Registers all collected feature routes under their respective prefixes,
     * protected by the 'feature' middleware. This middleware checks if a
     * requested feature is enabled before allowing access to its routes.
     */
    public static function trigger(): void
    {
        foreach (self::$registry as $feature => $callables) {
            Feature::define($feature, fn () => true);
            Route::middleware(EnsureFeaturesAreActive::using($feature))->prefix($feature)->group($callables);
        }
    }

    /**
     * Adds routes to the registry, associated with a specific feature.
     */
    public static function add(string $feature, callable $callable): bool
    {
        if (! in_array($feature, self::$registry)) {
            self::$registry[$feature] = [];
        }

        self::$registry[$feature][] = $callable;

        return true;
    }
}
