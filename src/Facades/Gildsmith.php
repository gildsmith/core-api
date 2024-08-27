<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Facades;

use Gildsmith\HubApi\Router\Web\WebApplication;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void api()
 * @method static void web()
 * @method static void registerWebApplication(WebApplication $webApplication)
 * @method static void registerFallbackWebApplication(WebApplication $webApplication)
 * @method static void registerApiFeature(string $feature, callable $callable)
 */
class Gildsmith extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gildsmith';
    }
}
