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
            ? $this->respond($feature)
            : $next($request);
    }

    public function respond(string $feature): Response
    {
        return config('gildsmith.silent_features', false)
            ? response(null, 404)
            : response()->json($this->error($feature), 503);
    }

    public function error(string $feature): array
    {
        $feature = ucfirst($feature);

        return [
            'error' => "$feature is temporarily disabled.",
        ];
    }
}
