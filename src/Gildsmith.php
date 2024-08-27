<?php

declare(strict_types=1);

namespace Gildsmith\HubApi;

use Gildsmith\HubApi\Router\Api\ApiFeatureRegistry;
use Gildsmith\HubApi\Router\Api\ApiFeatureRoutes;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Gildsmith\HubApi\Router\Web\WebRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Gildsmith
{
    /**
     * Directs all requests with the '/api' prefix
     * to the ApiRouter for JSON-focused handling.
     */
    public function api(): void
    {
        ApiFeatureRegistry::trigger();

        $callback = fn () => response(null, 404);

        Route::any('/', $callback);
        Route::any('{route}', $callback)->where('route', '.*');
    }

    /**
     * Serves as a catch-all for frontend routes,
     * forwarding requests to the WebRouter.
     *
     * This enables your frontend application (powered by Vue
     * or a similar framework) to manage its own internal
     * routing for seamless user experience.
     */
    public function web(): void
    {
        Route::fallback(function (Request $request, string $route = '/') {
            $webapp = WebRegistry::get($route);

            return empty($webapp->restricted) || in_array($request->user()?->role->name, $webapp->restricted)
                ? response()->view($webapp->template, compact('webapp'))
                : response()->redirectTo('/');
        });
    }

    public function app(?string $name = null): WebApplication
    {
        $applicationObject = new WebApplication($name);
        $name === null
            ? WebRegistry::setFallback($applicationObject)
            : WebRegistry::add($applicationObject);

        return $applicationObject;
    }

    public function feature(string $featureName): ApiFeatureRoutes
    {
        $featureObject = new ApiFeatureRoutes();
        ApiFeatureRegistry::add($featureName, $featureObject);

        return $featureObject;
    }
}
