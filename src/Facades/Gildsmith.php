<?php

namespace Gildsmith\HubApi\Facades;

use Gildsmith\HubApi\Router\Web\WebApplication;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void api()
 * @method static void web()
 *
 * @method static void registerWebApplication(WebApplication $webApplication)
 * @method static void registerFallbackWebApplication(WebApplication $webApplication)
 *
 * @method static void registerFeatures(string ...$features)
 * @method static void registerFeatureRoutes(string $feature, callable $callable)
 */
class Gildsmith extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gildsmith';
    }
}