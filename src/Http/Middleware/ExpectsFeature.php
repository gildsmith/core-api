<?php

namespace Gildsmith\HubApi\Http\Middleware;

use Closure;
use Gildsmith\HubApi\Router\Api\FeatureRegistry;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to verify if a specified feature is enabled before
 * allowing access to a route. Use as 'feature:expected_feature_name'
 * in route definitions. This middleware is automatically applied
 * to routes registered via Gildsmith::registerFeatureRoutes.
 */
class ExpectsFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        return ! in_array($feature, FeatureRegistry::get())
            ? response(null, 404)
            : $next($request);
    }
}
