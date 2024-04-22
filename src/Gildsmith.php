<?php
/** @noinspection PhpUnused */

namespace Gildsmith\HubApi;

use Gildsmith\HubApi\Router\Api\FeatureRegistry;
use Gildsmith\HubApi\Router\Api\FeatureRoutingRegistry;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Gildsmith\HubApi\Router\Web\WebRegistry;
use Gildsmith\HubApi\Router\Web\WebRouter;
use Illuminate\Support\Facades\Route;

class Gildsmith
{
    /** @var string The current version of the Gildsmith Hub package */
    public const VERSION = '1.0.0-alpha';

    /**
     * Directs all requests with the '/api' prefix
     * to the ApiRouter for JSON-focused handling.
     */
    public function api(): void
    {
        FeatureRoutingRegistry::trigger();

        $callback = fn() => (response(null, 404));

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
        Route::fallback(function (string $route = '/') {
            return (new WebRouter())($route);
        });
    }

    /**
     * Adds a web application to the registry.
     *
     * @param WebApplication $webApplication The application to be registered.
     */
    public function registerWebApplication(WebApplication $webApplication): void
    {
        WebRegistry::add($webApplication);
    }

    public function registerFallbackWebApplication(WebApplication $webApplication): void
    {
        WebRegistry::setFallback($webApplication);
    }

    /**
     * Registers routes for a specific feature.
     *
     * @param string $feature The feature name.
     * @param callable $callable A closure containing route definitions.
     */
    public function registerFeatureRoutes(string $feature, callable $callable): void
    {
        FeatureRoutingRegistry::add($feature, $callable);
    }

    /**
     * Registers multiple features.
     *
     * @param string[] $features An array of feature names.
     */
    public function registerFeatures(string ...$features): void
    {
        foreach ($features as $feature) {
            FeatureRegistry::add($feature);
        }
    }
}