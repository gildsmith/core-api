<?php

namespace Gildsmith\HubApi\Router\Api;

use Illuminate\Support\Facades\Route;

/**
 * Manages feature routes and registers them with feature-control middleware.
 * This class enables organizing API routes based on features and applying
 * selective access control using the 'feature' middleware.
 */
class FeatureRoutingRegistry
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
            Route::middleware("feature:$feature")->prefix($feature)->group($callables);
        }
    }

    /**
     * Adds routes to the registry, associated with a specific feature.
     */
    public static function add(string $feature, callable $callable): bool
    {
        $feature = strtolower($feature);

        if (! ctype_alnum($feature)) {
            return false;
        }

        if (! in_array($feature, self::$registry)) {
            self::$registry[$feature] = [];
        }

        self::$registry[$feature][] = $callable;

        return true;
    }
}
