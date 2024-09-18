<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi;

use Gildsmith\CoreApi\Router\Api\ApiFeatureBuilder;
use Gildsmith\CoreApi\Router\Api\ApiFeatureRegistry;
use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Gildsmith
{
    /**
     * Initializes all API routes registered via Gildsmith.
     *
     * This method should be invoked in `/routes/api.php` to set up
     * API routes. It also defines a default 404 response for any
     * routes that are not explicitly handled.
     */
    public function api(): void
    {
        ApiFeatureRegistry::call();

        $callback = fn () => response(null, 404);

        Route::any('/', $callback);
        Route::any('{route}', $callback)->where('route', '.*');
    }

    /**
     * Initializes web routes registered via Gildsmith.
     *
     * This method should be invoked in `/routes/web.php` to set up
     * web routes. It restricts access based on user roles defined
     * within each application and redirects unauthorized users to
     * the homepage.
     */
    public function web(): void
    {
        Route::fallback(function (Request $request, string $route = '/') {
            $webapp = WebRegistry::get($route);

            return empty($webapp->restricted) || in_array($request->user()?->role->name, $webapp->restricted)
                ? response()->view($webapp->getTemplate(), compact('webapp'))
                : response()->redirectTo('/');
        });
    }

    /**
     * Registers a web application and returns its builder instance.
     *
     * If a name is provided, the application is registered with that
     * name in the WebRegistry. If no name is provided, the application
     * is set as the fallback. The fallback application is used when no
     * matching route or application is found.
     */
    public function app(?string $name = null): WebAppBuilder
    {
        $applicationObject = new WebAppBuilder($name);
        $name === null
            ? WebRegistry::setFallback($applicationObject)
            : WebRegistry::add($applicationObject);

        return $applicationObject;
    }

    /**
     * Registers an API feature and returns its builder instance.
     *
     * Associates a feature with a unique name and provides a builder
     * for configuring the feature's behavior within the API.
     */
    public function feature(string $featureName): ApiFeatureBuilder
    {
        $featureObject = new ApiFeatureBuilder($featureName);
        ApiFeatureRegistry::add($featureName, $featureObject);

        return $featureObject;
    }
}
