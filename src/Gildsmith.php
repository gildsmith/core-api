<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi;

use Gildsmith\CoreApi\Router\Api\FeatureBuilder;
use Gildsmith\CoreApi\Router\Api\FeatureRegistry;
use Gildsmith\CoreApi\Router\Web\AppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Gildsmith
{
    /**
     * TODO refactor pending
     *
     * This method should be triggered within `/routes/api.php`
     * to load all API routes registered via Gildsmith::feature()
     */
    public function api(): void
    {
        FeatureRegistry::trigger();

        $callback = fn () => response(null, 404);

        Route::any('/', $callback);
        Route::any('{route}', $callback)->where('route', '.*');
    }

    /**
     * TODO refactor pending
     *
     * This method should be triggered within `/routes/web.php`
     * to load all API routes registered via Gildsmith::app().
     *
     * Its sole purpose is to redirect all unregistered routes
     * to custom registry. Traffic is then distributed to apps
     * registered via Gildsmith.
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

    /**
     * TODO register web application
     */
    public function app(?string $name = null): AppBuilder
    {
        $applicationObject = new AppBuilder($name);
        $name === null
            ? WebRegistry::setFallback($applicationObject)
            : WebRegistry::add($applicationObject);

        return $applicationObject;
    }

    /**
     * TODO register api feature
     */
    public function feature(string $featureName): FeatureBuilder
    {
        $featureObject = new FeatureBuilder;
        FeatureRegistry::add($featureName, $featureObject);

        return $featureObject;
    }
}
